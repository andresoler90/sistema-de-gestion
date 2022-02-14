<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use App\Models\Tracking;
use App\Models\DocumentManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\GeneralNotification;
use App\Models\ClientTask;
use Illuminate\Support\Facades\Mail;


class SuspendedController extends Controller
{
    public function index(){

        $lasted = DB::raw('(select register_events.id, Max(register_events.id) as max_event from register_events group by register_events.registers_id) max_table');

        $suspended = Register::select('registers.*','register_events.created_at as date_suspended')
        ->addEvent()
        ->join($lasted, function ($join) {
            $join->on('max_table.max_event', '=', 'register_events.id');
        })
        ->where([
            ['registers.states_id',7]
        ])
        ->paginate(10);

        return view('coordinator.suspended.index', compact('suspended'));

    }

    public function open(Register $registers_id){

        DB::beginTransaction();
        try {

            // Se abre la solicitud
            $registers_id->states_id = 1; // Abierto : 1
            $registers_id->save();

            //Saber si esta en gestión documental o en subsanación

            if($registers_id->stage->id == 4){ // Gestión documental

                // Se busca la ultima tarea
                $register_task_old = RegisterTask::where('registers_id',$registers_id->id)
                ->orderBy('id','DESC')->first();

                // Se crea una nueva tarea
                $register_task_new = new RegisterTask();
                $register_task_new->client_tasks_id = $register_task_old->client_tasks_id;
                $register_task_new->registers_id = $register_task_old->registers_id;
                $register_task_new->analyst_users_id = $register_task_old->analyst_users_id;
                $register_task_new->priority = 'CPM';
                $register_task_new->start_date = now();
                $register_task_new->created_users_id = Auth::id();
                $register_task_new->save();

                // Se crea el nuevo evento
                $registers_event_new = new RegisterEvent();
                $registers_event_new->registers_id = $registers_id->id;
                $registers_event_new->stage_tasks_id = $registers_id->stage->id;
                $registers_event_new->states_id = $registers_id->states_id;
                $registers_event_new->register_tasks_id = $register_task_new->id;
                $registers_event_new->management = 'PAR';
                $registers_event_new->description = 'La solicitud pasa de suspendida a abierta, se crea un nuevo seguimiento';
                $registers_event_new->created_users_id = Auth::id();
                $registers_event_new->save();

            }else{ // Subsanación

                // Se buscan las tareas de subsanación que habian fallado

                $tracking = Tracking::where('registers_id', $registers_id->id)->whereIn('stage_tasks_id', [10, 11, 12, 13])
                ->orderBy('id','ASC')->pluck('id','stage_tasks_id')->toArray();

                $document_management = DocumentManagement::whereIn('trackings_id',$tracking)->where('valid','N');
                $tasks = $document_management->select('trackings.*')->addTracking()->pluck('stage_tasks_id','stage_tasks_id')->toArray();

                $aux = [];
                foreach ($tasks as $task) {
                    $register_event_old = RegisterEvent::where('registers_id',$registers_id->id)
                    ->where('stage_tasks_id',$task)
                    ->orderBy('id','DESC')
                    ->first();
                    $aux[]= $register_event_old->register_tasks_id;
                }

                // Se buscan las tareas
                $register_task_old = RegisterTask::where('registers_id',$registers_id->id)
                ->whereIn('id',$aux) // Tareas de subsanación que fallaron
                ->get();

                //Se crean las tareas y los eventos correspondientes
                foreach ($register_task_old as $task) {

                    // Se crea una nueva tarea
                    $register_task_new = new RegisterTask();
                    $register_task_new->client_tasks_id = $task->client_tasks_id;
                    $register_task_new->registers_id = $task->registers_id;
                    $register_task_new->analyst_users_id = $task->analyst_users_id;
                    $register_task_new->priority = 'CPM';
                    $register_task_new->start_date = now();
                    $register_task_new->created_users_id = Auth::id();
                    $register_task_new->save();

                    // Se crea el nuevo evento
                    $registers_event_new = new RegisterEvent();
                    $registers_event_new->registers_id = $registers_id->id;
                    $registers_event_new->stage_tasks_id = ClientTask::where('id',$task->client_tasks_id)->first()->stage_tasks_id;
                    $registers_event_new->states_id = $registers_id->states_id;
                    $registers_event_new->register_tasks_id = $register_task_new->id;
                    $registers_event_new->management = 'PAR';
                    $registers_event_new->description = 'La solicitud pasa de suspendida a abierta, se crea un nuevo seguimiento';
                    $registers_event_new->created_users_id = Auth::id();
                    $registers_event_new->save();
                }
            }
            DB::commit();
            Alert::success(__('Estado de la solicitud'), __('Se ha registrado la información'))->persistent('Close');
            return redirect()->back();

        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error al intentar pasar la solicitud de suspendida a abierta',
                "EXCEPTION: ".$exception->getMessage()
            ));
            Alert::warning(__('Estado de la solicitud'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
            return redirect()->back();
        }
    }
}
