<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Validator;


class TrackingTaskController extends Controller
{
    public function index(){
        $registers = Register::whereIn('states_id',[1,5])->paginate(10); //1: Abierto, 5: Re abierto
        return view('coordinator.tracking.index',compact('registers'));
    }

    public function show(Register $register)
    {
        return view('coordinator.tracking.show',compact('register'));
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'tasks_gd' => 'required',
            'tasks_sub' => 'required',
            'register_id' => 'required',
        ]);

        $register = Register::find($request->register_id);
        $register->tasks_gd = $request->tasks_gd;
        $register->tasks_sub = $request->tasks_sub;

        if($register->save()){
            Alert::success(__('Seguimientos'), __('Se ha registrado la información'));
            return redirect()->route('tracking.tasks.index');
        }

        Alert::warning(__('Seguimientos'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();

    }
}
