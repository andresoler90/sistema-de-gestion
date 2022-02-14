<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;
use App\Models\RegisterEvent;
use DateTime;
use App\Models\Register;
use App\Models\RegisterTask;
use App\Models\User;

class SuspendedRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SuspendedRequests:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela las solicitudes que han sido suspendidas y no se han gestionado en el tiempo establecido';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            // Obtenemos todos los registros con el estado suspendido: 7
            $registers = Register::where('states_id',7)->get();

            foreach ($registers as $register) {
                $last_event =  $register->lastEvent();

                $date_suspended_more_3_months = new DateTime(date('Y-m-d H:i:s', strtotime($last_event->created_at . ' + 3 month')));
                // echo $date_suspended_more_3_months->format('Y-m-d H:i:s').'  -  ';
                if($date_suspended_more_3_months >= now()){
                    // echo ' --[Ya se paso]-- ';

                    // Se cancela la solicitud
                    $register->states_id = 4; // 4: Cancelado
                    $register->save();

                    //Se cierran las tareas y los eventos de la solicitud
                    $register_events = RegisterEvent::where('registers_id',$register->id)->get();
                    $register_tasks = RegisterTask::where('registers_id',$register->id)->get();

                    foreach ($register_events as $register_event) {
                        $register_event->finished_at = now();
                        $register_event->save();
                    }

                    foreach ($register_tasks as $register_task) {
                        $register_task->status = 'C';
                        $register_task->end_date = now();
                        $register_task->save();
                    }

                    // Se crea un nuevo evento
                    $register_event = new RegisterEvent();
                    $register_event->registers_id = $register->id;
                    $register_event->stage_tasks_id = $last_event->stage_tasks_id;
                    $register_event->states_id = $register->states_id;
                    $register_event->register_tasks_id = $last_event->register_tasks_id;
                    $register_event->management = $last_event->management;
                    $register_event->description = 'Se cancela la solicitud, despues de 3 meses no se presentaron modificaciones';
                    $register_event->created_users_id = $last_event->created_users_id;
                    $register_event->finished_at = now();
                    $register_event->save();

                    $user = User::find($register->requesting_users_id);
                    // Se notifica al cliente y al proveedor
                    Mail::to($user->email)
                    ->bcc($register->email_contact)->send(new GeneralNotification(
                        'Solicitud cancelada',
                        "La solicitud $register->code ha sido cancelada, debido a que la solicitud se encontraba suspendida y despues de 3 meses no se presentaron modificaciones",
                    ));
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la ejecución del comando SuspendedRequests',
                $exception->getMessage()
            ));
        }
    }
}
