<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\RegisterTask;
use App\Models\Stage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalystTaskController extends Controller
{
    public function index()
    {

        $tasks = RegisterTask::select('register_tasks.*','register_events.*')
        ->addRegisterEvent()
        ->addRegisterClient()
        ->where([
            ['register_tasks.analyst_users_id', Auth::id()],
            ['register_events.finished_at', null],
            ['registers.states_id','<>', 3], // Escalado al cliente
        ])
        ->whereIn('register_events.management',['PAR','PRO'])
        ->orderBy('register_tasks.created_at')
        ->orderBy('register_tasks.priority')
        ->orderBy('register_events.description')
        ->orderBy('registers.business_name')
        ->paginate(10);

        $stages = Stage::all()->pluck('name','id')->toArray();

        return view('analyst.tasks.index', compact('tasks','stages'));
    }

    public function search(Request $request)
    {

        $tasks = RegisterTask::select('register_tasks.*','register_events.*','stages.name as stages_name','stages.id as stages_id',DB::raw("IF(registers.countries_id = registers.client_country_id,'Nacional','Internacional') as tipo_proveedor"))
        ->addRegisterEvent()
        ->addStage()
        ->addRegisterClient()
        ->where([
            ['register_tasks.analyst_users_id', Auth::id()],
            ['register_events.finished_at', null],
            ['registers.states_id','<>', 3], // Escalado al cliente
        ])
        ->whereIn('register_events.management',['PAR','PRO'])
        ->when($request->code, function ($query) use ($request){
            return $query->where('registers.code','like','%'.$request->code.'%');
        })
        ->when($request->client, function ($query) use ($request){
            return $query->where('clients.name','like','%'.$request->client.'%');
        })
        ->when($request->priority, function ($query) use ($request){
            return $query->where('register_tasks.priority',$request->priority);
        })
        ->when($request->stage, function ($query) use ($request){
            return $query->where('stages_id',$request->stage);
        })
        ->when($request->business_name, function ($query) use ($request){
            return $query->where('registers.business_name','like','%'.$request->business_name.'%');
        })
        ->when($request->state_ans, function ($query) use ($request){
            return $query->where('register_tasks.management_status',$request->state_ans);
        })
        ->when($request->date_start, function ($query) use ($request){
            return $query->where('register_tasks.created_at','>=',$request->date_start.' 00:00:00');
        })
        ->when($request->date_end, function ($query) use ($request){
            return $query->where('register_tasks.created_at','<=',$request->date_end.' 23:59:59');
        })
        ->when($request->orderBy, function ($query) use ($request){
            if($request->order != 'ASC' && $request->order != 'DESC'){
                $request->order = 'ASC';
            }

            $newOrderBy = '';
            switch ($request->orderBy) {
                case 'codigo':
                    $newOrderBy = 'registers.code';
                    break;
                case 'fecha':
                    $newOrderBy = 'register_tasks.created_at';
                    break;
                case 'prioridad':
                    $newOrderBy = 'register_tasks.priority';
                    break;
                case 'etapa':
                    $newOrderBy = 'stages_name';
                    break;
                case 'descripcion':
                    $newOrderBy = 'register_events.description';
                    break;
                case 'razon_social':
                    $newOrderBy = 'registers.business_name';
                    break;
                case 'tipo_registro':
                    $newOrderBy = 'registers.register_type';
                    break;
                case 'tipo_proveedor':
                    $newOrderBy = 'tipo_proveedor';
                    break;
                case 'estado_ans':
                    $newOrderBy = 'register_tasks.management_status';
                    break;
                case 'gestion':
                    $newOrderBy = 'register_events.management';
                    break;
                default:
                    $newOrderBy = 'registers.code';
                    break;
            }
            return $query->orderBy($newOrderBy,$request->order);

        }, function ($query) {
            return $query
            ->orderBy('register_tasks.created_at')
            ->orderBy('register_tasks.priority')
            ->orderBy('register_events.description')
            ->orderBy('registers.business_name');
        })
        ->paginate(10);

        $data = $request;
        $stages = Stage::all()->pluck('name','id')->toArray();

        return view('analyst.tasks.index', compact('tasks','stages','data'));
    }

}
