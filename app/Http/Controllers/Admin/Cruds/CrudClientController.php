<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Country;
use App\Models\MiProveedor\Cl1Clients;
use App\Models\SettingClient;
use App\Models\User;
use App\Models\Stage;
use App\Models\ClientTask;
use App\Models\StageTask;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;
use App\Models\ClientsCountry;
use App\Models\ConfigurableWordClient;
use App\Models\Register;

class CrudClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::paginate(10);
        $clients_name = Client::pluck('name','id');

        return view('admin.cruds.clients.index', compact('clients','clients_name'));
    }

    public function search(Request $request)
    {
        $clients = Client::select('*')
        ->when($request->clients_id, function ($query) use ($request){
            return $query->where('clients.id',$request->clients_id);
        })
        ->when($request->orderBy, function ($query) use ($request){
            if($request->order != 'ASC' && $request->order != 'DESC'){
                $request->order = 'ASC';
            }

            $newOrderBy = '';
            switch ($request->orderBy) {
                case 'client':
                    $newOrderBy = 'clients.id';
                    break;
                default:
                    $newOrderBy = 'clients.id';
                    break;
            }
            return $query->orderBy($newOrderBy,$request->order);

        })
        ->paginate(10);

        $clients_name = Client::pluck('name','id');
        $data = $request;

        return view('admin.cruds.clients.index', compact('clients','clients_name','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mp_clients = Cl1Clients::Active()->orderBy('cl1_name','ASC')->pluck('cl1_name','cl1_id')->toArray();
        return view('admin.cruds.clients.create', compact('mp_clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
            'acronym' => 'required|unique:clients|min:3|max:3',
            'interval_gd' => 'required|numeric|min:0',
            'interval_sub' => 'required|numeric|min:0',
            'days_waiting' => 'required|numeric|min:0',
            'analyst_time' => 'required|numeric|min:1',
            'coordinator_time' => 'required|numeric|min:1',
            'mp_clients_id' => 'required',
        ]);

        $client = new Client();
        $client->fill($request->all());
        $client->created_users_id = Auth::id();
        if ($client->save()) {

            $stage_tasks = StageTask::all();
            foreach ($stage_tasks as $stage_task) {
                $client_task = new ClientTask();
                $client_task->clients_id = $client->id;
                $client_task->stage_tasks_id = $stage_task->id;
                $client_task->created_users_id = Auth::id();
                $client_task->ans_time = 0;
                $client_task->save();
            }


            Alert::success(__('Cliente'), __('Se ha registrado la información'));
            return redirect()->route('clients.edit', $client->id);
        }
        Alert::warning(__('Cliente'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $settingClients = SettingClient::where('client_id',$client->id)->get();
        $mp_clients = Cl1Clients::Active()->orderBy('cl1_name','ASC')->pluck('cl1_name','cl1_id')->toArray();

        $stage_tasks = StageTask::whereNotIn('stage_tasks.name',['ve_task_5','su_task_5'])->get();
        $client_tasks = ClientTask::where('clients_id',$client->id)->get();
        $tasks =[];

        foreach ($stage_tasks as $stage_task) {
            $obj = (object)[
                'stage_task_label' => $stage_task->label,
                'stage_task_id' => $stage_task->id,
                'visible' => $stage_task->visible,
            ];
            foreach ($client_tasks as $client_task) {
                if($client_task->stage_tasks_id == $stage_task->id){
                    $obj->client_task_id= $client_task->id;
                    $obj->client_task_ans_time= $client_task->ans_time;
                }
            }
            $tasks[] = $obj;
        }

        $words = ConfigurableWordClient::where('clients_id',$client->id)->paginate(10,['*'],'words');

        $users = $client->users()->paginate(10,['*'],'users');
        $documents = $client->documents()->paginate(10,['*'],'documents');

        $countries =  ClientsCountry::where('clients_id',$client->id)->paginate(10,['*'],'countries');

        $words->appends([
            'documents' => $documents->currentPage(),
            'users' => $users->currentPage(),
            'countries' => $countries->currentPage()
        ])->links();

        $users->appends([
            'documents' => $documents->currentPage(),
            'words' => $words->currentPage(),
            'countries' => $countries->currentPage()
        ])->links();

        $documents->appends([
            'users' => $users->currentPage(),
            'words' => $words->currentPage(),
            'countries' => $countries->currentPage()
        ])->links();

        $countries->appends([
            'users' => $users->currentPage(),
            'words' => $words->currentPage(),
            'documents' => $documents->currentPage(),
        ])->links();

        return view('admin.cruds.clients.edit', ['data' => $client], compact('mp_clients','settingClients','users','documents','tasks','words','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
            'acronym' => 'required|min:3|max:3|unique:clients,acronym,'.$client->id,
            'interval_gd' => 'required|numeric|min:0',
            'interval_sub' => 'required|numeric|min:0',
            'days_waiting' => 'required|numeric|min:0',
            'analyst_time' => 'required|numeric|min:1',
            'coordinator_time' => 'required|numeric|min:1',
            'mp_clients_id' => 'required',
        ]);

        $client->fill($request->all());
        if ($client->save()) {
            Alert::success(__('Cliente'), __('Se ha actualizado la información'));
            return redirect()->route('clients.edit', $client->id);
        }

        Alert::warning(__('Cliente'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if ($client->delete()) {
            Alert::success(__('Cliente'), __('Se ha eliminado el registro'));
            return redirect()->route('clients.index');
        }
        Alert::warning(__('Cliente'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * get all Users the specified Client.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request, $id)
    {
        if($request->ajax()){
            $users = User::where([['clients_id',$id],['roles_id',5]])->get();
            $clients_country = ClientsCountry::select('countries_id')->where('clients_id',$id)->get();
            $country = Country::whereIn('id',$clients_country)->get();
            return response()->json(['users'=>$users,'country'=>$country]);
        }
    }

    public function taskSave(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $registers = Register::where('clients_id',$request->client_id)->whereNotIn('states_id',[2,4,6])->get();

            $checked = isset($request->checkbox_id) ? $request->checkbox_id : [];

            foreach ($request->stage_task_id as $key => $stage_task_id) {
                $client_task = ClientTask::where([
                    ['clients_id',$request->client_id],
                    ['stage_tasks_id',$stage_task_id]
                ])->first();

                $ans = (isset($request->ans_time[$key]) && is_numeric($request->ans_time[$key]) && $request->ans_time[$key] > 0)? $request->ans_time[$key] : 0;

                if(isset($client_task)){ // Si existe
                    // Se actualiza
                    if(in_array($stage_task_id, $checked)){ // Si la tarea se marco
                        // Actualizamos ANS time
                        $client_task->ans_time = $ans;
                        $client_task->save();
                    }else{ // Si no se marco
                        // Si se puede eliminar
                        $stage_task = StageTask::find($stage_task_id);
                        if($stage_task->visible == 'S'){ // Se elimina
                            if (count($registers)>0) {
                                Alert::warning(__('Cliente'), __('No se puede eliminar una tarea del Flujo de Proceso, existen solicitudes pendientes por gestionar'))->persistent('Close');
                                return redirect()->back();
                            }
                            $client_task->delete();
                        }else{ // Se actualiza ANS time
                            $client_task->ans_time = $ans;
                            $client_task->save();
                        }
                    }
                }else{ // Si no existe
                    if(in_array($stage_task_id, $checked)){ // Si la tarea se marco
                        if (count($registers)>0) {
                            Alert::warning(__('Cliente'), __('No se puede seleccionar una tarea del Flujo de Proceso, existen solicitudes pendientes por gestionar'))->persistent('Close');
                            return redirect()->back();
                        }
                        $client_task_new = new ClientTask();
                        $client_task_new->clients_id = $request->client_id;
                        $client_task_new->stage_tasks_id = $stage_task_id;
                        $client_task_new->created_users_id = Auth::id();
                        $client_task_new->ans_time = $ans;
                        $client_task_new->save();
                    }
                }
            }
            DB::commit();
            Alert::success(__('Cliente'), __('Se ha registrado la información'))->persistent('Close');
            return redirect()->back();

        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en actualizar el flujo de procesos del cliente',
                "REQUEST: ".$request->client_id."\nEXCEPTION: ".$exception->getMessage()
            ));
            // dd($exception->getMessage());
            Alert::warning(__('Cliente'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
            return redirect()->back();
        }

        Alert::success(__('Cliente'), __('Se ha registrado la información'))->persistent('Close');
        return redirect()->back();
    }
}
