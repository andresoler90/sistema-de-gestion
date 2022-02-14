@if(isset($data))
    {{Form::open(['route' => ['client.document.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['client.document.store']])}}
@endif
{{Form::hidden("clients_id", isset($data)?$data->clients_id:$clients_id)}}
<div class="form-group">
    <label for="documents_id">{{__('Documento')}}</label>
        {{Form::select("documents_id",$documents, isset($data)?$data->documents_id:null,
        ['class' => "custom-select select2", 'id'=>'documents_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="register_type">{{__('Tipo de registro')}}</label>
    <select name="register_type" id="register_type" class="custom-select" required placeholder="{{ __('Seleccione...') }}">
        <option>{{ __('Seleccione...') }}</option>
        @foreach ( Config::get('options.register_type') as $key => $type)
            <option value="{{ $key }}" {{ (isset($data) && $data->register_type ==$key)? 'selected':'' }}>
                {{ words($type) }}
            </option>
        @endforeach
    </select>
        {{-- {{Form::select("register_type",Config::get('options.register_type'), isset($data)?$data->register_type:null,
        ['class' => "custom-select", 'id'=>'register_type', 'required','placeholder' => 'Seleccione...'])}} --}}
</div>
<div class="form-group">
    <label for="provider_type">{{__('Tipo de proveedor')}}</label>
        {{Form::select("provider_type",Config::get('options.provider_type'), isset($data)?$data->provider_type:null,
        ['class' => "custom-select", 'id'=>'provider_type', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="document_type">{{__('Tipo de documento')}}</label>
        {{Form::select("document_type",Config::get('options.document_type'), isset($data)?$data->document_type:null,
        ['class' => "custom-select", 'id'=>'document_type', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="validity">{{__('Vigencia')}}</label>
    {{Form::text("validity", isset($data)?$data->validity:"", ['id'=>'validity', 'class' => "form-control"])}}
</div>
<div class="form-group">
    <label for="commentary">{{__('Comentario')}}</label>
    {{Form::text("commentary", isset($data)?$data->commentary:"", ['id'=>'commentary', 'class' => "form-control"])}}
</div>
<div class="form-group">
    <label for="stage_tasks_id">{{__('Tipo de verificación para el documento')}}</label>
    {{Form::select("stage_tasks_id",$stage_tasks, isset($data)?$data->stage_tasks_id:null,
        ['class' => "custom-select", 'id'=>'stage_tasks_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
