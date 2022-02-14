<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\RegisterTask;
use App\Models\Stage;
use App\Models\User;
use App\Models\StageTask;
use Illuminate\Http\Request;
use App\Exports\InitialInternalManagementReport as InitialReport;

use DB;

class ReportInternalManagementController extends Controller
{
    public function index()
    {

        $stages = Stage::whereNotIn('id',[1,2,3])->orderBy('id')->get();
        $analysts = User::where('users.roles_id',2)->orderBy('id')->get();
        $analysts_tasks = [];

        foreach ($analysts as $analyst) {

            $obj = (object)['users_id' => $analyst->id, 'users_name' =>$analyst->name];
            $total = 0;
            foreach ($stages as $stage) {

                $stage_tasks = User::select('stages.id as stages_id','stages.name as stages_name',DB::raw('count(stages.id) as tasks'))
                ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
                ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
                ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
                ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
                ->where([
                    ['users.id',$analyst->id],
                    // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                    ['stages.id',$stage->id],
                ])
                ->first();

                $obj_stages = (object)[];
                $obj_stages->id = $stage_tasks->stages_id;
                $obj_stages->name = $stage_tasks->stages_name;
                $obj_stages->tasks = $stage_tasks->tasks;
                $total = $total + $stage_tasks->tasks;
                $obj->stages[] = $obj_stages;
            }
            if($total != 0){ // Si el analista tiene tareas en alguna etapa se muestra
                $obj->total = $total;
                $analysts_tasks[] = $obj;
            }
        }

        $stage_tasks =[];
        $total = 0;
        foreach ($stages as $stage) {
            $tasks = RegisterTask ::select('stages.id as id','stages.name as name',DB::raw('count(stages.id) as tasks'))
            ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
            ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
            ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
            ->where([
                ['register_tasks.analyst_users_id','<>',null],
                // ['register_tasks.status','C'], // solo las tareas que se han cerrado
                ['stages.id',$stage->id],
            ])
            ->first();
            $stage_tasks[] = $tasks;
            $total = $total + $tasks->tasks;
        }

        return view('reports.internal_management.index',compact('analysts_tasks','stage_tasks','total'));
    }

    public function search(Request $request)
    {
        $method = $this->getData($request);
        $analysts_tasks = $method[0];
        $stage_tasks = $method[1];
        $total = $method[2];
        $data = $request;

        return view('reports.internal_management.index',compact('analysts_tasks','stage_tasks','total','data'));
    }

    public function analyst( User $analyst)
    {
        $verifications = User::select('registers.code','stage_tasks.label as name','register_tasks.management_status as ans','register_tasks.status')
        ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
        ->join('registers','registers.id','=','register_tasks.registers_id')
        ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
        ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
        ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
        ->where([
            ['users.id',$analyst->id],
            // ['register_tasks.status','C'], // solo las tareas que se han cerrado
            ['stages.id',5], // Verificación : 5
        ])
        ->orderBy('registers.code')
        ->paginate(10,['*'],'verifications');

        $retrievals = User::select('registers.code','stage_tasks.label as name','register_tasks.management_status as ans','register_tasks.status')
        ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
        ->join('registers','registers.id','=','register_tasks.registers_id')
        ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
        ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
        ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
        ->where([
            ['users.id',$analyst->id],
            // ['register_tasks.status','C'], // solo las tareas que se han cerrado
            ['stages.id',6], // Subsanación : 6
        ])
        ->orderBy('registers.code')
        ->paginate(10,['*'],'retrievals');

        $verifications->appends(['retrievals' => $retrievals->currentPage()])->links();
        $retrievals->appends(['verifications' => $verifications->currentPage()])->links();

        return view('reports.internal_management.analyst',compact('analyst','verifications','retrievals'));
    }

    public function verification()
    {

        $analysts = User::select('id')->where('users.roles_id',2)->orderBy('id')->get();

        $verification_1 = $this->tasksVerification($analysts,5,'basica');
        $verification_2 = $this->tasksVerification($analysts,6,'experiencias');
        $verification_3 = $this->tasksVerification($analysts,7,'financiera');
        $verification_4 = $this->tasksVerification($analysts,8,'documentos');

        $verification_1->appends([
            'experiencias' => $verification_2->currentPage(),
            'financiera' => $verification_3->currentPage(),
            'documentos' => $verification_4->currentPage(),
        ])->links();

        $verification_2->appends([
            'basica' => $verification_1->currentPage(),
            'financiera' => $verification_3->currentPage(),
            'documentos' => $verification_4->currentPage(),
        ])->links();

        $verification_3->appends([
            'basica' => $verification_1->currentPage(),
            'experiencias' => $verification_2->currentPage(),
            'documentos' => $verification_4->currentPage(),
        ])->links();

        $verification_4->appends([
            'basica' => $verification_1->currentPage(),
            'experiencias' => $verification_2->currentPage(),
            'financiera' => $verification_3->currentPage(),
        ])->links();

        return view('reports.internal_management.verification',compact('verification_1','verification_2','verification_3','verification_4'));
    }


    public function tasksVerification($analysts, $task_id, $paginate_name)
    {

        $tasks = User::select('users.id as users_id','users.name as users_name','registers.code as code','stage_tasks.id as task_id','stage_tasks.label as task_name','register_tasks.status','register_tasks.management_status as ans')
        ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
        ->join('registers','registers.id','=','register_tasks.registers_id')
        ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
        ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
        ->whereIn('users.id',$analysts)
        ->where([
            // ['register_tasks.status','C'], // solo las tareas que se han cerrado
            ['stage_tasks.id',$task_id],
        ])
        ->orderBy('registers.code')
        ->paginate(10,['*'],$paginate_name);

        return $tasks;
    }

    public function export(Request $request)
    {
        $method = $this->getData($request);
        $analysts_tasks = $method[0];
        $stage_tasks = $method[1];
        $total = $method[2];
        $data = $request;

        $date = date('Y_m_d_h_i_s');
        return \Excel::download(new InitialReport($analysts_tasks,$stage_tasks,$total,$data), "Reporte-gestión-interna-$date.xlsx");
    }

    public function getData(Request $request)
    {
        $stages = Stage::whereNotIn('id',[1,2,3])->orderBy('id')->get();
        $analysts = User::where('users.roles_id',2)->orderBy('id')->get();
        $analysts_tasks = [];

        foreach ($analysts as $analyst) {

            $obj = (object)['users_id' => $analyst->id, 'users_name' =>$analyst->name];
            $total = 0;
            foreach ($stages as $stage) {

                $stage_tasks = User::select('stages.id as stages_id','stages.name as stages_name',DB::raw('count(stages.id) as tasks'))
                ->join('register_tasks','register_tasks.analyst_users_id','=','users.id')
                ->when($request->date_start, function ($query) use ($request){
                    return $query->where('register_tasks.start_date','>=',$request->date_start.' 00:00:00');
                })
                ->when($request->date_end, function ($query) use ($request){
                    return $query->where('register_tasks.start_date','<=',$request->date_end.' 23:59:59');
                })
                ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
                ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
                ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
                ->where([
                    ['users.id',$analyst->id],
                    ['register_tasks.status','C'], // solo las tareas que se han cerrado
                    ['stages.id',$stage->id],
                ])
                ->first();

                $obj_stages = (object)[];
                $obj_stages->id = $stage_tasks->stages_id;
                $obj_stages->name = $stage_tasks->stages_name;
                $obj_stages->tasks = $stage_tasks->tasks;
                $total = $total + $stage_tasks->tasks;
                $obj->stages[] = $obj_stages;
            }
            if($total != 0){ // Si el analista tiene tareas en alguna etapa se muestra
                $obj->total = $total;
                $analysts_tasks[] = $obj;
            }
        }

        $stage_tasks =[];
        $total = 0;
        foreach ($stages as $stage) {
            $tasks = RegisterTask ::select('stages.id as id','stages.name as name',DB::raw('count(stages.id) as tasks'))
            ->when($request->date_start, function ($query) use ($request){
                return $query->where('register_tasks.start_date','>=',$request->date_start.' 00:00:00');
            })
            ->when($request->date_end, function ($query) use ($request){
                return $query->where('register_tasks.start_date','<=',$request->date_end.' 23:59:59');
            })
            ->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
            ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
            ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id')
            ->where([
                ['register_tasks.analyst_users_id','<>',null],
                ['register_tasks.status','C'], // solo las tareas que se han cerrado
                ['stages.id',$stage->id],
            ])
            ->first();
            $stage_tasks[] = $tasks;
            $total = $total + $tasks->tasks;
        }

        return [$analysts_tasks,$stage_tasks,$total];
    }
}
