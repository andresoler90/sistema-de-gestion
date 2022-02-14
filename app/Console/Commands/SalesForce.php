<?php

namespace App\Console\Commands;

use App\Http\Controllers\Libs\ProcessFlow;
use App\Mail\GeneralNotification;
use App\Models\ClientTask;
use App\Models\Register;
use App\Models\RegisterContact;
use App\Models\RegisterEvent;
use App\Models\RegisterSalesForce;
use App\Models\RegisterSalesforceActivity;
use App\Models\RegisterTask;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SalesForce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @type = opportunity is default
     */
    protected $signature = 'salesforce:find {type=opportunity} {register_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta el estado de la oportunidad en salesforce';

    /**
     * @var \App\Http\Controllers\Libs\SalesForce
     */
    private $salesForce;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->salesForce = new \App\Http\Controllers\Libs\SalesForce();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            if ($this->argument('type') == 'opportunity') {

                $register = Register::where('id', $this->argument('register_id'))->first();

                if ($register)
                    $data = RegisterSalesForce::where('register_id', $register->id)->where('status', 'P')->get();
                else
                    $data = RegisterSalesForce::where('status', 'P')->get();

                $bar = $this->output->createProgressBar(count($data));
                $bar->start();

                foreach ($data as $sales_f) {
                    $register = $sales_f->registerWithTrashed;

                    // Se obtienen los contactos por ID Cuenta salesforce
                    $salesForceContacts = $this->salesForce->getContactByIdAccount($sales_f->account);
                    $this->saveContactRegister($register, $salesForceContacts);

                    // Se consula la oportunidad
                    $response = $this->salesForce->getOpportunityAll($sales_f->opportunity)->first();
                    if ($response) {

                        if ($response->StageName == 'En ejecución y facturado. Oppty ganada') {
                            $sales_f->status = 'E';
                            // Avanza a etapa de gestión documental
                            $save = $this->saveNextStage($register);
                            if($save==false){
                                DB::rollBack();
                                Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                                    'Problema en la ejecución del comando SalesForce'. $this->argument('type'),
                                    'No hay analistas para gestionar la solicitud'
                                ));
                            }
                        } elseif ($response->StageName == 'Oppty perdida') {
                            $sales_f->status = 'E';
                            // Cancelar solicitud
                            $this->cancelProcess($register);
                        }

                        $sales_f->response_ws = json_encode($response);
                        $sales_f->save();
                    }
                    // Actividades de la oportunidad
                    $activities = $this->salesForce->getActivitiesByIdOpportunity($sales_f->opportunity);
                    if (count($activities)) {
                        foreach ($activities as $activity) {
                            $registerActivity = RegisterSalesforceActivity::updateOrCreate(
                                ['account' => $activity->AccountId, 'opportunity' => $activity->WhatId, 'activity' => $activity->Id, 'register_id' => $register->id],
                                ['data_json' => json_encode($activity)]
                            );
                        }
                    }

                    $bar->advance();
                }
                $bar->finish();
                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la ejecución del comando SalesForce'. $this->argument('type'),
                $exception->getMessage()
            ));
        }
    }

    public function saveNextStage($register)
    {
        $processFLow = new ProcessFlow($register);
        $currentTask = $processFLow->currentTask();
        $nextClientTask = $processFLow->nextTask();
        $analyst = true;
        if ($currentTask->id == 3) { // Proceso solo para etapa comercial
            // consultamos register_tasks activa
            $register_task = $register->tasks->where('status', 'A')->first();
            if ($register_task) { // se cierra tarea
                $register_task->status = 'C';
                $register_task->end_date = now();
                if ($register_task->save()) { // Se cierra register_events
                    $events = $register_task->events->first();
                    $events->finished_at = now();
                    $events->save();
                }
            }
            // save new task and event
            $newTask = new RegisterTask();
            $newTask->client_tasks_id = $nextClientTask->id;
            $newTask->registers_id = $register->id;
            $newTask->start_date = now();
            $newTask->created_users_id = $register->created_users_id;
            $newTask->analyst_users_id = $processFLow->analyticsAvaible($nextClientTask->stage_tasks_id,$register->identification_number);
            $analyst = $newTask->analyst_users_id == null? false : true;

            if ($newTask->save()) {
                $newEvent = new RegisterEvent();
                $newEvent->registers_id = $register->id;
                $newEvent->stage_tasks_id = $nextClientTask->stage_tasks_id;
                $newEvent->states_id = $register->states_id; //[states_id]
                $newEvent->register_tasks_id = $newTask->id;
                $newEvent->description = $nextClientTask->stage_tasks_id == 4 ? 'Matriz Documental, Seguimiento 1' : 'Analisis ' . $nextClientTask->stage_task->name;
                $newEvent->created_users_id = $register->created_users_id;
                $newEvent->save();
            }
        }

        return $analyst;
    }

    public function cancelProcess($register)
    {
        $register->states_id = 4; // cancelado
        if ($register->save()) {
            $register_task = $register->tasks->where('status', 'A')->last();
            if ($register_task) { // se cierra tarea
                $register_task->status = 'C';
                $register_task->end_date = now();
                if ($register_task->save()) {
                    // Se cierra el evento
                    $events = $register_task->events->last();
                    $events->finished_at = now();
                    $events->save();

                    // Se crea un nuevo evento
                    $register_event = new RegisterEvent();
                    $register_event->registers_id = $register->id;
                    $register_event->stage_tasks_id = $events->stage_tasks_id;
                    $register_event->states_id = $register->states_id; //[states_id]
                    $register_event->register_tasks_id = $events->register_tasks_id;
                    $register_event->management = 'PAR';
                    $register_event->description = 'Se Cancela la solicitud';
                    $register_event->created_users_id = $events->created_users_id;
                    $register_event->finished_at = now();
                    $register_event->save();
                }
            }
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Solicitud Cancelada',
                "Se ha cancelado la solicitud para el proveedor: " . $register->identification_number
            ));
        }
    }

    /**
     * @param $register
     * @param $salesForceContacts
     * @TODO Registro de contactos de salesforce solo para etapa comercial
     */
    public function saveContactRegister($register,$salesForceContacts)
    {
        $processFLow = new ProcessFlow($register);
        $currentTask = $processFLow->currentTask();
        if ($currentTask->id == 3) { // Proceso solo para etapa comercial
            foreach ($salesForceContacts as $contact){
                $roleContact = $this->salesForce->getContactsRoleById($contact->Id)->first();
                $contact->Role = $roleContact ? $roleContact->Role: null;
                $contact->IsPrimary = $roleContact ? $roleContact->IsPrimary: null;

                $registerContact = RegisterContact::updateOrCreate(
                    ['account_id' => $contact->AccountId, 'contact_id' => $contact->Id,'register_id' => $register->id],
                    ['data_json' => json_encode($contact)]
                );
            }
        }
    }
}
