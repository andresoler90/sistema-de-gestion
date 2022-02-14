<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\AnalystTask;
use App\Models\RegisterTask;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PriorityTaskController extends Controller
{
    public function index(){
        $register_tasks = RegisterTask::where([['register_tasks.status','A'],['analyst_users_id','<>',null]])->paginate(10);
        return view('coordinator.priority.index',compact('register_tasks'));
    }

    public function show(RegisterTask $task){

        // Se buscan los analistas a los que se les puede asignar la tarea
        $event = $task->last_event;
        $analyticsAvaible =AnalystTask::select('analyst_id')
        ->where('stage_tasks_id',$event->stage_tasks_id)->get();

        $analyst = User::whereIn('id',$analyticsAvaible)->pluck('name', 'id');

        return view('coordinator.priority.show',compact('task','analyst'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'priority' => 'required',
            'analyst' => 'required',
            'task_id' => 'required',
        ]);

        $task = RegisterTask::find($request->task_id);
        $task->priority = $request->priority;
        $task->analyst_users_id = $request->analyst;

        if($task->save()){
            Alert::success(__('Gestión'), __('Se ha registrado la información'));
            return redirect()->route('priority.tasks.show', $task);
        }

        Alert::warning(__('Gestión'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();

    }
}
