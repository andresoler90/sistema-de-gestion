<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterManagement;
use App\Models\RegisterTask;
use App\Models\Tracking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;



class ScaledRegisterController extends Controller
{
    public function index(){


        $register_management = RegisterManagement::select('registers_id')->where('status','A')->get();

        $scaled_registers = Register::where([
            ['states_id', 3],
            ['clients_id', Auth::user()->client->id],
        ])
        ->whereNotIn('id',$register_management)
        ->paginate(10);

        return view('client.scaled.index', compact('scaled_registers'));
    }

    public function showDocumentManagement(Register $register)
    {
        $register_event = RegisterEvent::where([
            ['registers_id',$register->id],
            ['finished_at',null],
            ['management','CLI'],
        ])->first();

        if(isset($register_event)){

            $register_task = RegisterTask::find($register_event->register_tasks_id);
            $trackings = Tracking::where([['registers_id',$register->id],['stage_tasks_id',4]])->orderBy('created_at', 'desc')->get();

            return view('client.scaled.document_management_show', compact('register_task','register','trackings','register_event'));
        }else{
            return redirect()->route('scaled.registers.index');
        }
    }

    public function showRetrieval(Register $register)
    {
        $register_event = RegisterEvent::where([
            ['registers_id',$register->id],
            ['finished_at',null],
            ['management','CLI'],
        ])->first();

        if(isset($register_event)){

            $register_task = RegisterTask::find($register_event->register_tasks_id);
            $trackings = Tracking::where('registers_id',$register->id)
                        ->whereIn('stage_tasks_id',[10,11,12,13])
                        ->orderBy('stage_tasks_id')
                        ->orderBy('created_at', 'desc')
                        ->get();

            return view('client.scaled.retrieval_show', compact('register_task','register','trackings','register_event'));
        }else{
            return redirect()->route('scaled.registers.index');
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'management_type' => 'required',
            'observations' => 'required',
            'decision' => 'required',
            'registers_id' => 'required',
            'register_event_id' => 'required',
        ]);

        dd('Hola');
        DB::beginTransaction();
        try {

            $register_management = new RegisterManagement();
            $register_management->fill($request->except(['register_event_id']));
            $register_management->created_users_id = Auth::id();

            if($request->decision=='CS'){
                $register_management->status = 'C';
            }

            $register_management->save();

            // Se cierra el evento
            $register_event = RegisterEvent::find($request->register_event_id);
            $register_event->finished_at = now();
            $register_event->save();

            // Si la decisión por parte del cliente es cancelar la solicitud
            if($register_management->decision=='CS'){

                // Se cierran todas las tareas relacionadas [Para gestión documental solo es una pero para subsanación pueden ser varias]
                $register_tasks = RegisterTask::where('registers_id',$request->registers_id)->get();

                foreach ($register_tasks as $register_task) {

                    $register_task_new = RegisterTask::find($register_task->id);
                    $register_task_new->end_date = now();
                    $register_task_new->status = 'C';
                    $register_task_new->save();
                }

                // Se cancela la solicitud
                $register = Register::find($request->registers_id);
                $register->states_id = 4; // 4: Cancelado
                $register->save();

                // Se registra el nuevo evento
                $register_event_new = new RegisterEvent();
                $register_event_new->registers_id = $register_event->registers_id;
                $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                $register_event_new->states_id = $register->states_id; //[states_id]
                $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                $register_event_new->management = 'CLI';
                $register_event_new->description = 'El cliente decidio cancelar la solicitud';
                $register_event_new->created_users_id = Auth::id();
                $register_event_new->finished_at = now();
                $register_event_new->save();
            }else{

                // Se registra el nuevo evento
                $register_event_new = new RegisterEvent();
                $register_event_new->registers_id = $register_event->registers_id;
                $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                $register_event_new->states_id = $register_event->states_id; // [states_id]
                $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                $register_event_new->management = 'PAR';
                $register_event_new->description = $register_management->decision =='SN'? 'El cliente solicita un nuevo seguimiento':'El cliente solicita enviar soporte al proveedor';
                $register_event_new->created_users_id = Auth::id();
                $register_event_new->save();
            }

            DB::commit();
            Alert::success(__('Gestión'), __('Se ha registrado la información'));
            return redirect()->route('scaled.registers.index');

        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error al dar respuesta a la solicitud escalada (Cliente)',
                "REQUEST: ".json_encode($request->all())."\nEXCEPTION: ".$exception->getMessage()
            ));
            Alert::warning(__('Calidad'), __('Ha surgido un error, por favor intente nuevamente'));
            return redirect()->back();
        }
    }

}
