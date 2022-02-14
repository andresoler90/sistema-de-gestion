<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\GeneralMethods;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Models\ClientDocument;
use App\Models\DocumentManagement;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterManagement;
use App\Models\RegisterTask;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Artisan;
use App\Models\ClientTask;
use Illuminate\Support\Facades\DB;
use App\Mail\GeneralNotification;
use App\Models\QualityDocumentReview;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Facades\Mail;



class DocumentManagementController extends Controller
{

    public function detail(RegisterTask $register_task, $continue = 'N')
    {
        $register = $register_task->register;
        $country_provider = $register->countries_id;
        $country_client = $register->client_country_id;
        $provider_type = ($country_provider == $country_client) ? 'N' : 'I';

        $tracking = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 4]])->orderBy('created_at', 'desc')->first();

        if (isset($tracking)) {

            $client_documents_1 = ClientDocument::join('document_managements', 'document_managements.client_documents_id', 'client_documents.id')
                ->where([
                    ['clients_id', $register->client->id],
                    ['provider_type', $provider_type],
                    ['register_type', $register->register_type],
                    ['document_managements.trackings_id', $tracking->id]
                ]);

            $id = $client_documents_1->get('client_documents.id');

            $client_documents_2 = ClientDocument::where([
                ['clients_id', $register->client->id],
                ['provider_type', $provider_type],
                ['register_type', $register->register_type],
            ])
                ->whereNotIn('id', $id);

            $client_documents_1 = collect($client_documents_1->get());
            $client_documents_2 = collect($client_documents_2->get());
            $client_documents = $client_documents_1->merge($client_documents_2)->sortBy('documents_id');
        } else {
            $client_documents = ClientDocument::where([
                ['clients_id', $register->client->id],
                ['provider_type', $provider_type],
                ['register_type', $register->register_type],
            ])
                ->get();
        }

        $trackings = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 4]])->orderBy('created_at', 'desc')->get();

        $registers_available = Register::select('id')->where([
            ['identification_number', $register->identification_number],
            ['id', '<>', $register->id]
        ])->get();

        $client_task = ClientTask::find($register_task->client_tasks_id);

        $my_registers = RegisterTask::select('register_tasks.*')
            ->join('client_tasks', 'client_tasks.id', 'register_tasks.client_tasks_id')
            ->join('register_events', 'register_events.register_tasks_id', 'register_tasks.id')
            ->whereIn('register_tasks.registers_id', $registers_available)
            ->where([
                ['analyst_users_id', Auth::id()],
                ['status', 'A'],
                ['client_tasks.stage_tasks_id', $client_task->stage_tasks_id],
                ['register_events.finished_at', null],
                ['register_events.management', 'PAR'],
            ])
            ->get();

        $other_registers = RegisterTask::select('register_tasks.*')
            ->join('client_tasks', 'client_tasks.id', 'register_tasks.client_tasks_id')
            ->join('register_events', 'register_events.register_tasks_id', 'register_tasks.id')
            ->whereIn('register_tasks.registers_id', $registers_available)
            ->where([
                ['analyst_users_id', '<>', Auth::id()],
                ['status', 'A'],
                ['client_tasks.stage_tasks_id', $client_task->stage_tasks_id],
                ['register_events.finished_at', null],
                ['register_events.management', 'PAR'],
            ])
            ->get();


        $data = null;
        if ($continue == 'S') {
            $data = Tracking::where([
                ['created_users_id', Auth::id()],
                ['stage_tasks_id', $client_task->stage_tasks_id],
            ])->orderBy('created_at', 'DESC')->first();
        }
        return view('analyst.documentManagement.detail', compact('register_task', 'register', 'client_documents', 'trackings', 'my_registers', 'other_registers', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
            'registers_id' => 'required',
            // 'stage_tasks_id' => 'required',
            'continue' => 'required',
        ]);

        $msg = 'Se ha registrado la información';
        DB::beginTransaction();
        try {

            $tracking = new Tracking();
            $tracking->fill($request->all());
            $tracking->created_users_id = Auth::id();
            $tracking->save();

            $document_valid = isset($request->valid) ? $request->valid : [];
            foreach ($request->client_documents_id as $key => $value) {
                $document_management = new DocumentManagement();
                $document_management->trackings_id = $tracking->id;
                $document_management->client_documents_id = $value;
                $document_management->valid = in_array($value, $document_valid) ? 'S' : 'N';
                $document_management->outcome = $request->outcome[$key];
                $document_management->created_users_id = Auth::id();
                $document_management->save();
            }

            $register = RegisterTask::find($request->register_task)->register;

            //Cerrar el evento del registro
            $register_event = RegisterEvent::where([
                ['registers_id', $register->id],
                ['stage_tasks_id', 4],
                ['register_tasks_id', $request->register_task],
            ])
            ->orderBy('created_at','DESC')
            ->first();

            $register_event->finished_at = now();
            $register_event->save();

            // Determinar si se debe crear un nuevo seguimiento, pasar a la proxima etapa o si se debe escalar al cliente

            //Último seguimiento
            $last_tracking = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 4]])
                ->orderBy('created_at', 'DESC')->first();

            //Documentos obligatorios cargados en el último seguimiento
            $document_management = DocumentManagement::join('client_documents', 'client_documents.id', '=', 'document_managements.client_documents_id')
                ->where([
                    ['client_documents.document_type', 'OB'],
                    ['trackings_id', $last_tracking->id],
                    ['valid', 'S']
                ])
                ->get();

            // Determinar si el proveedor es N: Nacional o I: internacional
            $country_provider = $register->countries_id;
            $country_client = $register->client_country_id;
            $provider_type = ($country_provider == $country_client) ? 'N' : 'I';

            // Documentos que debería tener cargados el proveedor para pasar de etapa
            $client_documents = ClientDocument::where([
                ['clients_id', $register->client->id],
                ['provider_type', $provider_type],
                ['register_type', $register->register_type],
                ['document_type', 'OB'],
            ])->get();

            if (count($document_management) == count($client_documents)) {

                //---Pasar a la siguiente etapa---

                // Primero se cierra la tarea
                $register_task = RegisterTask::find($register_event->register_tasks_id);
                $register_task->end_date = now();
                $register_task->status = 'C';
                $register_task->save();

                $stage_tasks = $this->verificationTasksNext($register);
                $generalMethods = new GeneralMethods($register);
                // Se crea la tarea y evento de la proxima etapa
                $save = $generalMethods->createTasksAndEvents($stage_tasks);
                if($save==false){
                    DB::rollBack();
                    Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                        'Problema en gestión documental al avanzar de etapa',
                        'No hay analistas para gestionar la solicitud '.$register->code. ' en la proxima etapa'
                    ));
                    Alert::warning(__('Seguimiento'), __('Ha surgido un error, no hay analistas para gestionar la solicitud en la proxima etapa'))->persistent('Close');
                    return redirect()->back();

                }
            } else {

                $tracking = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 4]])->get();
                if (count($tracking) >= $register->tasks_gd) {

                    // Si en esta misma etapa ya se escalo al cliente se debe cancelar la solicitud
                    $register_management = RegisterManagement::where([['registers_id', $register->id], ['stages_id', 4]])->first();

                    $user = User::find($register->requesting_users_id);

                    if (isset($register_management)) { // Se Suspende la solicitud

                        //Cambiar estado de la solicitud a Suspendido
                        $register = Register::find($register->id);
                        $register->states_id = 7; // 7: Suspendido
                        $register->save();

                        //Crear nuevo evento
                        $register_event_new = new RegisterEvent();
                        $register_event_new->registers_id = $register_event->registers_id;
                        $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                        $register_event_new->states_id = $register->states_id; //[states_id]
                        $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                        $register_event_new->management = 'PAR';
                        $register_event_new->description = 'Se suspende la solicitud, no se cargaron los documentos solicitados, la solicitud se había escalado anteriormente';
                        $register_event_new->created_users_id = Auth::id();
                        $register_event_new->finished_at = now();
                        $register_event_new->save();

                        $msg = $register_event_new->description;

                        //Cerramos la tarea
                        $register_tasks = RegisterTask::find($register_event_new->register_tasks_id);
                        $register_tasks->end_date = now();
                        $register_tasks->status = 'C';
                        $register_tasks->save();

                        // Se notifica al cliente y al proveedor
                        Mail::to($user->email)
                        ->bcc($register->email_contact)->send(new GeneralNotification(
                            'Solicitud suspendida',
                            "La solicitud $register->code ha sido suspendida en la etapa de gestión documental",
                            // 'mails.documentation.request_canceled',
                            // [$register->id]
                        ));

                    } else { // Escalar al cliente
                        // Si no se ha escalado al cliente en esta misma etapa entonces se puede escalar

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
                        $register_event_new->description = 'Escalado al cliente, etapa gestión documental';
                        $register_event_new->created_users_id = Auth::id();
                        $register_event_new->save();

                        $msg = $register_event_new->description;

                        // Se notifica al cliente
                        Mail::to($user->email)->send(new GeneralNotification(
                            'Solicitud escalada al cliente',
                            "La solicitud $register->code ha sido escalada en la etapa de gestión documental",
                            'mails.documentation.notification',
                            [$register->id]
                        ));
                    }
                } else {
                    //Crear el evento del registro
                    $register_event = new RegisterEvent();
                    $register_event->registers_id = $register->id;
                    $register_event->stage_tasks_id = 4;
                    $register_event->states_id = $register->states_id; //[states_id]
                    $register_event->register_tasks_id = $request->register_task;
                    $register_event->management = 'PRO';
                    $register_event->description = 'Matriz Documental, Seguimiento ' . (count($tracking));
                    $register_event->created_users_id = Auth::id();
                    $register_event->save();
                }
            }

            DB::commit();
            Alert::success(__('Seguimiento'), __($msg))->persistent('Close');

            if ($request->continue == 'N') {
                return redirect()->route('analyst.tasks.index');
            } else {
                return redirect()->route('task.document.detail', ['register_task'=>$request->next_task,'continue'=>$request->continue]);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la etapa gestión documental',
                "REQUEST: ".json_encode($request->all())."\nEXCEPTION: ".$exception->getMessage()
            ));
            Alert::warning(__('Seguimiento'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
            return redirect()->back();
        }
    }

    public function getTracking(Request $request)
    {

        if ($request->ajax()) {
            $tracking = Tracking::find($request->tracking_id);
            if (isset($tracking)) {
                $documents = DocumentManagement::where('trackings_id', $tracking->id)->with('clientDocument')->get();
                return response()->json(['status' => 200, 'tracking' => $tracking, 'documents' => $documents]);
            } else {
                return response()->json(['status' => 500, 'msg' => __('Ha surgido un error, por favor intente nuevamente'), 'title' => __('Seguimiento')]);
            }
        }
    }

    public function getTrackingVerification(Request $request)
    {
        if ($request->ajax()) {
            $documents = Verification::select('verifications.*', 'client_documents.*','documents.*')
            ->where('verifications.register_tasks_id',$request->register_tasks_id)
            ->addClientDocument()
            ->orderBy('verifications.created_at', 'desc')
            ->get();

            if (count($documents)) {
                return response()->json(['status' => 200,'documents' => $documents]);
            } else {
                return response()->json(['status' => 500, 'msg' => __('Ha surgido un error, por favor intente nuevamente'), 'title' => __('Seguimiento')]);
            }
        }
    }

    public function getTrackingQuality(Request $request)
    {
        if ($request->ajax()) {
            $documents = QualityDocumentReview::select('quality_document_review.*', 'client_documents.*','documents.*')
            ->where('quality_document_review.register_tasks_id',$request->register_tasks_id)
            ->addClientDocument()
            ->orderBy('quality_document_review.created_at', 'desc')
            ->get();

            if (count($documents)) {
                return response()->json(['status' => 200,'documents' => $documents]);
            } else {
                return response()->json(['status' => 500, 'msg' => __('Ha surgido un error, por favor intente nuevamente'), 'title' => __('Seguimiento')]);
            }
        }
    }

    public function verificationTasksNext($register)
    {
        $process = new ProcessFlow($register);
        $total = $process->totalDocuments();
        $r = isset($total->{5}) ? array_sum($total->{5}) : 0;
        if ($r == 0)
            $stage_tasks = ['ca_task_1'];
        else
            $stage_tasks = array_keys($total->{5});

        return $stage_tasks;
    }

    public function executeProviderTask($id)
    {
        Artisan::call("DocumentationTracking:update $id");
        Alert::success(__('Gestión documental'), __('Se ha ejecutado la consulta correctamente'));
        return redirect()->back();
    }
}
