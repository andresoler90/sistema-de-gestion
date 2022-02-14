@if(isset($data))
    {{Form::open(['route' => ['documents.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['documents.store']])}}
@endif
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['id'=>'name', 'class' => "form-control",'required'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
