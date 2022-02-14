@if(isset($data))
    {{Form::open(['route' => ['configurable.words.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['configurable.words.store']])}}
@endif
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"",
    ['id'=>'name', 'class' => "form-control",'required','placeholder'=>'Nombre interno de la palabra', 'readonly'])}}
</div>
<div class="form-group">
    <label for="label">{{__('Label')}}</label>
    {{Form::text("label", isset($data)?$data->label:"", ['id'=>'label', 'class' => "form-control",'required','placeholder'=>'Nombre que se muestra al usuario'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
