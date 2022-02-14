<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\ClientTask;
use Illuminate\Http\Request;
use App\Models\StageTask;
use App\Models\Stage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class CrudTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = StageTask::paginate(10);
        return view('admin.cruds.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stages = Stage::pluck('name', 'id');
        return view('admin.cruds.tasks.create', compact('stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'label' => 'required',
            'stages_id' => 'required',
            'estimated_time' => 'required',
        ]);

        $task = new StageTask();
        $task->fill($request->all());
        if ($task->save()) {
            Alert::success(__('Tarea'), __('Se ha registrado la información'));
            return redirect()->route('tasks.edit', $task->id);
        }
        Alert::warning(__('Tarea'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  StageTask  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(StageTask $task)
    {
        $stages = Stage::pluck('name', 'id');
        return view('admin.cruds.tasks.edit', ['data' => $task], compact('stages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StageTask $task)
    {
        $validated = $request->validate([
            'name' => 'required',
            'label' => 'required',
            'stages_id' => 'required',
            'estimated_time' => 'required',
        ]);

        $task->fill($request->all());
        if ($task->save()) {
            Alert::success(__('Tarea'), __('Se ha registrado la información'));
            return redirect()->route('tasks.edit', $task->id);
        }
        Alert::warning(__('Tarea'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StageTask $task)
    {
        if ($task->delete()) {
            ClientTask::where('stage_tasks_id', $task->id)->delete();
            Alert::success(__('Tarea'), __('Se ha eliminado el registro'));
            return redirect()->route('tasks.index');
        }
        Alert::warning(__('Tarea'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }
}
