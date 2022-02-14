@if (isset($field))
    @php($camp_id = $field->id)
    {{ Form::open(['route' => ['settings.field.update',$field], 'method' => 'PUT', 'id' => 'form_camp_'.$field->id]) }}
@else
    @php($camp_id = 0)
    {{ Form::open(['route' => ['settings.field.save'], 'method' => 'POST', 'id' => 'form_camp_']) }}
@endif
<div class="modal-body">
    <div class="form-group">
        <label for="name" class="col-form-label">{{__('Nombre')}}</label>
        {{ Form::text('name',@$field->name,['class' => 'form-control', 'id' => 'name', 'required']) }}
    </div>
    <div class="form-group">
        <label for="slug" class="col-form-label">{{__('Slug')}}</label>
        {{ Form::text('slug',@$field->slug,['class' => 'form-control', 'id' => 'slug', 'required', isset($field) ? 'readonly' : null]) }}
    </div>
    <div class="form-group">
        <label for="description" class="col-form-label">{{__('Descripción')}}</label>
        {{ Form::textarea('description',@$field->description,['class' => 'form-control', 'id' => 'description', 'required','rows' => 2]) }}
    </div>
    <div class="form-group">
        <label for="default_value_{{$camp_id}}" class="col-form-label">{{__('Valor por defecto')}}</label>

        <div class="custom-control custom-switch">
            {{ Form::checkbox('default_value',isset($field) ? $field->default_value : 0,isset($field) ? $field->default_value : 0,
            ['class'=> 'custom-control-input', 'id' => 'default_value_'.$camp_id, 'onclick' => 'statusSwitch('.$camp_id.')']) }}
            <label class="custom-control-label" for="default_value_{{$camp_id}}"
                   id="statusSwitch_{{$camp_id}}">
                {{isset($field) && $field->default_value == 1 ? __('Habilitado') : 'Deshabilitado'}}
            </label>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancelar')}}</button>
    <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
</div>
{{ Form::close() }}

@section('scripts')
    @parent
    <script>
        function statusSwitch(id)
        {
            let default_value = "default_value_"+id;
            let statusSwitch =  "statusSwitch_"+id;

            if( $('#'+default_value).is(':checked'))
            {
                $('#'+statusSwitch).text('Habilitado');
                $('#'+default_value).val(1);
            }else
            {
                $('#'+statusSwitch).text('Deshabilitado');
                $('#'+default_value).val(0).prop('checked',false);
            }

        }
    </script>
@endsection
