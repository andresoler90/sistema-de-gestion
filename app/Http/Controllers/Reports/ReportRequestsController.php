<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Models\QualityDocumentReview;
use App\Models\Register;
use App\Models\RegisterContact;
use App\Models\RegisterEvent;
use App\Models\RegisterSalesforceActivity;
use App\Models\RegisterTask;
use App\Models\State;
use App\Models\Stage;
use App\Models\Tracking;
use App\Models\Verification;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportRequestsController extends Controller
{
    public function index()
    {
        $lasted = DB::raw('(select register_events.id, Max(register_events.id) as max_event from register_events group by register_events.registers_id) max_table');

        if(Auth::user()->roles_id == 1){ // Administrador
            $registers = Register::select('registers.*','register_events.management as management')
            ->addEvent()
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->paginate(10);
        }else if(Auth::user()->roles_id == 2){ // Analista
            $register_tasks = RegisterTask::select('registers_id')->where('analyst_users_id', Auth::id())->groupBy('registers_id');
            $registers = Register::select('registers.*','register_events.management as management')
            ->whereIn('registers.id',$register_tasks)->orderByDesc('registers.created_at')
            ->addEvent()
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->paginate(10);
        }else{
            $registers = Register::select('registers.*','register_events.management as management')
            ->where('clients_id',Auth::user()->client->id)->orderByDesc('registers.created_at')
            ->addEvent()
            ->join($lasted, function ($join) {
                $join->on('max_table.max_event', '=', 'register_events.id');
            })
            ->paginate(10);
        }

        $states = State::all()->pluck('name','id')->toArray();
        $stages = Stage::all()->pluck('name','id')->toArray();


        return view('reports.request.index', compact('registers','states','stages'));
    }

    public function detail(Register $register)
    {
        if(Auth::user()->roles_id == 5){ // Cliente
            if($register->clients_id != Auth::user()->client->id){
                return redirect()->route('reports.request.index');
            }
        }else if(Auth::user()->roles_id == 2){ // Analista
            $register_tasks = RegisterTask::where([['registers_id',$register->id],['analyst_users_id', Auth::id()]])->first();
            if(!isset($register_tasks)){
                return redirect()->route('reports.request.index');
            }
        }


        // Seguimientos en gestión documental
        $trackings_gd = Tracking::select('id','created_at','created_users_id')
            ->where([
                ['registers_id', $register->id],
                ['stage_tasks_id', 4]
            ])
            ->orderBy('created_at','ASC')
            ->get();

        // Seguimientos en Verificación
        $trackings_ve = Verification::select('verifications.register_tasks_id','verifications.created_at','verifications.created_users_id', 'stage_tasks.label')
            ->where('verifications.registers_id',$register->id)
            ->addClientDocument()
            ->groupBy('client_documents.stage_tasks_id')
            ->orderBy('verifications.created_at','ASC')
            ->get();

        // Seguimientos en subsanación
        $trackings_sub = Tracking::select('id','stage_tasks_id','created_at','created_users_id')
            ->where('registers_id',$register->id)
            ->whereIn('stage_tasks_id',[10,11,12,13])
            ->orderBy('created_at','ASC')
            ->orderBy('stage_tasks_id')
            ->get();

        // Seguimientos en calidad- subsanación
        $trackings_ca_sub = Tracking::select('id','stage_tasks_id','created_at','created_users_id')
            ->where('registers_id',$register->id)
            ->whereIn('stage_tasks_id',[16,17,18,19])
            ->orderBy('created_at','ASC')
            ->orderBy('stage_tasks_id')
            ->get();

        // Seguimientos en Calidad
        $trackings_ca = QualityDocumentReview::select('register_tasks_id','created_at','created_users_id')
            ->where('quality_document_review.registers_id',$register->id)
            ->groupBy('quality_document_review.register_tasks_id')
            ->orderBy('quality_document_review.created_at','ASC')
            ->get();

        $salesforce = Register::select('registers.*')
            ->join('register_contacts','register_contacts.register_id','=','registers.id')
            ->where('registers.id',$register->id)->first();

        $trackings = [];

        foreach ($trackings_gd as $key => $tracking) {
            $trackings[] = (object)[
                'type' => 1,
                'stage_name' => (count($trackings_gd)>1)?'Gestión documental ['.($key+1).']' : 'Gestión documental',
                'id' => $tracking->id,
                'created_at' => $tracking->created_at,
                'analyst' => $tracking->created_user->name,
                'class' => 'show-tracking',
            ];
        }
        foreach ($trackings_ve as $tracking) {
            $trackings[] = (object)[
                'type' => 2,
                'stage_name' => $tracking->label,
                // 'register_tasks_id' => $tracking->register_tasks_id,
                'id' => $tracking->register_tasks_id,
                'created_at' => $tracking->created_at,
                'label' => $tracking->label,
                'analyst' => $tracking->created_user->name,
                'class' => 'show-tracking-ve',
            ];
        }
        foreach ($trackings_sub as $key => $tracking) {

            $stage_name = 'Subsanación';
            $stage_name .= (count($trackings_sub)>1)?' ['.($key+1).'] ' : ' ';
            $stage_name .= $tracking->stage_tasks->label;

            $trackings[] = (object)[
                'type' => 3,
                'stage_name' => $stage_name,
                'id' => $tracking->id,
                'created_at' => $tracking->created_at,
                'analyst' => $tracking->created_user->name,
                'class' => 'show-tracking',
            ];
        }
        foreach ($trackings_ca_sub as $key => $tracking) {

            $stage_name = 'Calidad - Subsanación';
            $stage_name .= (count($trackings_ca_sub)>1)?' ['.($key+1).'] ' : ' ';
            $stage_name .= $tracking->stage_tasks->label;

            $trackings[] = (object)[
                'type' => 4,
                'stage_name' => $stage_name,
                'id' => $tracking->id,
                'created_at' => $tracking->created_at,
                'analyst' => $tracking->created_user->name,
                'class' => 'show-tracking',
            ];
        }
        foreach ($trackings_ca as $key => $tracking) {
            $trackings[] = (object)[
                'type' => 5,
                'stage_name' => (count($trackings_ca)>1)?'Calidad ['.($key+1).']' : 'Calidad',
                // 'register_tasks_id' => $tracking->register_tasks_id,
                'id' => $tracking->register_tasks_id,
                'created_at' => $tracking->created_at,
                'analyst' => $tracking->created_user->name,
                'class' => 'show-tracking-ca',
            ];
        }

        if ($salesforce) {
            $trackings[] = (object)[
                'type' => 6,
                'stage_name' => 'Comercial',
                'id' => $register->id,
                'created_at' => $salesforce->created_at,
                'analyst' => null,
                'class' => 'show-tracking-salesforce',
            ];
        }

        usort($trackings, $this->object_sorter('created_at')); // Ordena el objeto por el campo que se le pase

        $process_flow = new ProcessFlow($register);
        $par = $process_flow->managementTime('PAR');
        $pro = $process_flow->managementTime('PRO');
        $cli = $process_flow->managementTime('CLI');

        $stages = Stage::all();

        return view('reports.request.detail', compact('register','par','pro','cli','stages','trackings'));

    }

    public function search(Request $request)
    {

        $type_user = Auth::user()->roles_id;
        $lasted = DB::raw('(select register_events.id, Max(register_events.id) as max_event from register_events group by register_events.registers_id) max_table');

        $registers = Register::select('registers.*','register_events.id as register_events_id','stages.name as stages_name','stages.id as stages_id','register_events.management as management')
        ->addEventStage()
        ->join($lasted, function ($join) {
            $join->on('max_table.max_event', '=', 'register_events.id');
        })
        ->addClient()
        ->when($type_user, function ($query) use ($type_user){
            if($type_user== 2){ // Analista
                $register_tasks = RegisterTask::select('registers_id')->where('analyst_users_id', Auth::id())->groupBy('registers_id');
                return $query->whereIn('registers.id',$register_tasks);
            }else if ($type_user== 5){ // Cliente
                return $query->where('registers.clients_id',Auth::user()->client->id);
            }
        })
        ->when($request->code, function ($query) use ($request){
            return $query->where('registers.code','like','%'.$request->code.'%');
        })
        ->when($request->identification, function ($query) use ($request){
            return $query->where('registers.identification_number','like','%'.$request->identification.'%');
        })
        ->when($request->business_name, function ($query) use ($request){
            return $query->where('registers.business_name','like','%'.$request->business_name.'%');
        })
        ->when($request->client, function ($query) use ($request){
            return $query->where('clients.name','like','%'.$request->client.'%');
        })
        ->when($request->stage, function ($query) use ($request){
            return $query->where('stages_id',$request->stage);
        })
        ->when($request->state, function ($query) use ($request){
            return $query->where('registers.states_id',$request->state);
        })
        ->when($request->date_start, function ($query) use ($request){
            return $query->where('registers.created_at','>=',$request->date_start.' 00:00:00');
        })
        ->when($request->date_end, function ($query) use ($request){
            return $query->where('registers.created_at','<=',$request->date_end.' 23:59:59');
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
                case 'identificacion':
                    $newOrderBy = 'registers.identification_number';
                    break;
                case 'razon_social':
                    $newOrderBy = 'registers.business_name';
                    break;
                case 'cliente':
                    $newOrderBy = 'clients.name';
                    break;
                case 'etapa':
                    $newOrderBy = 'stages_name';
                    break;
                case 'estado':
                    $newOrderBy = 'registers.states_id';
                    break;
                case 'gestion':
                    $newOrderBy = 'register_events.management';
                    break;
                case 'fecha':
                    $newOrderBy = 'registers.created_at';
                    break;
                default:
                    $newOrderBy = 'registers.code';
                    break;
            }
            return $query->orderBy($newOrderBy,$request->order);

        }, function ($query) {
            return $query->orderBy('registers.code');
        })
        ->paginate(10);


        $data = $request;
        $states = State::all()->pluck('name','id')->toArray();
        $stages = Stage::all()->pluck('name','id')->toArray();

        return view('reports.request.index', compact('registers','states','stages','data'));

    }

    // Función que permite ordenar un objeto por un campo en particular
    public function object_sorter($clave,$orden=null) {
        return function ($a, $b) use ($clave,$orden) {
            $result = ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) : strnatcmp($a->$clave, $b->$clave);
            return $result;
        };
    }
}
