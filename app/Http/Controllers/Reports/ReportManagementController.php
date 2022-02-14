<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Register;
use App\Models\RegisterEvent;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Stage;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\ManagementReport;
use App\Exports\ManagementMultipleSheets;


class ReportManagementController extends Controller
{
    public function index()
    {
        $is_client = Auth::user()->roles_id == 5;

        $registers_states = Register::select('states.id','states.name', DB::raw('count(registers.states_id) as total'))
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->rightJoin('states','states.id','registers.states_id')
        ->groupBy('states.id')
        ->get();

        $lasted = DB::raw('(select register_events.id, Max(register_events.id) as max_event from register_events group by register_events.registers_id) max_table');

        $registers_stages = Register::select('stages.name as name',DB::raw('count(stage_tasks.stages_id) as total'))
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->join('register_events','register_events.registers_id','registers.id')
        ->join($lasted, function ($join) {
            $join->on('max_table.max_event', '=', 'register_events.id');
        })
        ->join('stage_tasks', 'stage_tasks.id', '=', 'register_events.stage_tasks_id')
        ->rightJoin('stages', 'stages.id', '=', 'stage_tasks.stages_id')
        ->groupBy('stages.id')
        ->get();

        $registers_responsables = Register::select(DB::raw("IF(register_events.management = 'PAR', 'PAR',IF(register_events.management = 'CLI', 'Cliente','Proveedor')) as name"),DB::raw('count(register_events.management) as total'))
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->join('register_events','register_events.registers_id','registers.id')
        ->join($lasted, function ($join) {
            $join->on('max_table.max_event', '=', 'register_events.id');
        })
        ->groupBy('register_events.management')
        ->get();


        if($is_client){
            $clients = null;
            $total = Register::where('registers.clients_id',Auth::user()->client->id)->count();
        }else{
            $clients = Client::all()->pluck('name','id')->toArray();
            $total = Register::all()->count();
        }

        $states = State::all()->pluck('name','id')->toArray();
        $stages = Stage::all()->pluck('name','id')->toArray();

        return view('reports.management.index', compact('registers_states','registers_stages','registers_responsables','clients','states','stages','total'));
    }

    public function search(Request $request)
    {
        $states = State::all()->pluck('name','id')->toArray();
        $stages = Stage::all()->pluck('name','id')->toArray();

        $method = $this->getData($request);
        $registers_states = $method[0];
        $registers_stages = $method[1];
        $registers_responsables = $method[2];
        $total = $method[3];
        $clients = $method[4];
        $data = $request;

        return view('reports.management.index', compact('registers_states','registers_stages','registers_responsables','clients','states','stages','total','data'));
    }

    public function export(Request $request)
    {
        $method = $this->getData($request);
        $registers_states = $method[0];
        $registers_stages = $method[1];
        $registers_responsables = $method[2];
        $total = $method[3];

        $registers_states_detail = $method[4];
        return $registers_states_detail;

        $data = $request;

        if(isset($data->state))
            $data->state_name = State::find($data->state)->name;
        if(isset($data->stage))
            $data->stage_name = Stage::find($data->stage)->name;
        if(Auth::user()->roles_id == 1 && isset($data->client))
            $data->client_name = Client::find($data->client)->name;

        $date = date('Y_m_d_h_i_s');
        return \Excel::download(new ManagementMultipleSheets($registers_states,$registers_stages,$registers_responsables,$total,$data), "Reporte-gestión-$date.xlsx");
    }

    public function getData(Request $request)
    {
        $is_client = Auth::user()->roles_id == 5;

        $sql = RegisterEvent::select(DB::raw("MAX(register_events.id) as max_event"))
        ->when($request->date_start, function ($query) use ($request){
            return $query->where('register_events.created_at','>=',$request->date_start.' 00:00:00');
        })
        ->when($request->date_end, function ($query) use ($request){
            return $query->where('register_events.created_at','<=',$request->date_end.' 23:59:59');
        })
        ->rightJoin('states','states.id','register_events.states_id')
        ->when($request->state, function ($query) use ($request){
            return $query->where('states.id',$request->state);
        })
        ->join('stage_tasks','stage_tasks.id','register_events.stage_tasks_id')
        ->when($request->stage, function ($query) use ($request){
            return $query->where('stage_tasks.stages_id',$request->stage);
        })
        ->groupBy('register_events.registers_id');

        $eloquent_sql = $this->getEloquentSqlWithBindings($sql);
        $lasted = DB::raw("($eloquent_sql) max_table");

        // Estado de las solicitud
        $registers_states = Register::select('states.id','states.name', DB::raw('count(registers.states_id) as total'))
            ->when($is_client, function ($query){
                return $query->where('registers.clients_id',Auth::user()->client->id);
            })
            ->when($request->client, function ($query) use ($request){
                return $query->where('registers.clients_id',$request->client);
            })
            ->join('register_events','register_events.registers_id','registers.id')
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->rightJoin('states','states.id','register_events.states_id')
            ->groupBy('states.id')
            ->get();
        //////////////////////////

        // Estado de las solicitud detalle
        $registers_states_detail = Register::select('registers.*')
            ->when($is_client, function ($query){
                return $query->where('registers.clients_id',Auth::user()->client->id);
            })
            ->when($request->client, function ($query) use ($request){
                return $query->where('registers.clients_id',$request->client);
            })
            ->join('register_events','register_events.registers_id','registers.id')
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            // ->rightJoin('states','states.id','register_events.states_id')
            // ->groupBy('states.id')
            ->get();
        //////////////////////////

        // Etapa de las solicitud
        $registers_stages = Register::select('stages.name as name',DB::raw('count(stage_tasks.stages_id) as total'))
            ->when($is_client, function ($query){
                return $query->where('registers.clients_id',Auth::user()->client->id);
            })
            ->when($request->client, function ($query) use ($request){
                return $query->where('registers.clients_id',$request->client);
            })
            ->join('register_events','register_events.registers_id','registers.id')
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->join('stage_tasks', 'stage_tasks.id', '=', 'register_events.stage_tasks_id')
            ->rightJoin('stages', 'stages.id', '=', 'stage_tasks.stages_id')
            ->groupBy('stages.id')
            ->get();
        //////////////////////////

        // Actual responsable de la gestión de la solicitud
        $registers_responsables = Register::select(DB::raw("IF(register_events.management = 'PAR', 'PAR',IF(register_events.management = 'CLI', 'Cliente','Proveedor')) as name"),DB::raw('count(register_events.management) as total'))
            ->when($is_client, function ($query){
                return $query->where('registers.clients_id',Auth::user()->client->id);
            })
            ->when($request->client, function ($query) use ($request){
                return $query->where('registers.clients_id',$request->client);
            })
            ->join('register_events','register_events.registers_id','registers.id')
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->groupBy('register_events.management')
            ->get();
        //////////////////////////

        if($is_client){
            $clients = null;
            $total = Register::where('registers.clients_id',Auth::user()->client->id)->count();
        }else{
            $clients = Client::all()->pluck('name','id')->toArray();
            if(isset($request->client)){
                $total = Register::where('clients_id',$request->client)->count();
            }else{
                $total = Register::all()->count();
            }
        }
        return [$registers_states,$registers_stages,$registers_responsables,$total,$clients,$registers_states_detail];
    }


    public function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}
