<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">{{$title}} <span
                            class="badge badge-cobalt-blue">{{count($settingClients)}}</span></h4>
                </div>
                @if($created)
                    <div class="iq-card-header-toolbar">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#campOfClientModal">
                            {{__('Crear')}}
                        </button>
                    </div>
                    @include('admin.cruds.settings.clients.modal')
                @else
                    <div class="iq-card-header-toolbar">
                        <button type="button" class="btn btn-primary btn-sm btn-40px" title="{{ __('Guardar') }}" id="saveForm">
                            <i class="fas fa-save icon-perfect"></i>
                        </button>
                    </div>
                @endif
            </div>
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Nombre Campo')}}</th>
                        <th>{{ $created ? __('Cliente') : __('Descripción')}}</th>
                        <th>{{__('Estado')}}</th>
                    </tr>
                    </thead>
                    {{ Form::open(['route' => ['settings.field_client.update'], 'method' => 'POST', 'id' => 'form_camp_client']) }}
                    <tbody>
                    @if(count($settingClients))
                    @foreach($settingClients as $settingC)
                        {{ Form::hidden('hidden_status_'.$settingC->id,$settingC->status) }}
                        {{ Form::hidden('hidden_id[]',$settingC->id) }}

                        <tr>
                            <td> {{ $settingC->field->name }} </td>
                            <td>
                                @if($created)
                                    {{ $settingC->client->name }}
                                @else
                                    {{ $settingC->field->description }}
                                @endif
                            </td>
                            <td>
                                {!! $settingC->status($edit) !!}
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">
                                {{__("No hay registros aún")}}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    {{ Form::close() }}
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    @parent
    <script>
        function validateStatus(id)
        {
            let setting_client = "setting_client_"+id;
            let status =  "status_"+id;
            let hidden_status = "hidden_status_"+id;

            if( $('#'+setting_client).is(':checked'))
            {
                $('#'+status).text('Activo');
                $('#'+setting_client).val(1);
                $('input[name="'+hidden_status+'"]').val(1)

            }else
            {
                $('#'+status).text('Inactivo');
                $('#'+setting_client).val(0).prop('checked',false);
                $('input[name="'+hidden_status+'"]').val(0)
            }
        }
        $('#saveForm').click(function (){
            $( "#form_camp_client" ).submit();
        });
    </script>
@endsection
