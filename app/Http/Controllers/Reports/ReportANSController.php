<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ANSReport;
use App\Exports\ANSReportDetail;
use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Models\ClientTask;
use App\Models\StageTask;
use Illuminate\Support\Facades\Auth;

class ReportANSController extends Controller
{
    public function index()
    {
        $is_client = Auth::user()->roles_id == 5;
        $states = [2,3,4,6]; //Cerrado, Escalado al cliente,Cancelado, Anulado

        $registers = Register::select('registers.*')
            ->when($is_client, function ($query){
                return $query->where('registers.clients_id',Auth::user()->client->id);
            })
            ->AddEvent()
            ->whereIn('registers.states_id',$states)
            ->groupBy('registers.id')->get();

        $method = $this->getData($registers);
        $register_times = $method[0];
        $management_time = $method[1];
        $totalRegisters = $method[2];

        return view('reports.ans.index',compact('register_times','management_time','totalRegisters'));
    }


    public function search(Request $request)
    {
        $is_client = Auth::user()->roles_id == 5;
        $states = [2,3,4,6]; //Cerrado, Escalado al cliente,Cancelado, Anulado

        $registers = Register::select('registers.*')
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->AddEvent()
        ->when($request->date_start, function ($query) use ($request){
            return $query->where('registers.created_at','>=',$request->date_start);
        })
        ->when($request->date_end, function ($query) use ($request){
            return $query->where('registers.created_at','<=',$request->date_end);
        })
        ->whereIn('registers.states_id',$states)
        ->groupBy('registers.id')->get();

        $data = $request;
        $method = $this->getData($registers);
        $register_times = $method[0];
        $management_time = $method[1];
        $totalRegisters = $method[2];

        return view('reports.ans.index',compact('data','register_times','management_time','totalRegisters'));
    }

    public function detail(Register $register){

        $method = $this->getDataDetail($register);
        $register_times = $method[0];
        $total = $method[1];
        return view('reports.ans.detail',compact('register','register_times','total'));
    }

    public function export(Request $request)
    {
        $is_client = Auth::user()->roles_id == 5;
        $states = [2,3,4,6];
        $registers = Register::select('registers.*')
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->AddEvent()
        ->when($request->date_start, function ($query) use ($request){
            return $query->where('registers.created_at','>=',$request->date_start);
        })
        ->when($request->date_end, function ($query) use ($request){
            return $query->where('registers.created_at','<=',$request->date_end);
        })
        ->whereIn('registers.states_id',$states)
        ->groupBy('registers.id')->get();

        $data = $request;
        $method = $this->getData($registers);
        $register_times = $method[0];
        $management_time = $method[1];
        $totalRegisters = $method[2];

        $date = date('Y_m_d_h_i_s');
        return \Excel::download(new ANSReport($register_times,$management_time,$totalRegisters,$data), "Reporte-ANS-$date.xlsx");
    }

    public function exportDetail(Register $register)
    {
        $method = $this->getDataDetail($register);
        $register_times = $method[0];
        $total = $method[1];

        $date = date('Y_m_d_h_i_s');
        return \Excel::download(new ANSReportDetail($register,$register_times,$total), "Reporte-ANS-Detalle-$date.xlsx");
    }


    public function getData($registers)
    {
        $register_times = (object)[];
        $management_time = ['REAL' => 0, 'PAR' => 0, 'PRO' => 0,'CLI' => 0];
        $totalRegisters = $registers->count();

        foreach ($registers as $register) {
            $process_flow = new ProcessFlow($register);
            $managements = ['PAR','PRO','CLI'];
            $durationReal = 0;

            foreach ($managements as $type){
                $duration[$type] = $process_flow->managementTime($type);
                $durationReal += $process_flow->managementTime($type)->total_hours;
            }
            $management_time['REAL'] += $durationReal;
            $management_time['PAR'] += $duration['PAR']->total_hours;
            $management_time['PRO'] += $duration['PRO']->total_hours;
            $management_time['CLI'] += $duration['CLI']->total_hours;

            $obj = (object)[
                'id' => $register->id,
                'code' => $register->code,
                'identification_number' => $register->identification_number,
                'business_name' => $register->business_name,
                'real' => round($durationReal,2),
                'par' => round($duration['PAR']->total_hours,2),
                'pro' => round($duration['PRO']->total_hours,2),
                'cli' => round($duration['CLI']->total_hours,2),
            ];
            $register_times->times[]= $obj;
        }
        return [$register_times,$management_time,$totalRegisters];
    }

    public function getDataDetail(Register $register)
    {
        $process_flow = new ProcessFlow($register);
        $stage_tasks = StageTask::whereNotIn('name',['ve_task_5','su_task_5'])->with('stage')->get();

        $register_times = (object)[];
        $total = ['estimate' => 0, 'real' => 0, 'par' => 0,'pro' => 0,'cli' => 0, 'ans' => 0];
        $total_ans = 0;

        foreach ($stage_tasks as $stage_task){
            $par = $process_flow->managementTime('PAR',$stage_task->id)->total_hours;
            $pro = $process_flow->managementTime('PRO',$stage_task->id)->total_hours;
            $cli = $process_flow->managementTime('CLI',$stage_task->id)->total_hours;
            $real = $par + $pro + $cli;

            $client_task = ClientTask::where([
                ['clients_id',$register->clients_id],
                ['stage_tasks_id',$stage_task->id]
            ])->withTrashed()->first();

            $ans = ($real > $client_task->ans_time)? 'No': 'Si';

            if( $ans == 'Si'){
                $total_ans ++;
            }

            $obj = (object)[
                'stage' => $stage_task->stage->name,
                'task' => $stage_task->label,
                'estimate' => $client_task->ans_time,
                'real' => round($real,2),
                'par' => round($par,2),
                'pro' => round($pro,2),
                'cli' => round($cli,2),
                'ans' => $ans,
            ];
            $total['estimate'] += $obj->estimate;
            $total['real'] += $obj->real;
            $total['par'] += $obj->par;
            $total['pro'] += $obj->pro;
            $total['cli'] += $obj->cli;

            $register_times->times[]= $obj;
        }
        $total['ans'] = round($total_ans * 100 / count($stage_tasks),2);

        return [$register_times, $total];
    }


}
