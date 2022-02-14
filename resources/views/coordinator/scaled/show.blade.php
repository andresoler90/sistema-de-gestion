@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('management.scaled.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            @include('analyst.partials.register_detail_card')
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-body">
                            <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                               <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        {{ __('Histórico de seguimiento') }}
                                    </a>
                               </li>
                            </ul>
                            <div class="tab-content" id="myTabContent-2">
                               <div class="tab-pane fade active show table-wrapper-scroll-y my-custom-scrollbar-200" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-striped table-bordered fixed-head d-table">
                                    <thead>
                                        <tr>
                                            <th>{{__('N°')}}</th>
                                            <th>{{__('Fecha')}}</th>
                                            <th>{{__('Observación')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($trackings))
                                        @foreach ($trackings as $tracking)
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-link show-tracking" data-tracking='{{ $tracking->id }}'>
                                                        @if ($register->stage->id==4)
                                                            {{ __('Seguimiento') }} {{ $loop->count - $loop->index}}
                                                        @else
                                                        {{ $loop->count - $loop->index}} - {{ $tracking->stage_tasks->label }}
                                                        @endif

                                                    </button>
                                                </td>
                                                <td>{{ isset($tracking->date)?$tracking->date->format('d/m/Y'):'' }}</td>
                                                <td>{{ $tracking->observations }}</td>
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
                                </table>
                               </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['management.scaled.store']])}}
                                {{Form::hidden("register_id", $register->id)}}
                                <div class="row mt-3">
                                    <fieldset class="scheduler-border w-100">
                                        <legend class="scheduler-border">
                                            {{__('Gestión')}}
                                        </legend>
                                        <div class="row justify-content-center">
                                            <div class="col">
                                                <h5 class="mb-3">{{ __('Gestión del cliente') }}</h5>
                                                <div class="form-group">
                                                    <label>{{__('Método de contacto')}}:</label>
                                                    @php($type =$management->management_type)
                                                    <span class="fz-13 badge badge-{{ ($type=='T')?'primary':'light' }}">{{ __('Teléfono') }}</span>
                                                    <span class="fz-13 badge badge-{{ ($type=='C')?'primary':'light' }}">{{ __('Correo') }}</span>
                                                    <span class="fz-13 badge badge-{{ ($type=='A')?'primary':'light' }}">{{ __('Ambos') }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="observations">{{__('Observaciones')}}:</label>
                                                    {{Form::textarea('',$management->observations, ['id'=>'observations', 'class' => "form-control", 'readonly', 'rows'=>'3'])}}
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h5 class="mb-3">{{ __('Respuesta') }}</h5>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="answer" id="accept" value="accept">
                                                        <label class="form-check-label" for="accept">{{ __('Aceptar nuevo seguimiento') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="answer" id="deny" value="deny">
                                                        <label class="form-check-label" for="deny">{{ _('Rechazar nuevo seguimiento') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group d-none" id="deny_reason">
                                                    <label for="reason">{{__('Motivo')}}:</label>
                                                    {{Form::textarea("reason",'', ['id'=>'reason', 'class' => "form-control", 'rows'=>'3'])}}
                                                </div>
                                                <div class="alert alert-danger d-none" id="deny_alert">
                                                    <small>
                                                        {{__('Si decide rechazar un nuevo seguimiento la solicitud sera cancelada')}}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
                            {{Form::close()}}
                            @include('partials._app_errors')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('analyst.modals.show_tracking')
@endsection

@section('scripts')
    @parent
    <script>
        var token = '{{ csrf_token() }}';
        var ruta = "{{ route('task.document.get.tracking') }}";

        $('.show-tracking').on('click', function() {
            let datos = new FormData();
            let tracking_id = $(this).data('tracking');
            datos.append('tracking_id', tracking_id);
            datos.append('_token', token);
            $.ajax({
                type: 'POST',
                url: ruta,
                dataType: 'json',
                timeout: 10000,
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200) {
                        let documents = data.documents;
                        let tracking = data.tracking;

                        let date = new Date(Date.parse(tracking.date));
                        let day = date.getDate()
                        day = (day < 10)? `0${day}` : day;
                        let month = date.getMonth() + 1
                        month = (month < 10)? `0${month}` : month;
                        let year = date.getFullYear()

                        let table = '';

                        $.each(documents, function(key, val) {
                            console.log(val);
                            let document_type='';
                            let commentary='';
                            if(val.client_document.document_type == 'OB'){
                                document_type= `<span class="badge border border-success text-success">Obligatorio</span>`
                            }else{
                                document_type= `<span class="badge border border-warning text-warning">Opcional</span>`
                            }
                            if(val.client_document.commentary!=null){
                                commentary = `<span class="d-block">${val.client_document.commentary}</span>`
                            }
                            table+=`
                            <tr>
                                <td>
                                    ${val.client_document.document.name}
                                    ${commentary}
                                </td>
                                <td>${document_type}</td>
                                <td>${val.client_document.validity??''}</td>
                                <td>${val.valid=='S'?'<span class="badge bg-primary text-white">Si</span>':'<span class="badge bg-secondary text-white">No</span>'}</td>
                                <td>${val.outcome??''}</td>
                            </tr>`;
                        })

                        let type_contact = tracking.type_contact;
                        if(type_contact =='T'){
                            type_contact = 'Teléfono';
                        }else if(type_contact =='C'){
                            type_contact = 'Correo';
                        }else if(type_contact =='A'){
                            type_contact = 'Ambos';
                        }else{
                            type_contact = '';
                        }

                        $('#modal_table_tracking').html(table);
                        $('#modal_date').val(`${year}-${month}-${day}`);
                        $('#modal_contact_name').val(tracking.contact_name);
                        $('#modal_phone').val(tracking.phone);
                        $('#modal_email').val(tracking.email);
                        $('#modal_type_contact').val(type_contact);
                        $('#modal_observations').val(tracking.observations);
                        $('#modal_consecutive_code').val(tracking.consecutive_code);

                        $('#tracking').modal('show');
                    } else {
                        Swal.fire(data.title, data.msg, 'warning');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(textStatus==="timeout") {
                        Swal.fire('Error', timeError, 'warning');
                    } else {
                        Swal.fire('Error', otherError, 'warning');
                    }
                }
            })
        });
    </script>

    <script>
        $("input[name='answer']").click(function() {
            if($("#deny").is(':checked')) {
                $('#deny_reason').removeClass('d-none');
                $('#deny_alert').removeClass('d-none');
                $('#reason').attr('required',true);
            } else {
                $('#deny_reason').addClass('d-none');
                $('#deny_alert').addClass('d-none');
                $('#reason').attr('required',false);
            }
        });
    </script>
    {{-- {!! Html::script('assets/js/clients/task.js') !!} --}}

@endsection
