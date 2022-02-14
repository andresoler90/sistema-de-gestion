@if(isset($data))
    {{Form::open(['route' => ['users.update',$data->id]])}}
    @method('PUT')
@else
    {{Form::open(['route' => ['users.store']])}}
@endif
@isset($clients_id)
    {{Form::hidden("clients_id", $clients_id)}}
@endisset
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="phone">{{__('Teléfono')}}</label>
    {{Form::text("phone", isset($data)?$data->phone:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="email">{{__('Email')}}</label>
    {{Form::email("email", isset($data)?$data->email:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="username">{{__('Nombre de usuario')}}</label>
    {{Form::text("username", isset($data)?$data->username:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="roles_id">{{__('Rol')}}</label>
    {{Form::select("roles_id",$roles, isset($data)?$data->roles_id:null,
    ['class' => "custom-select", 'id'=>'roles_id','placeholder' => 'Seleccione...','required'])}}
</div>
<div class="form-group">
    <label for="">{{__('Cliente ancla')}}</label>
    {{Form::select("clients_id",$clients, isset($data)?$data->clients_id:null,
    ['class' => "custom-select select2", 'id'=>'clients_id','placeholder' => 'Seleccione...','required'])}}
</div>
@if(!isset($data))
    <div class="form-group">
        <label for="email">{{__('Clave')}}</label>
        {{Form::password("password",  ['class' => "form-control", 'required'])}}
    </div>
@endif

<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
