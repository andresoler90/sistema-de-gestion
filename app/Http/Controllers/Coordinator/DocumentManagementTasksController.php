<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisterTask;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class DocumentManagementTasksController extends Controller
{
    public function index()
    {
        $register_tasks = RegisterTask::select('register_tasks.*',DB::raw("IF(registers.countries_id = registers.client_country_id,'Nacional','Internacional') as tipo_proveedor"))
        ->addRegisterEvent()
        ->addRegisterClient()
        ->addAnalyst()
        ->where([
            ['register_tasks.status','A'],
            ['register_events.stage_tasks_id',4],
            ['register_events.finished_at',null],
        ])
        ->orderBy('register_tasks.created_at')
        ->orderBy('register_tasks.priority')
        ->orderBy('register_events.description')
        ->orderBy('registers.business_name')
        ->paginate(10);

        $states = State::all()->pluck('name','id')->toArray();
        return view('coordinator.document_management.index',compact('register_tasks','states'));
    }

    public function search(Request $request)
    {
        $register_tasks = RegisterTask::select('register_tasks.*',DB::raw("IF(registers.countries_id = registers.client_country_id,'Nacional','Internacional') as tipo_proveedor"))
        ->addRegisterEvent()
        ->addRegisterClient()
        ->addAnalyst()
        ->where([
            ['register_tasks.status','A'],
            ['register_events.stage_tasks_id',4],
            ['register_events.finished_at',null],
        ])
        ->when($request->code, function ($query) use ($request){
            return $query->where('registers.code','like','%'.$request->code.'%');
        })
        ->when($request->client, function ($query) use ($request){
            return $query->where('clients.name','like','%'.$request->client.'%');
        })
        ->when($request->analyst, function ($query) use ($request){
            return $query->where('users.name','like','%'.$request->analyst.'%');
        })
        ->when($request->state, function ($query) use ($request){
            return $query->where('registers.states_id',$request->state);
        })
        ->when($request->priority, function ($query) use ($request){
            return $query->where('register_tasks.priority',$request->priority);
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
                case 'estado':
                    $newOrderBy = 'registers.states_id';
                    break;
                case 'prioridad':
                    $newOrderBy = 'register_tasks.priority';
                    break;
                case 'evento':
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
                case 'analista':
                    $newOrderBy = 'users.name';
                    break;
                case 'estado_ans':
                    $newOrderBy = 'register_tasks.management_status';
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

        $states = State::all()->pluck('name','id')->toArray();
        $data = $request;
        return view('coordinator.document_management.index',compact('register_tasks','states','data'));

    }
}
