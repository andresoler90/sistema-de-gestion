<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use App\Models\RegisterTask;
use App\Models\RegisterEvent;
use App\Models\Tracking;
use App\Models\DocumentManagement;
use App\Models\Register;
use App\Models\RegisterManagement;
use App\Http\Controllers\Libs\GeneralMethods;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Mail\GeneralNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\StageTask;
use App\Models\ClientTask;



class RetrievalController extends Controller
{
    public function basicDetail(RegisterTask $register_task, $continue = 'N')
    {
        $method = $this->getDocuments($register_task, 5, $continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];
        return view('analyst.retrieval.detail', compact('register_task', 'register', 'register_event', 'client_documents','my_registers','other_registers','data','trackings'));
    }

    public function experienceDetail(RegisterTask $register_task, $continue = 'N')
    {
        $method = $this->getDocuments($register_task, 6, $continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];
        return view('analyst.retrieval.detail', compact('register_task', 'register', 'register_event', 'client_documents','my_registers','other_registers','data','trackings'));
    }

    public function financialDetail(RegisterTask $register_task, $continue = 'N')
    {
        $method = $this->getDocuments($register_task, 7, $continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];
        return view('analyst.retrieval.detail', compact('register_task', 'register', 'register_event', 'client_documents','my_registers','other_registers','data','trackings'));
    }

    public function documentDetail(RegisterTask $register_task, $continue = 'N')
    {
        $method = $this->getDocuments($register_task, 8, $continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];
        return view('analyst.retrieval.detail', compact('register_task', 'register', 'register_event', 'client_documents','my_registers','other_registers','data','trackings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'date' => 'required',
            // 'contact_name' => 'required',
            // 'phone' => 'required_without:email',
            // 'email' => 'required_without:phone',
            // 'type_contact' => 'required',
            // 'observations' => 'required',
            // 'consecutive_code' => 'required',
            'register_event_id' => 'required',
            'continue' => 'required',
            'registers_id' => 'required',
        ]);

        DB::beginTransaction();
        $msg = 'Se ha registrado la información';
        try {

            // Se almacena la información del seguimiento y la documentación
            $tracking = new Tracking();
            $tracking->fill($request->all());
            $tracking->created_users_id = Auth::id();
            $tracking->stage_tasks_id = RegisterEvent::find($request->register_event_id)->stage_tasks_id;
            $tracking->save();

            $document_valid = $request->valid ?? [];

            foreach ($request->client_documents_id as $key => $value) {
                $document_management = new DocumentManagement();
                $document_management->trackings_id = $tracking->id;
                $document_management->client_documents_id = $value;
                $document_management->valid = in_array($value, $document_valid) ? 'S' : 'N';
                $document_management->outcome = $request->outcome[$key];
                $document_management->created_users_id = Auth::id();
                $document_management->save();
            }

            //Cerrar el evento del registro
            $register = Register::find($request->registers_id);

            $generalMethods = new GeneralMethods($register);
            $register_event = RegisterEvent::find($request->register_event_id);

            $generalMethods->closeEvent($register_event->id);

            // Buscamos las cantidad de eventos de las tareas de la etapa
            $totalTask = RegisterTask::select('register_tasks.*')
            ->where('registers_id', $register->id)
            ->AddClientTask()
            ->whereIn('stage_tasks_id', [10, 11, 12, 13]);

            $totalTaskIDs = $totalTask->pluck('id')->toArray();
            $totalEvents = RegisterEvent::whereIn('register_tasks_id',$totalTaskIDs)->whereNull('finished_at')->count();

            if ($totalEvents == 0) { //Si todos eventos de subsanación fueron cerrados

                $tracking = Tracking::where('registers_id', $register->id)->whereIn('stage_tasks_id', [10, 11, 12, 13])
                ->orderBy('id','ASC')->pluck('id','stage_tasks_id')->toArray();

                $document_management = DocumentManagement::whereIn('trackings_id',$tracking)->where('valid','N');

                if ($document_management->count() == 0) { // Si todos los documentos de todas las tareas cumplen con la verificación
                    // Se cierran todas las tareas de subsanación
                    foreach ($totalTaskIDs as $id) {
                        $generalMethods->closeTask($id);
                    }
                    // Se crea la tarea y evento de la proxima etapa La gestión debe quedar en PAR
                    $save = $generalMethods->createTasksAndEvents(['ca_task_1'], true, 1);
                    if($save==false){
                        DB::rollBack();
                        Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                            'Problema en subsanación al avanzar de etapa',
                            'No hay analistas para gestionar la solicitud '.$register->code. ' en la proxima etapa'
                        ));
                        Alert::warning(__('Seguimiento'), __('Ha surgido un error, no hay analistas para gestionar la solicitud en la proxima etapa'))->persistent('Close');
                        return redirect()->back();

                    }

                }else{ // Se verifica si se puede crear otro evento de subsanación por cada tarea que fallo

                    $tracking1 = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 10]])->count();
                    $tracking2 = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 11]])->count();
                    $tracking3 = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 12]])->count();
                    $tracking4 = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 13]])->count();
                    if ($tracking1 >= $register->tasks_sub || $tracking2 >= $register->tasks_sub ||
                        $tracking3 >= $register->tasks_sub || $tracking4 >= $register->tasks_sub
                    ) {

                        // Si en esta misma etapa ya se escalo al cliente se debe suspender la solicitud
                        $register_management = RegisterManagement::where([['registers_id', $register->id], ['stages_id', 6]])->first();

                        $user = User::find($register->requesting_users_id);

                        if (isset($register_management)) { // si el registro ya ha sido escalado en esta misma etapa

                            //Cambiar estado de la solicitud a Suspendido
                            $register = Register::find($register->id);
                            $register->states_id = 7; // 7: Suspendido
                            $register->save();

                            // Se cierran todas las tareas de subsanación
                            foreach ($totalTaskIDs as $id) {
                                $generalMethods->closeTask($id);
                            }

                            //Crear nuevo evento
                            $register_event_new = new RegisterEvent();
                            $register_event_new->registers_id = $register_event->registers_id;
                            $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                            $register_event_new->states_id = $register->states_id; //[states_id]
                            $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                            $register_event_new->management = 'PAR';
                            $register_event_new->description = 'Se suspende la solicitud, los documentos no cumplen con las verificaciones, ya se había escalado anteriormente';
                            $register_event_new->created_users_id = Auth::id();
                            $register_event_new->finished_at = now();
                            $register_event_new->save();

                            $msg = $register_event_new->description;

                            // Se notifica al cliente y al proveedor
                            Mail::to($user->email)
                            ->bcc($register->email_contact)->send(new GeneralNotification(
                                'Solicitud suspendida',
                                "La solicitud $register->code ha sido suspendida en la etapa de subsanación",
                                //Enviar estado documental y los seguimientos
                                // 'mails.documentation.notification',
                                // [$register->id]
                            ));

                        } else {// Se escala al cliente

                            // dd('Se escala');
                            // Se cambia el estado
                            $register = Register::find($register_event->registers_id);
                            $register->states_id = 3; // 3: Escalado al cliente
                            $register->save();

                            // Se crea el evento
                            $register_event_new = new RegisterEvent();
                            $register_event_new->registers_id = $register_event->registers_id;
                            $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                            $register_event_new->states_id = $register->states_id; //[states_id]
                            $register_event_new->register_tasks_id = $register_event->register_tasks_id;;
                            $register_event_new->management = 'CLI';
                            $register_event_new->description = 'Escalado al cliente, etapa subsanación';
                            $register_event_new->created_users_id = Auth::id();
                            $register_event_new->save();

                            $msg = $register_event_new->description;

                            // Se notifica al cliente
                            Mail::to($user->email)->send(new GeneralNotification(
                                'Solicitud escalada al cliente',
                                "La solicitud $register->code ha sido escalada en la etapa de subsanación",
                                // 'mails.documentation.notification',
                                // [$register->id]
                            ));


                        }
                    }else{ // se crean los eventos de subsanación correspondientes a los que fallaron

                        $tasks = $document_management->select('trackings.*')->addTracking()->pluck('stage_tasks_id','stage_tasks_id')->toArray();
                        // dd('Se crean eventos',$tasks);
                        foreach ($tasks as $task) {
                            $old_event = RegisterEvent::where([['registers_id',$register->id],['stage_tasks_id',$task]])->first();
                            //Crear el evento del registro
                            $register_event = new RegisterEvent();
                            $register_event->registers_id = $register->id;
                            $register_event->stage_tasks_id = $task;
                            $register_event->states_id = $register->states_id; //[states_id]
                            $register_event->register_tasks_id = $old_event->register_tasks_id;
                            $register_event->management = 'PRO';
                            $tracking = Tracking::where([['registers_id',$old_event->registers_id],['stage_tasks_id', $old_event->stage_tasks_id]])->get();
                            $stage_tasks = StageTask::find($old_event->stage_tasks_id);
                            $register_event->description = "$stage_tasks->label, Seguimiento ".(count($tracking)+1);
                            $register_event->created_users_id = Auth::id();
                            $register_event->save();
                        }

                        // Se notifica al proveedor
                        Mail::to($register->email_contact)->send(new GeneralNotification(
                            'Nuevo seguimiento',
                            "Se ha creado un nuevo seguimiento para la solicitud $register->code.",
                        ));
                    }
                }
            }
            DB::commit();
            Alert::success(__('Seguimiento'), __($msg))->persistent('Close');

            if ($request->continue == 'N') {
                return redirect()->route('analyst.tasks.index');
            } else {
                $tasks = RegisterTask::find($request->next_task);
                return redirect()->route(config('tasks-routes.'.$tasks->task->name), ['register_task'=>$request->next_task,'continue'=>$request->continue]);
            }

        } catch (\Exception $exception) {
            // dd($exception);
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Problema en la etapa de subsanación',
                "Solicitud ".$register->code. ", error $exception"
            ));
            Alert::warning(__('Seguimiento'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
            return redirect()->back();
        }

    }

    public function getDocuments(RegisterTask $register_task, $task_verification, $continue)
    {

        $register = $register_task->register;
        $register_event = RegisterEvent::where([['register_tasks_id', $register_task->id],['finished_at',null]])->orderBy('created_at', 'DESC')->first();

        $client_documents = ClientDocument::select('client_documents.*')
            ->join('verifications', 'client_documents.id', 'verifications.client_documents_id')
            ->join('register_tasks', 'register_tasks.id', 'verifications.register_tasks_id')
            ->join('client_tasks', 'client_tasks.id', 'register_tasks.client_tasks_id')
            ->where([
                ['verifications.registers_id', $register_task->registers_id],
                ['verifications.satisfy', 'N'],
                ['client_tasks.stage_tasks_id', $task_verification],
            ])->get();

        $registers_available = Register::select('id')->where([
            ['identification_number', $register->identification_number],
            ['id', '<>', $register->id]
        ])->get();

        $client_task = ClientTask::find($register_task->client_tasks_id);

        $all_registers= RegisterTask::select('register_tasks.*')
        ->join('client_tasks', 'client_tasks.id', 'register_tasks.client_tasks_id')
        ->join('register_events', 'register_events.register_tasks_id', 'register_tasks.id')
        ->whereIn('register_tasks.registers_id', $registers_available)
        ->where([
            ['status', 'A'],
            ['client_tasks.stage_tasks_id', $client_task->stage_tasks_id],
            ['register_events.finished_at', null],
            ['register_events.management', 'PAR'],
        ]);

        $my_registers = $all_registers->where('analyst_users_id', Auth::id())->get();
        $other_registers = $all_registers->where('analyst_users_id','<>' ,Auth::id())->get();

        $data = null;
        if ($continue == 'S') {
            $data = Tracking::where([
                ['created_users_id', Auth::id()],
                ['stage_tasks_id', $client_task->stage_tasks_id],
            ])->orderBy('created_at', 'DESC')->first();
        }

        $trackings = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', $client_task->stage_tasks_id]])->orderBy('created_at', 'desc')->get();

        return [$register,$register_event, $client_documents, $my_registers, $other_registers,$data,$trackings];

    }


    public function executeProviderRetrievalTask($id)
    {
        Artisan::call("RetrievalTracking:update $id");
        Alert::success(__('Subsanación'), __('Se ha ejecutado la consulta correctamente'));
        return redirect()->back();
    }
}
