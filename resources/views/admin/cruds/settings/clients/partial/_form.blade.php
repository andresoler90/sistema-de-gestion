{{ Form::open(['route' => ['settings.field_client.save'], 'method' => 'POST', 'id' => 'form_camp_']) }}

<div class="modal-body">
    <div class="form-group">
        <label for="setting_field_id" class="col-form-label">{{__('Campo')}}</label>
        {{ Form::select('setting_field_id',$fields,null,['class' => 'form-control', 'id' => 'setting_field_id', 'required','placeholder'=>'Seleccione...']) }}
    </div>
    <div class="form-group">
        <label for="client_id" class="col-form-label">{{__('Cliente')}}</label>
        {{ Form::select('client_id',$clients,null,['class' => 'form-control', 'id' => 'client_id', 'required','placeholder'=>'Seleccione...']) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancelar')}}</button>
    <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
</div>
{{ Form::close() }}
