@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('analyst.tasks.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            @include('analyst.partials.register_detail_card')
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__($register_event->description)}}
                                    <span class="badge badge-cobalt-blue">
                                        {{ count($client_documents) }}
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['task.retrieval.store'],'id'=>'form-retrieval'])}}
                            {{Form::hidden("register_tasks_id",$register_task->id)}}
                            {{Form::hidden("continue",'N',['id'=>'continue'])}}
                            {{Form::hidden("next_task",'',['id'=>'next_task'])}}
                            <div class="row table-wrapper-scroll-y my-custom-scrollbar-auto">
                                <table class="table table-striped table-bordered fixed-head d-table">
                                    <thead>
                                        <tr>
                                            <th>{{__('Documento')}}</th>
                                            <th>{{__('Tipo')}}</th>
                                            <th>{{__('Vigencia')}}</th>
                                            <th>{{__('¿Cumple?')}}</th>
                                            <th>{{__('Resultado de verificación')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($client_documents as $document)
                                        {{Form::hidden("client_documents_id[]",$document->id,['class'=>'document'])}}
                                        <tr>
                                            <td>
                                                {{ $document->document->name }}
                                                @if($document->commentary)
                                                    <span class="d-block">{{ $document->commentary }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($document->document_type == 'OB')
                                                    <span class="badge border border-success text-success">{{ $document->document_type_name }}</span>
                                                @else
                                                    <span class="badge border border-warning text-warning">{{ $document->document_type_name }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $document->validity }}</td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-text custom-control-inline">
                                                    <div class="custom-switch-inner">
                                                    <input type="checkbox" name="valid[]" class="custom-control-input satisfy" id="satisfy-{{ $loop->index }}" value="{{ $document->id }}">
                                                    <label class="custom-control-label" for="satisfy-{{ $loop->index }}" data-on-label="Si" data-off-label="No">
                                                    </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="outcome[]" class="form-control outcome">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-3">
                                {{Form::hidden("registers_id",$register->id)}}
                                {{Form::hidden("register_event_id",$register_event->id)}}
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Seguimiento')}}
                                    </legend>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-row mb-1">
                                                <label for="date" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Fecha de seguimiento')}}:
                                                </label>
                                                {{Form::date("date", $data->date?? date('Y-m-d'), ['id'=>'date', 'class' => "form-control col-sm-7 col-md-8", 'required'])}}
                                            </div>
                                            <div class="form-row mb-1">
                                                <label for="contact_name" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Nombre de contacto')}}:
                                                </label>
                                                {{Form::text("contact_name",$data->contact_name??'', ['id'=>'contact_name', 'class' => "form-control col-sm-7 col-md-8", 'required'])}}
                                            </div>
                                            <div class="form-row mb-1">
                                                <label for="phone" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Teléfono')}}:
                                                </label>
                                                {{Form::text("phone",$data->phone??'', ['id'=>'phone', 'class' => "form-control col-sm-7 col-md-8", 'maxlength'=>255])}}
                                            </div>
                                            <div class="form-row mb-1">
                                                <label for="email" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Correo')}}:
                                                </label>
                                                {{Form::text("email",$data->email??'', ['id'=>'email', 'class' => "form-control col-sm-7 col-md-8", 'maxlength'=>255])}}
                                            </div>
                                            <div class="form-row mb-1">
                                                <label for="type_contact" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Tipo de contacto')}}:
                                                </label>
                                                {{Form::select("type_contact",Config::get('options.management_type'),$data->type_contact??null,
                                                ['class' => "form-control col-sm-8", 'id'=>'type_contact','placeholder' =>  __('Seleccione ...')])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="observations">{{__('Observaciones')}}:</label>
                                                {{Form::textarea("observations",$data->observations??'', ['id'=>'observations', 'class' => "form-control", 'required', 'rows'=>'3'])}}
                                            </div>
                                            <div class="form-row">
                                                <label for="consecutive_code" class="col-sm-5 col-md-4 col-form-label">
                                                    {{__('Código consecutivo')}}:
                                                </label>
                                                {{Form::text("consecutive_code",$data->consecutive_code??'', ['id'=>'consecutive_code', 'class' => "form-control col-sm-7 col-md-8"])}}
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <button type="button" class="btn btn-primary mt-2 btn-question">{{__('Guardar')}}</button>
                            {{Form::close()}}
                            @include('partials._app_errors')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-body">
                            <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                               <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        {{ __('Tiquetes relacionados') }}
                                    </a>
                               </li>
                               <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                        {{ __('Histórico de seguimiento') }}
                                    </a>
                               </li>
                            </ul>
                            <div class="tab-content" id="myTabContent-2">
                               <div class="tab-pane fade active show table-wrapper-scroll-y my-custom-scrollbar-200" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-striped table-bordered fixed-head d-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('N° de Tiquete')}}</th>
                                        <th>{{__('Cliente Ancla')}}</th>
                                        <th>{{__('Tipo de registro')}}</th>
                                        <th>{{__('Tipo de proveedor')}}</th>
                                        <th>{{__('Prioridad')}}</th>
                                        <th>{{__('Etapa')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="registers" data-registers = {{ count($my_registers) }}>
                                        @foreach ($my_registers as $register)
                                            <tr id="{{ $register->id }}">
                                                <td>
                                                    <a href="{{route(config('tasks-routes.'.$register->task->name),$register->id)}}"
                                                        class="btn btn-sm btn-link">
                                                        {{ $register->register->code }}
                                                    </a>
                                                </td>
                                                <td>{{ $register->register->client->name }}</td>
                                                <td>
                                                    {{ words(Config::get("options.register_type.".$register->register->register_type)) }}
                                                </td>
                                                <td>{{ $register->register->provider_type_name }}</td>
                                                <td>{{ $register->priority_name }}</td>
                                                <td>{{ $register->register->stage->name }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach ($other_registers as $register)
                                            <tr>
                                                <td>{{ $register->register->code }}</td>
                                                <td>{{ $register->register->client->name }}</td>
                                                <td>
                                                    {{ words(Config::get("options.register_type.".$register->register->register_type)) }}
                                                </td>
                                                <td>{{ $register->register->provider_type_name }}</td>
                                                <td>{{ $register->priority_name }}</td>
                                                <td>{{ $register->register->stage->name }}</td>
                                            </tr>
                                        @endforeach
                                        @if(count($my_registers) == 0 && count($other_registers) == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    {{__("No hay registros aún")}}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                               </div>
                               <div class="tab-pane fade table-wrapper-scroll-y my-custom-scrollbar-200" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                                                        {{ __('Seguimiento') }} {{ $loop->count - $loop->index}}
                                                    </button>
                                                </td>
                                                <td>{{ isset($tracking->date)?$tracking->date->format('d/m/Y'):$tracking->created_at->format('d/m/Y') }}</td>
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
    $('.btn-question').on('click', function() {

        let registers = $('#registers').data('registers');

        if(registers > 0){ // Si puede realizar más tareas de ese tipo
            Swal.fire({
                title: 'Gestión',
                icon: 'question',
                text: "¿Después de terminar esta tarea desea continuar gestionando otra tarea similar?",
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#continue').val('S');
                    let task = $('#registers tr:first-child').attr('id');
                    $('#next_task').val(task);
                }
                $('#form-retrieval').submit();
            })
        }else{
            $('#form-retrieval').submit();
        }
    });
</script>

@endsection
