@if(isset($data))
    {{Form::open(['route' => ['clients.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['clients.store']])}}
@endif
<div class="form-group">
    <label for="mp_clients_id">{{__('Asociar cliente Mi Proveedor')}}</label>
    {{Form::select("mp_clients_id",$mp_clients, isset($data)?$data->mp_clients_id:null,
    ['class' => "custom-select select2", 'id'=>'mp_clients_id','placeholder' => 'Seleccione...','required'])}}
</div>
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="acronym">{{__('Acrónimo')}}</label>
    {{Form::text("acronym", isset($data)?$data->acronym:"",
    ['class' => "form-control", 'id'=>'acronym', 'required', 'minlength' => 3, 'maxlength' => 3])}}
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
    <label for="contact_person">{{__('Nombre de la persona de contacto')}}</label>
    {{Form::text("contact_person", isset($data)?$data->contact_person:"", ['class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="interval_gd">{{__('Intervalo de seguimientos en gestión documental')}}</label>
    {{Form::number("interval_gd", isset($data)?$data->interval_gd:"", ['class' => "form-control", 'required', 'placeholder'=>__('En días')])}}
</div>
<div class="form-group">
    <label for="interval_sub">{{__('Intervalo de seguimientos en subsanación')}}</label>
    {{Form::number("interval_sub", isset($data)?$data->interval_sub:"", ['class' => "form-control", 'required', 'placeholder'=>__('En días')])}}
</div>
<div class="form-group">
    <label for="days_waiting">{{__('Máxima espera para solicitudes escaladas')}}</label>
    {{Form::number("days_waiting", $data->days_waiting??"", ['class' => "form-control", 'required', 'placeholder'=>__('En días')])}}
</div>

{{-- Notificación de tareas --}}
<div class="form-group">
    <label for="analyst_time">{{__('Máxima espera para notificar tareas pendientes al analista')}}</label>
    {{Form::number("analyst_time", $data->analyst_time??"", ['class' => "form-control", 'required', 'placeholder'=>__('En horas')])}}
</div>
<div class="form-group">
    <label for="coordinator_time">{{__('Máxima espera para notificar tareas atrasadas al coordinador')}}</label>
    {{Form::number("coordinator_time", $data->coordinator_time??"", ['class' => "form-control", 'required', 'placeholder'=>__('En horas')])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')

@section('scripts')
    @parent
    {!! Html::script('assets/js/upper_case.js') !!}
@endsection
