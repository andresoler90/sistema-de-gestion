<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;
use App\Models\Tracking;
use App\Models\RegisterTask;
use App\Models\RegisterEvent;
use App\Models\RegisterManagement;
use App\Models\DocumentManagement;
use App\Models\StageTask;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;



class ManagementScaledController extends Controller
{
    public function index(){


        $scaled_registers = Register::select('registers.*','register_managements.decision as decision','register_managements.created_at as scaled_created_at')
        ->join('register_managements','registers.id','register_managements.registers_id')
        ->where('register_managements.status', 'A')
        ->paginate(10);

        return view('coordinator.scaled.index', compact('scaled_registers'));
    }

    public function showDocumentManagement(Register $register)
    {
        $register_event = RegisterEvent::where('registers_id',$register->id)
        ->orderBy('created_at','DESC')->first();

        $register_task = RegisterTask::find($register_event->register_tasks_id);
        $trackings = Tracking::where([['registers_id',$register->id],['stage_tasks_id',4]])->orderBy('created_at', 'desc')->get();
        $management = RegisterManagement::where([
            ['registers_id',$register->id],
            ['stages_id',4] // Etapa Gestión documental
        ])->first();

        return view('coordinator.scaled.show', compact('register_task','register','trackings','management'));
    }

    public function showRetrieval(Register $register)
    {

        $register_event = RegisterEvent::where('registers_id',$register->id)
        ->orderBy('created_at','DESC')->first();

        $register_task = RegisterTask::find($register_event->register_tasks_id);


        $trackings = Tracking::where('registers_id',$register->id)
                    ->whereIn('stage_tasks_id',[10,11,12,13])
                    ->orderBy('stage_tasks_id')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $management = RegisterManagement::where([
            ['registers_id',$register->id],
            ['stages_id',6]// Etapa Subsanación
        ])->first();

        return view('coordinator.scaled.show', compact('register_task','register','trackings','management'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'answer' => 'required',
            'reason' => 'required_if:answer,deny',
            'register_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $register = Register::find($request->register_id);
            // Se cierra la gestión del escalamiento de la solicitud

            if($register->stage->id == 4){

                $register_management = RegisterManagement::where([
                    ['registers_id',$request->register_id],
                    ['stages_id',4], // Etapa Gestión documental
                ])->first();
            }else{
                $register_management = RegisterManagement::where([
                    ['registers_id',$request->register_id],
                    ['stages_id',6], // Etapa Subsanación
                ])->first();
            }

            $register_management->status = 'C';
            $register_management->save();

            //Cerrar ultimo evento
            $register_event = RegisterEvent::where([
                ['registers_id',$request->register_id],
                ['finished_at',null],
                ['management','PAR'],
            ])->orderBy('created_at','DESC')->first();

            $register_event->finished_at = now();
            $register_event->save();

            if($request->answer == 'deny'){ // Se Cancela la solicitud

                //Cambiar estado de la solicitud a Cancelado
                $register = Register::find($request->register_id);
                $register->states_id = 4;
                $register->save();

                //Crear nuevo evento
                $register_event_new = new RegisterEvent();
                $register_event_new->registers_id = $register_event->registers_id;
                $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                $register_event_new->states_id = $register->states_id; //[states_id]
                $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                $register_event_new->management = 'PAR';
                $register_event_new->description = 'Se cancela la solicitud, '.$request->reason;
                $register_event_new->created_users_id = Auth::id();
                $register_event_new->finished_at = now();
                $register_event_new->save();

                // Se cierran todas las tareas relacionadas [Para gestión documental solo es una pero para subsanación pueden ser varias]
                $register_tasks = RegisterTask::where([['registers_id',$request->register_id],['status','A']])->get();
                foreach ($register_tasks as $register_task) {

                    $register_task_new = RegisterTask::find($register_task->id);
                    $register_task_new->end_date = now();
                    $register_task_new->status = 'C';
                    $register_task_new->save();
                }


            }else{ // Se crea un nuevo seguimiento

                // Se añade un seguimiento más al máximo de seguimientos en la etapa gestión documental o subsanación
                $register = Register::find($request->register_id);

                if($register->stage->id == 4){
                    $register->tasks_gd = $register->tasks_gd + 1;
                }else{
                    $register->tasks_sub = $register->tasks_sub + 1;
                }

                // Se cambia el estado de la solicitud de [Escalado al cliente] a [Abierto]
                $register->states_id = 1;
                $register->save();

                //Crear nuevo evento
                $register_event_new = new RegisterEvent();
                $register_event_new->registers_id = $register_event->registers_id;
                $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                $register_event_new->states_id = $register->states_id; //[states_id]
                $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                $register_event_new->management = 'PAR';
                $register_event_new->description = 'El coordinador acepto un nuevo seguimiento';
                $register_event_new->created_users_id = Auth::id();
                $register_event_new->finished_at = now();
                $register_event_new->save();

                //Crear nuevo evento
                if($register->stage->id == 4){

                    $tracking = Tracking::where([['registers_id',$register->id],['stage_tasks_id',4]])->get();

                    $register_event_new = new RegisterEvent();
                    $register_event_new->registers_id = $register_event->registers_id;
                    $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                    $register_event_new->states_id = $register->states_id; //[states_id]
                    $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                    $register_event_new->management = 'PRO';
                    $register_event_new->description = 'Matriz Documental, Seguimiento '.(count($tracking));
                    $register_event_new->created_users_id = Auth::id();
                    $register_event_new->save();
                }else{
                    // Crear un nuevo evento para cada una de las tareas de subsanación que fallaron
                    $tracking = Tracking::where('registers_id', $register->id)->whereIn('stage_tasks_id', [10, 11, 12, 13])
                    ->orderBy('id','ASC')->pluck('id','stage_tasks_id')->toArray();

                    $document_management = DocumentManagement::whereIn('trackings_id',$tracking)->where('valid','N');

                    $tasks = $document_management->select('trackings.*')->addTracking()->pluck('stage_tasks_id','stage_tasks_id')->toArray();
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
                }


            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Alert::warning(__('Gestión'), __('Ha surgido un error, por favor intente nuevamente'));
            return redirect()->back();

        }

        Alert::success(__('Gestión'), __('Se ha registrado la información'));
        return redirect()->route('management.scaled.index');
    }
}
