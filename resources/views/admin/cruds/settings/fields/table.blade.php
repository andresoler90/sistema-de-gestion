<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>{{__('Nombre')}}</th>
        <th>{{__('Descripción')}}</th>
        <th>{{__('Valor por defecto')}}</th>
        <th style="width: 250px">{{__('Opciones')}}</th>
    </tr>
    </thead>
    <tbody>
    @if(count($settingFields))
        @foreach($settingFields as $field)
            <tr>
                <td>{{$field->name}}</td>
                <td>{{$field->description}}</td>
                <td>{!! $field->switchStatus() !!}</td>
                <td>
                    <a href="#" title="Editar" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#campsModal_{{$field->id}}">
                        <i class="far fa-edit" aria-hidden="true"></i>
                    </a>
                    @include('admin.cruds.settings.fields.modal')
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text-center">
                {{__("No hay registros aún")}}
            </td>
        </tr>
    @endif
    </tbody>
</table>

