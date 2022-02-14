@if(isset($data))
    {{Form::open(['route' => ['configurable.words.clients.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['configurable.words.clients.store']])}}
@endif
{{Form::hidden("clients_id", isset($data)?$data->clients_id:$clients_id)}}
<div class="form-group">
    <label for="configurable_words_id">{{__('Palabra')}}</label>
        {{Form::select("configurable_words_id",$words, isset($data)?$data->configurable_words_id:null,
        ['class' => "custom-select", 'id'=>'configurable_words_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['id'=>'name', 'class' => "form-control",'required'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
