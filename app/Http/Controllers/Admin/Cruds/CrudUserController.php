<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Stage;
use App\Models\AnalystTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Role;
use App\Models\Client;


class CrudUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::paginate(10);
        $roles = Role::all()->pluck('name','id')->toArray();

        return view('admin.cruds.users.index', compact('users','roles'));
    }

    public function search(Request $request)
    {

        $users = User::select('users.*')
        ->addClient()
        ->addRol()
        ->when($request->name, function ($query) use ($request){
            return $query->where('users.name','like','%'.$request->name.'%');
        })
        ->when($request->email, function ($query) use ($request){
            return $query->where('users.email','like','%'.$request->email.'%');
        })
        ->when($request->roles_id, function ($query) use ($request){
            return $query->where('users.roles_id',$request->roles_id);
        })
        ->when($request->client, function ($query) use ($request){
            return $query->where('clients.name','like','%'.$request->client.'%');
        })
        ->when($request->orderBy, function ($query) use ($request){
            if($request->order != 'ASC' && $request->order != 'DESC'){
                $request->order = 'ASC';
            }

            $newOrderBy = '';
            switch ($request->orderBy) {
                case 'name':
                    $newOrderBy = 'users.name';
                    break;
                case 'email':
                    $newOrderBy = 'users.email';
                    break;
                case 'rol':
                    $newOrderBy = 'roles.name';
                    break;
                case 'client':
                    $newOrderBy = 'clients.name';
                    break;
                default:
                    $newOrderBy = 'users.created_at';
                    break;
            }
            return $query->orderBy($newOrderBy,$request->order);

        })
        ->paginate(10);

        $roles = Role::all()->pluck('name','id')->toArray();
        $data = $request;

        return view('admin.cruds.users.index', compact('users','roles','data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($clients_id = null)
    {

        $clients = Client::pluck('name', 'id');
        $roles = Role::pluck('name', 'id');
        return view('admin.cruds.users.create', compact('clients_id','clients','roles'));
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
            'username' => 'required',
            'password' => 'required',
            'clients_id' => 'required',
            'roles_id' => 'required',
        ]);

        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($user->password);
        if ($user->save()) {
            Alert::success(__('Usuario'), __('Se ha registrado la información'));
            return redirect()->route('users.edit', $user->id);
        }
        Alert::warning(__('Usuario'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data = $user;
        $user_permissions = UserPermission::select('permissions_id')->where('users_id',$user->id)->get();

        if($user->roles_id!=2){
            $permissions = Permission::whereNotIn('name',['request_provider','ticket_status_report'])->whereNotIn('id',$user_permissions)->pluck('label', 'id');
        }else{
            $permissions = Permission::whereNotIn('id',$user_permissions)->pluck('label', 'id');
        }
        $stages = Stage::whereIn('id',[4,5,7])->get(); // Gestión documental, verificación y calidad
        $clients = Client::pluck('name', 'id');
        $roles = Role::pluck('name', 'id');

        return view('admin.cruds.users.edit', compact('data', 'permissions','stages','clients','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'clients_id' => 'required',
            'roles_id' => 'required',
        ]);

        $user->fill($request->all());
        if ($user->save()) {
            Alert::success(__('Usuario'), __('Se ha actualizado la información'));
            return redirect()->route('users.edit', $user->id);
        }

        Alert::warning(__('Usuario'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            Alert::success(__('Usuario'), __('Se ha eliminado el registro'));
            return redirect()->route('users.index');
        }
        Alert::warning(__('Usuario'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    public function addPermissions(Request $request)
    {
        $validated = $request->validate([
            'permissions_id' => 'required',
            'users_id' => 'required',
        ]);
        $userPermission = UserPermission::firstOrNew(['users_id' => $request->users_id, 'permissions_id' => $request->permissions_id]);
        $userPermission->fill($request->all());
        $userPermission->created_users_id = Auth::id();
        if ($userPermission->save()) {
            Alert::success(__('Permisos'), __('Los permisos del usuario han sido actualizados'));
        }else{
            Alert::warning(__('Permisos'), __('Ha surgido un error, por favor intente nuevamente'));
        }
        return redirect()->back();
    }

    public function destroyPermissions(UserPermission $permission){
        if ($permission->delete()) {
            Alert::success(__('Permisos'), __('Se ha eliminado el permiso'));
        }else{
            Alert::warning(__('Permisos'), __('Ha surgido un error, por favor intente nuevamente'));
        }
        return redirect()->back();
    }

    public function taskSave(Request $request)
    {
        if($request->ajax()){

            $task = AnalystTask::where([
                ['analyst_id',$request->user_id],
                ['stage_tasks_id',$request->task_id],
            ])->first();

            if($request->option=='true'){

                if(isset($task)){
                    return response()->json(['status'=>200,'msg'=>__('Se ha registrado la información'), 'title'=>__('Asociar Tarea')]);
                }else{
                    $task = new AnalystTask();
                    $task->analyst_id = $request->user_id;
                    $task->stage_tasks_id = $request->task_id;
                    $task->created_users_id = Auth::id();

                    if($task->save()){ //+1
                        return response()->json(['status'=>200,'msg'=>__('Se ha registrado la información'), 'title'=>__('Asociar Tarea'),'number'=>1]);
                    }
                }
                return response()->json(['status'=>500,'msg'=>__('Ha surgido un error, por favor intente nuevamente'), 'title'=>__('Asociar Tarea')]);
            }else{
                if(isset($task)){
                    if($task->delete()){ //-1
                        return response()->json(['status'=>200,'msg'=>__('Se ha registrado la información'), 'title'=>__('Asociar Tarea'),'number'=>-1]);
                    }
                    return response()->json(['status'=>500,'msg'=>__('Ha surgido un error, por favor intente nuevamente'), 'title'=>__('Asociar Tarea')]);
                }
                return response()->json(['status'=>200,'msg'=>__('Se ha registrado la información'), 'title'=>__('Asociar Tarea')]);
            }
        }
    }
}
