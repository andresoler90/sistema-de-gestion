<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisterTask;
use App\Models\StageTask;
use App\Models\User;
use DB;
use App\Exports\QualityReport;

class ReportQualityController extends Controller
{
    public function index()
    {
        $stage_tasks = StageTask::whereIn('id',[16,17,18,19])->orderBy('id')->get(); //Tareas de subsanación en calidad
        $analysts = User::where('users.roles_id',2)->orderBy('id')->get();
        $analysts_tasks = [];

        foreach ($analysts as $analyst) {

            $obj = (object)['users_id' => $analyst->id, 'users_name' =>$analyst->name];
            $total = 0;
            foreach ($stage_tasks as $stage_task) {

                $tasks = User::select('stage_tasks.id as stage_tasks_id','stage_tasks.label as stage_tasks_label',DB::raw('count(stage_tasks.id) as tasks'))
                ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
                ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
                ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
                ->where([
                    ['users.id',$analyst->id],
                    // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                    ['stage_tasks.id',$stage_task->id],
                ])
                ->first();

                $obj_stages = (object)[];
                $obj_stages->id = $tasks->stage_tasks_id;
                $obj_stages->name = $tasks->stage_tasks_label;
                $obj_stages->tasks = $tasks->tasks;
                $total = $total + $tasks->tasks;
                $obj->stages[] = $obj_stages;
            }
            if($total != 0){ // Si el analista tiene tareas en alguna etapa se muestra
                $obj->total = $total;
                $analysts_tasks[] = $obj;
            }
        }

        $verification_tasks =[];
        $total = 0;
        foreach ($stage_tasks as $stage_task) {
            $tasks = RegisterTask ::select('stage_tasks.id as stage_tasks_id','stage_tasks.label as stage_tasks_label',DB::raw('count(stage_tasks.id) as tasks'))
            ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
            ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
            ->where([
                ['register_tasks.analyst_users_id','<>',null],
                // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                ['stage_tasks.id',$stage_task->id],
            ])
            ->first();
            $verification_tasks[] = $tasks;
            $total = $total + $tasks->tasks;
        }

        return view('reports.quality.index',compact('analysts_tasks','verification_tasks','total'));
    }

    public function search(Request $request)
    {
        $method = $this->getData($request);
        $analysts_tasks = $method[0];
        $verification_tasks = $method[1];
        $total = $method[2];
        $data = $request;

        return view('reports.quality.index',compact('analysts_tasks','verification_tasks','total','data'));
    }

    public function export(Request $request)
    {
        $method = $this->getData($request);
        $analysts_tasks = $method[0];
        $verification_tasks = $method[1];
        $total = $method[2];
        $data = $request;

        $date = date('Y_m_d_h_i_s');
        return \Excel::download(new QualityReport($analysts_tasks,$verification_tasks,$total,$data), "Reporte-calidad-$date.xlsx");

    }

    public function getData(Request $request)
    {
        $stage_tasks = StageTask::whereIn('id',[16,17,18,19])->orderBy('id')->get(); //Tareas de subsanación en calidad
        $analysts = User::where('users.roles_id',2)->orderBy('id')->get();
        $analysts_tasks = [];

        foreach ($analysts as $analyst) {

            $obj = (object)['users_id' => $analyst->id, 'users_name' =>$analyst->name];
            $total = 0;
            foreach ($stage_tasks as $stage_task) {

                $tasks = User::select('stage_tasks.id as stage_tasks_id','stage_tasks.label as stage_tasks_label',DB::raw('count(stage_tasks.id) as tasks'))
                ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
                ->when($request->date_start, function ($query) use ($request){
                    return $query->where('register_tasks.start_date','>=',$request->date_start.' 00:00:00');
                })
                ->when($request->date_end, function ($query) use ($request){
                    return $query->where('register_tasks.start_date','<=',$request->date_end.' 23:59:59');
                })
                ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
                ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
                ->where([
                    ['users.id',$analyst->id],
                    // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                    ['stage_tasks.id',$stage_task->id],
                ])
                ->first();

                $obj_stages = (object)[];
                $obj_stages->id = $tasks->stage_tasks_id;
                $obj_stages->name = $tasks->stage_tasks_label;
                $obj_stages->tasks = $tasks->tasks;
                $total = $total + $tasks->tasks;
                $obj->stages[] = $obj_stages;
            }
            if($total != 0){ // Si el analista tiene tareas en alguna etapa se muestra
                $obj->total = $total;
                $analysts_tasks[] = $obj;
            }
        }

        $verification_tasks =[];
        $total = 0;
        foreach ($stage_tasks as $stage_task) {
            $tasks = RegisterTask ::select('stage_tasks.id as stage_tasks_id','stage_tasks.label as stage_tasks_label',DB::raw('count(stage_tasks.id) as tasks'))
            ->when($request->date_start, function ($query) use ($request){
                return $query->where('register_tasks.start_date','>=',$request->date_start.' 00:00:00');
            })
            ->when($request->date_end, function ($query) use ($request){
                return $query->where('register_tasks.start_date','<=',$request->date_end.' 23:59:59');
            })
            ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
            ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
            ->where([
                ['register_tasks.analyst_users_id','<>',null],
                // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                ['stage_tasks.id',$stage_task->id],
            ])
            ->first();
            $verification_tasks[] = $tasks;
            $total = $total + $tasks->tasks;
        }
        return [$analysts_tasks,$verification_tasks, $total];
    }

}
