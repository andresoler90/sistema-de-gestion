@if(isset($data))
    {{Form::open(['route' => ['tasks.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['tasks.store']])}}
@endif
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['id'=>'name', 'class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="label">{{__('Label')}}</label>
    {{Form::text("label", isset($data)?$data->label:"", ['id'=>'label', 'class' => "form-control", 'required'])}}
</div>
<div class="form-group">
    <label for="stages_id">{{__('Etapa')}}</label>
        {{Form::select("stages_id",$stages, isset($data)?$data->stages_id:null,
        ['class' => "custom-select", 'id'=>'stages_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="estimated_time">{{__('Tiempo Estimado')}}</label>
    {{Form::number("estimated_time", isset($data)?$data->estimated_time:"",
    ['id'=>'estimated_time','class' => "form-control", 'step' => '0.01', 'required'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
