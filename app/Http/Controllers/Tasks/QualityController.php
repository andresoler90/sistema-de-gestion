<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\GeneralMethods;
use App\Mail\GeneralNotification;
use App\Models\ClientDocument;
use App\Models\DocumentManagement;
use App\Models\QualityDocumentReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\RegisterTask;
use App\Models\RegisterEvent;
use App\Models\Register;
use App\Models\Tracking;
use App\Models\Verification;
use Illuminate\Support\Facades\DB;
use App\Models\ClientTask;



class QualityController extends Controller
{
    public function detail(RegisterTask $register_task)
    {
        $register = $register_task->register;
        $documents = Verification::select('client_documents_id')->where('registers_id',$register->id)->get();
        $client_documents = ClientDocument::whereIn('id',$documents)->get();

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

        return view('analyst.quality.detail', compact('register_task','register','client_documents','my_registers','other_registers'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'register_id' => 'required',
            'register_task' => 'required',
            // 'review' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $document_satisfy = $request->satisfy ?? [];
            $document_fingering_review = $request->fingering_review ?? [];

            foreach ($request->client_documents_id as $key => $value) {
                $quality_document_review = new QualityDocumentReview();
                $quality_document_review->registers_id = $request->register_id;
                $quality_document_review->register_tasks_id = $request->register_task;
                $quality_document_review->client_documents_id = $value;
                $quality_document_review->satisfy = in_array($value, $document_satisfy) ? 'S' : 'N';
                // $quality_document_review->fingering_review = in_array($value, $document_fingering_review) ? 'S' : ($request->review == 'I'? 'N' : null);
                $quality_document_review->fingering_review = in_array($value, $document_fingering_review) ? 'S' : 'N';
                $quality_document_review->comments = $request->comments[$key];
                $quality_document_review->created_users_id = Auth::id();
                $quality_document_review->save();
            }
            $register = Register::find($request->register_id);
            $generalMethods = new GeneralMethods($register);
            $generalMethods->closeTask($request->register_task);//Cerramos tarea
            $registerEvent = RegisterTask::find($request->register_task)->events->first();//consultamos el evento de la tarea
            $generalMethods->closeEvent($registerEvent->id);

            $quality_documents = QualityDocumentReview::AddClientDocuments()->AddStageTasks()->where('registers_id',$register->id)->where('satisfy','N')->where('register_tasks_id', $request->register_task);
            // dd($quality_documents->get());

            if ($quality_documents->count()) { // Si existen errores en la revisión de calidad [HU 1.8.6 Revisión no exitosa, creación de tarea - Subsanación ]
                $stage_tasks_ids = $quality_documents->pluck('stage_tasks.id')->toArray();
                $events = $register->events()->whereIn('stage_tasks_id',$stage_tasks_ids);
                $stage_tasks = [ // tareas padre de etapa de calidad
                    've_task_1' => 'ca_task_2',
                    've_task_2' => 'ca_task_3',
                    've_task_3' => 'ca_task_4',
                    've_task_4' => 'ca_task_5',
                ];
                foreach ($events->cursor() as $event) {
                    if (array_key_exists($event->stage_task->name, $stage_tasks)) {
                        $name = $stage_tasks[$event->stage_task->name];
                        $stage_tasks_name[$name] = $name;
                        $usersByTask[$name] = $event->register_task->analyst_users_id;
                    }
                }
                $generalMethods->createTasksAndEvents($stage_tasks_name ?? [], false, 1, $management = 'PAR',$usersByTask ?? [],'ALT');

            } else { // Si no hay errores se cierra la solicitud satisfactoriamente [HU 1.8.7 Revisión exitosa - Calidad ]
                $register->states_id = 2;
                $register->save();


                //Se crea el evento
                $event = new RegisterEvent();
                $event->registers_id = $register->id;
                $event->stage_tasks_id = 15; // Calidad
                $event->states_id = $register->states_id; //[states_id]
                $event->register_tasks_id = $request->register_task;
                $event->management = 'PAR';
                $event->description = 'Se cierra la solicitud satisfactoriamente';
                $event->created_users_id = Auth::id();
                $event->finished_at = now();
                $event->save();

                // envio de correo
                Mail::to($register->email_contact)->bcc($register->creatorUser->email)->send(new GeneralNotification(
                    'Solicitud Cerrada',
                    "Se ha culminado el proceso del registro: $register->code Proveedor: $register->identification_number "
                ));
            }
            DB::commit();
            Alert::success(__('Calidad'), __('Se ha registrado la información'));
            return redirect()->route('analyst.tasks.index');

        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::warning(__('Calidad'), __('Ha surgido un error, por favor intente nuevamente'));
            return redirect()->back();
        }

    }

    public function retrievalBasic(RegisterTask $register_task, $continue = 'N')
    {
        $category = 5;
        $method = $this->getDocuments($register_task,$category,$continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];

        return view('analyst.quality.retrieval_detail', compact('register_task','register','register_event','client_documents','my_registers','other_registers','data','trackings'));
    }

    public function retrievalExperience(RegisterTask $register_task, $continue = 'N')
    {
        $category = 6;
        $method = $this->getDocuments($register_task,$category,$continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];

        return view('analyst.quality.retrieval_detail', compact('register_task','register','register_event','client_documents','my_registers','other_registers','data','trackings'));
    }

    public function retrievalFinancial(RegisterTask $register_task, $continue = 'N')
    {
        $category = 7;
        $method = $this->getDocuments($register_task,$category,$continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];

        return view('analyst.quality.retrieval_detail', compact('register_task','register','register_event','client_documents','my_registers','other_registers','data','trackings'));
    }

    public function retrievalDocument(RegisterTask $register_task, $continue = 'N')
    {
        $category = 8;
        $method = $this->getDocuments($register_task,$category,$continue);
        $register = $method[0];
        $register_event = $method[1];
        $client_documents = $method[2];
        $my_registers = $method[3];
        $other_registers = $method[4];
        $data = $method[5];
        $trackings = $method[6];

        return view('analyst.quality.retrieval_detail', compact('register_task','register','register_event','client_documents','my_registers','other_registers','data','trackings'));
    }

    public function retrievalStore(Request $request)
    {
        $validated = $request->validate([
            'registers_id' => 'required',
            'register_task' => 'required',
            'client_documents_id' => 'required',
            'register_event' => 'required',
            // 'date' => 'required',
            // 'contact_name' => 'required',
            // 'phone' => 'required_without:email',
            // 'email' => 'required_without:phone',
            // 'type_contact' => 'required',
            // 'observations' => 'required',
            // 'consecutive_code' => 'required',
            'stage_tasks_id' => 'required',
            'continue' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $tracking = new Tracking();
            $tracking->fill($request->all());
            $tracking->created_users_id = Auth::id();
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

            $register = Register::find($request->registers_id);
            $generalMethods = new GeneralMethods($register);
            $generalMethods->closeEvent($request->register_event); // Cerramos el evento

            if(count($document_valid)== count($request->client_documents_id )){ // Si esta tarea de calidad se cerro correctamente

                $generalMethods->closeTask($request->register_task);//Cerramos tarea
                $tasks = $register->tasks()->where('status','A')->count();

                if($tasks == 0){ // Si ya todas las tareas de calidad están cerradas

                    $events = $register->events()->select('register_tasks.*')->where('stage_tasks_id',15)->orderBy('register_tasks.created_at','DESC')->first();
                    $generalMethods->createTasksAndEvents(['ca_task_1'], false, 1,'PAR',['ca_task_1'=> $events->analyst_users_id],'ALT');
                }
            }else{
                // Si los documentos siguen presentando fallas, no cierra la tarea y se crea otro evento
                $event = RegisterEvent::where('register_tasks_id', $request->register_task)->orderBy('created_at','DESC')->get();
                $count = count($event) + 1;
                $array = explode(' ', $event[0]->description);

                // Esto es para la descripción con contador
                if(is_numeric(end($array))){
                    $last = array_key_last($array);
                    unset($array[$last]);
                    $description = implode(' ',$array)." $count";
                }else{
                    $description = $event[0]->description." $count";
                }

                $new_event = new RegisterEvent();
                $new_event->registers_id = $register->id;
                $new_event->stage_tasks_id = $request->stage_tasks_id;
                $new_event->states_id = $register->states_id; //[states_id]
                $new_event->register_tasks_id = $request->register_task;
                $new_event->management = 'PAR';
                $new_event->description = $description;
                $new_event->created_users_id = Auth::id();
                $new_event->save();
            }
            DB::commit();
            Alert::success(__('Calidad - Subsanación'), __('Se ha registrado la información'));

            if ($request->continue == 'N') {
                return redirect()->route('analyst.tasks.index');
            } else {
                $tasks = RegisterTask::find($request->next_task);
                return redirect()->route(config('tasks-routes.'.$tasks->task->name), ['register_task'=>$request->next_task,'continue'=>$request->continue]);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            Alert::warning(__('Calidad - Subsanación'), __('Ha surgido un error, por favor intente nuevamente'));
            return redirect()->back();
        }

    }

    public function getDocuments(RegisterTask $register_task, $category, $continue)
    {


        $register = $register_task->register;
        $register_event = RegisterEvent::where('register_tasks_id', $register_task->id)->orderBy('created_at','DESC')->first();
        $task = QualityDocumentReview::select('register_tasks_id')->where('registers_id', $register->id)->orderBy('created_at','DESC')->first();

        $quality_document_review = QualityDocumentReview::select('client_documents_id')
        ->where([
            ['registers_id',$register->id],
            ['satisfy','N'],
            ['register_tasks_id', $task->register_tasks_id],
        ])->get();

        $client_documents = ClientDocument::where([
            ['clients_id', $register->client->id],
            ['stage_tasks_id', $category],
        ])
        ->whereIn('id',$quality_document_review)
        ->get();

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


        return [$register, $register_event, $client_documents, $my_registers, $other_registers,$data,$trackings];
    }

}
