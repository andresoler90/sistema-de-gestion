<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\MiProveedor;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Mail\GeneralNotification;
use App\Models\ClientTask;
use App\Models\MiProveedor\Cl1Clients;
use App\Models\MiProveedor\Pv15StatesRelations;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Object_;

class AnalysisManagementController extends Controller
{

    protected $register;
    protected $miProveedor;
    protected $provider;
    protected $client;
    protected $groupClient;
    protected $closeTaskStage = true; //Cerrar tarea y etapa
    public $nextStage;
    public $comments; // Comentarios del flujo
    protected $canceled; // Solicitud anulada
    public $processData;

    public function __construct(Register $register)
    {
        $this->register = $register;
        $this->processData = new Object_();

        if ($this->register->register_assumed_by == 'C') {
            $this->process();
        } else {
            $this->miProveedor = new MiProveedor();
            $this->provider = $this->miProveedor->getProvider($this->register->identification_number, $this->register->check_digit);
            $this->client = $this->register->client;// Cliente asociado al registro

            if ($this->provider) {//validacion de existencia proveedor
                $this->getGroupClient();// validacion si cliente mp pertenece a un grupo
                $this->process();
            } else {
                $clientTask = ClientTask::where([['clients_id', $this->client->id], ['stage_tasks_id', 3]])->count();//Si se encuentra habilitada la etapa comercial
                $this->comments[] = 'Proveedor No Existe en MP - Creacion de etapa comercial';
                if ($clientTask)
                    $this->nextStage = 3;//enviar a etapa comercial
                else
                    $this->nextStage = 4;//gestion documental
            }
        }
        //dd($this);
        $this->createTaskAndEvent();
        $this->nextStageEvent();
    }

    public function process()
    {
        if ($this->register->register_assumed_by == 'C') {
            $this->comments[] = 'Registro asumido por el cliente';
            $this->nextStage = 4; //Se envia a etapa gestion documental
        } elseif ($this->register->register_assumed_by == 'P') {

            $this->comments[] = 'Registro asumido por el proveedor';
            $this->processData->stateProvider = $this->provider->pv15ClientAccepted($this->client->mp_clients_id);// Estados del proveedor cliente mp
            $this->processData->process1 = $this->provider->pv15()->client(2)->PaymentIntegral()->Active()->where('pv15_end_date', '>', date('Y-m-d', strtotime("+1 month")))->get();
            $this->processData->process2 = $this->provider->pv15()->client(2)->PaymentPartial()->Active()->where('pv15_end_date', '>', date('Y-m-d'))->get();
            $this->processData->process2b = [];
            $this->processData->process3 = $this->provider->pv15()->client(2)->PaymentPartial()->Active()->whereBetween('pv15_end_date', [date('Y-m-d'), date('Y-m-d', strtotime("+1 month"))])->get();//Vigencia <1mes con Servicio al Proveedor
            $paymentsByClient = $this->provider->paymentsByClient($this->client->mp_clients_id)->Partial()->Active()->get();//pagos realizados por el cliente

            if ($paymentsByClient->count()){
                $dataPv15ByClient = $this->provider->pv15()->client($this->client->mp_clients_id)->Active()->where('pv15_end_date', '>', date('Y-m-d'))->first();
                if ($dataPv15ByClient)
                    $this->processData->process2b[] = $dataPv15ByClient;

                foreach ($paymentsByClient as $data) {
                    $datePayment = new Carbon($data->paym1_paymentDate);// Fecha de pago
                    $datePayAdd1Year = $datePayment->addYear()->toDateString(); // Se añade 1 año a la fecha de pago
                    $currentDate = date('Y-m-d');// Fecha actual

                    if ($datePayAdd1Year > $currentDate)//Fecha final de pago sea mayor a fecha actual
                        $this->processData->process2b[] = $data;
                }
            }

            if ($this->processData->process1->count()) {
                $this->comments[] = 'Tiene pago integral vigente fecha final Servicio al Proveedor > 1 mes';
                if ($this->processData->stateProvider) {
                    $this->comments[] = 'Proveedor es Aceptado para el cliente';
                    $this->canceled = true;
                } else {
                    $this->comments[] = 'Proveedor NO es Aceptado para el cliente';
                    $this->nextStage = 4; //Se envia a etapa gestion documental
                }
            } else {
                $this->comments[] = 'NO Tiene pago integral vigente fecha final Servicio al Proveedor > 1 mes';
                if ($this->processData->process2->count() && count($this->processData->process2b)) {//si
                    $this->comments[] = 'Tiene pago parcial vigente Servicio al Proveedor Y pago parcial para el cliente ancla';
                    if ($this->processData->process3->count()) {//si
                        $this->comments[] = 'Tiene vigencia < 1 mes para Servicio al Proveedor';
                        $this->nextStage = 3; //Se envia a etapa comercial
                    } else {//no
                        $this->comments[] = 'NO Tiene vigencia < 1 mes para Servicio al Proveedor';
                        if ($this->processData->stateProvider) {//si
                            $this->comments[] = 'Proveedor es Aceptado para el cliente';
                            $this->canceled = true;
                        } else {
                            $this->comments[] = 'Proveedor NO es Aceptado para el cliente';
                            $this->nextStage = 4; //Se envia a etapa gestion documental
                        }
                    }
                } else {//no
                    $this->comments[] = 'NO Tiene pago parcial vigente Servicio al Proveedor Y NO tiene pago parcial para el cliente ancla';
                    if ($this->groupClient && count($this->groupClient)) {
                        $this->comments[] = 'Solicitud pertenece a un grupo';
                        $this->processData->process4 = [];
                        $paymentPartialGroup = $this->provider->payments()->partial()->Active()->OrderPaymentDesc()->whereIn('cl1_id', $this->groupClient)->get();

                        foreach ($paymentPartialGroup as $dataGroup) {

                            $dataPv15ByGroup = $this->provider->pv15()->Active()->where('cl1_id', $dataGroup->cl1_id)
                                ->where('pv15_end_date', '>', date('Y-m-d', strtotime("+1 month")))->first();

                            if ($dataPv15ByGroup)
                                $this->processData->process4[] = $dataPv15ByGroup->cl1_id;

                            $datePayment = new Carbon($dataGroup->paym1_paymentDate);// Fecha de pago
                            $datePayAdd1Year = $datePayment->addYear()->toDateString(); // Se añade 1 año a la fecha de pago
                            $dateAddMonth = date('Y-m-d', strtotime("+1 month"));// Vigencia mayor a 1 mes

                            if ($datePayAdd1Year > $dateAddMonth)//Fecha final de pago sea mayor a fecha actual
                                $this->processData->process4[] = $dataGroup->cl1_id;
                        }

                        if (count($this->processData->process4)) {
                            $this->comments[] = 'Tiene pago parcial vigente > 1 mes para una empresa del grupo';
                            $this->processData->process5 = $this->provider->pv15()->whereIn('cl1_id', $this->processData->process4)->Active()->Conf4Accepted()->get();//estados aceptados cliente
                            if ($this->processData->stateProvider) {
                                $this->comments[] = "Es Aceptado para el cliente";
                                $this->canceled = true;
                            } else {
                                $this->comments[] = "NO es Aceptado para el cliente";
                                $this->nextStage = 4; //Se envia a etapa gestion documental
                            }
                        } else {
                            $this->comments[] = 'NO Tiene pago parcial vigente > 1 mes para una empresa del grupo';
                            $this->nextStage = 3; //enviar a etapa comercial
                        }
                    } else {
                        $this->comments[] = 'Solicitud No pertenece a un grupo';
                        $this->nextStage = 3; //enviar a etapa comercial
                    }
                }
            }
        }
    }

    public function getGroupClient()
    {
        $mp_client = $this->client->mp_client;
        if ($mp_client) {
            if ($mp_client->cl5_id) {
                $this->groupClient = Cl1Clients::where('cl5_id', $mp_client->cl5_id)->Active()->pluck('cl1_id')->toArray();
                //$this->groupClient = Pv15StatesRelations::whereIn('cl1_id', $mp_clients_ids)->where('pv1_id', $this->provider->pv1_id)->Active()->get();
            }
        }
    }

    public function createTaskAndEvent()
    {
        $register_task = new RegisterTask();
        $client_task = ClientTask::where([['clients_id', $this->register->clients_id], ['stage_tasks_id', 2]])->first();
        $register_task->client_tasks_id = $client_task->id;
        $register_task->registers_id = $this->register->id;
        $register_task->start_date = now();
        $register_task->end_date = now();
        $register_task->status = 'A';
        $register_task->created_users_id = $this->register->created_users_id;
        $register_task->save();

        $register_event = new RegisterEvent();
        $register_event->registers_id = $this->register->id;
        $register_event->stage_tasks_id = 2; // Tarea 2:Analisis
        $register_event->states_id = $this->register->states_id;  //[states_id]
        $register_event->register_tasks_id = $register_task->id;
        $register_event->management = 'PAR';
        $register_event->description = 'Analisis de la solicitud';
        $register_event->created_users_id = $this->register->created_users_id;
        $register_event->finished_at = now();
        $register_event->save();
    }

    public function nextStageEvent()
    {
        DB::beginTransaction();
        try {
            if ($this->closeTaskStage) {
                $tasks = $this->register->tasks->where('status', 'A')->first();
                if ($tasks) {
                    $tasks->status = 'C';
                    $tasks->end_date = now();
                    if ($tasks->save()) {
                        $events = $tasks->events->first();
                        $events->description = json_encode($this->comments);//todo se habilita para pruebas de flujo, se debe deshabilitar
                        $events->finished_at = now();
                        $events->save();
                    }
                }
                if ($this->nextStage) {
                    $clientTask = ClientTask::where('clients_id', $this->register->clients_id)->where('stage_tasks_id', $this->nextStage)->first();
                    if ($clientTask == null) {
                        $clientTask = new ClientTask();
                        $clientTask->clients_id = $this->register->clients_id;
                        $clientTask->stage_tasks_id = $this->nextStage;
                        $clientTask->save();
                    }
                    $newTask = new RegisterTask();
                    $process_flow = new ProcessFlow();
                    $newTask->client_tasks_id = $clientTask->id;
                    $newTask->registers_id = $this->register->id;
                    $newTask->start_date = now();
                    $newTask->created_users_id = $this->register->created_users_id;
                    $newTask->analyst_users_id = $this->nextStage == 4 ? $process_flow->analyticsAvaible($this->nextStage,$this->register->identification_number) : null;
                    if ($newTask->save()) {
                        $newEvent = new RegisterEvent();
                        $newEvent->registers_id = $this->register->id;
                        $newEvent->stage_tasks_id = $this->nextStage;
                        $newEvent->states_id = $this->register->states_id; //[states_id]
                        $newEvent->register_tasks_id = $newTask->id;
                        $newEvent->description = $this->nextStage == 4 ? 'Matriz Documental, Seguimiento 1' : implode(',',$this->comments);
                        $newEvent->created_users_id = $this->register->created_users_id;
                        $newEvent->save();
                    }
                }
            }
            if ($this->canceled) {
                $processFLow = new ProcessFlow($this->register);
                $cancel = $processFLow->cancelRegister();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Excepción evento de Analisis',
                $e->getMessage()
            ));
        }
    }
}

