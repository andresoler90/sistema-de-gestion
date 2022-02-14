@extends('layouts.sgpar')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('reports.request.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col">
                            <div class="iq-card iq-card-block iq-card-stretch">
                                <div class="iq-card-body">
                                    <div
                                        class="bg-cobalt-blue p-3 rounded d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="text-white">{{__('SOLICITUD - ').$register->code}} </h5>
                                        <div class="rounded-circle iq-card-icon bg-white">
                                            <i class="ri-layout-line text-cobalt-blue"></i>
                                        </div>
                                    </div>
                                    <h4 class="mb-2">{{$register->business_name}}</h4>
                                    <div class="row align-items-center justify-content-between mt-3">
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Tipo de solicitud')}}</p>
                                            <h6>{{ words(Config::get("options.register_type.$register->register_type")) }}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Estado')}}</p>
                                            <h6>{{$register->state->name}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('País')}}</p>
                                            <h6>{{$register->country->name}}</h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-between mt-3">
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Identificación')}}</p>
                                            <h6>{{$register->identification_type.' '.$register->identification_number}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Telefono')}}</p>
                                            <h6>{{$register->telephone_contact}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Contacto')}}</p>
                                            <h6>{{$register->name_contact}}</h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-between mt-3">
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Email')}}</p>
                                            <h6>{{$register->email_contact}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Responsable del pago')}}</p>
                                            <h6>{{$register->assumed_by_name}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Cliente')}}</p>
                                            <h6>
                                                {{$register->client->name}}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-between mt-3">
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Solicitado por')}}</p>
                                            <h6>{{$register->user->name}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Creado por')}}</p>
                                            <h6>{{$register->creatorUser->name}}</h6>
                                        </div>
                                        <div class="col-sm-4">
                                            <p class="mb-0">{{__('Fecha de creación')}}</p>
                                            <h6>
                                                {{$register->created_at}}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-between mt-3">
                                        <div class="col">
                                            <span class="d-block">
                                                {{ __('Etapa actual') }} : {{ $register->stage->name }}
                                            </span>
                                            <span class="d-block">
                                                {{ __('Porcentaje de avance') }} : {{ $register->previous_stage->accumulated }}%
                                            </span>
                                            <div class="progress">
                                                @foreach ( $stages as $stage)
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated pointer bg-{{ $stage->id }}"
                                                        role="progressbar"
                                                        style="max-width: {{ $stage->percentage }}%; min-width: {{ $stage->percentage }}%;"
                                                        aria-valuenow="{{ $stage->percentage }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        title="{{ $stage->name }} &#013;{{ __('% de avance que representa') }} : {{ $stage->percentage }}%"
                                                        >
                                                        @if($register->previous_stage->id == $stage->id)
                                                            {{ $stage->accumulated }}%
                                                        @endif
                                                    </div>
                                                    @if($register->previous_stage->id == $stage->id)
                                                        @break
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($trackings)) {{-- Seguimientos en todas las etapas --}}
                        <div class="row">
                            <div class="col">
                                <div class="iq-card iq-card-block iq-card-stretch">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">{{__('Seguimientos')}}</h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body table-responsive">
                                        <div class="table-wrapper-scroll-y my-custom-scrollbar-400">
                                            <table class="table table-striped table-bordered fixed-head d-table">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Etapa')}}</th>
                                                    <th>{{__('Fecha')}}</th>
                                                    <th>{{__('Analista')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($trackings as $tracking)
                                                        <tr>
                                                            <td>
                                                                <button type="button" class="btn btn-link text-left {{ $tracking->class }}"
                                                                    data-tracking='{{ $tracking->id }}' data-title = "{{ $tracking->stage_name }}">
                                                                    {{ $tracking->stage_name }}
                                                                </button>
                                                            </td>
                                                            <td>{{ $tracking->created_at->format('d/m/Y H:i:s') }}</td>
                                                            <td>{{ $tracking->analyst }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col">
                            <div class="iq-card iq-card-block iq-card-stretch">
                                <div class="iq-card-body">
                                    <figure class="highcharts-figure">
                                        <div id="chart_column"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @foreach($register->tasks as $task)
                        <div class="iq-card dash-hover-gradient iq-card-block">
                            <div class="iq-card-header d-flex justify-content-between border-0">
                                <h4 class="card-title">{{$task->task->label}}
                                    @php
                                        $color = 'primary';
                                        if($task->status == 'A'){
                                            $color = 'danger';
                                        }else if($task->status == 'C'){
                                            $color = 'success';
                                        }
                                    @endphp
                                    <i class="badge badge-{{ $color }}">{{$task->status_name}}</i>
                                </h4>
                                {{-- Se comenta por ahora ya que no tiene ninguna funcionalidad --}}
                                {{-- <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="d-28" data-toggle="dropdown">
                                            <i class="ri-more-2-fill "></i>
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-right shadow-none"
                                             aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                            <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                            <a class="dropdown-item" href="#"><i
                                                    class="ri-pencil-fill mr-2"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                            <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="iq-card-body pt-0">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-sm-4">
                                        <p class="mb-0">{{__('Prioridad')}}</p>
                                        <h6>{{$task->priority_name}}</h6>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="mb-0">{{__('Inicio')}}</p>
                                        <h6>{{$task->start_date}}</h6>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="mb-0">{{__('Culminación')}}</p>
                                        <h6>{{$task->end_date??__('En proceso')}}</h6>
                                    </div>
                                </div>
                                @if(count($task->events))
                                    <div class="accordion mt-3" id="accordionTask_{{$task->id}}">
                                        <div class="card">
                                            <div class="card-header pointer" id="headingOne" data-toggle="collapse"
                                                 data-target="#collapseOne_{{$task->id}}"
                                                 aria-expanded="true"
                                                 aria-controls="collapseOne_{{$task->id}}">
                                                <h5 class="mb-0  bg-blue">
                                                    {{__('Eventos')}}
                                                    <span class="badge badge-primary">
                                                        {{count($task->events)}}
                                                    </span>
                                                </h5>
                                            </div>
                                            <div id="collapseOne_{{$task->id}}" class="collapse"
                                                 aria-labelledby="headingOne"
                                                 data-parent="#accordionTask_{{$task->id}}">
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('Description')}}</th>
                                                            <th>{{__('Inicio')}}</th>
                                                            <th>{{__('Fin')}}</th>
                                                            <th>{{__('Responsable')}}</th>
                                                        </tr>
                                                        </thead>
                                                        @foreach($task->events as $event)
                                                            <tr>
                                                                <td>
                                                                    @if(json_decode($event->description))
                                                                        @foreach(json_decode($event->description) as $value)
                                                                            {{$value}}
                                                                            <br><br>
                                                                        @endforeach
                                                                    @else
                                                                        {{$event->description}}
                                                                    @endif
                                                                </td>
                                                                <td>{{$event->created_at}}</td>
                                                                <td>{{$event->finished_at}}</td>
                                                                <td>{{$event->management_name}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-action font-size-14 p-3">
                                <span class="float-right">
                                    @if($task->analyst)
                                        @if(Auth::user()->roles_id==1)
                                            <a href="{{route('users.edit',$task->analyst_users_id)}}">{{$task->analyst->name}}</a>
                                        @else
                                            {{$task->analyst->name}}
                                        @endif
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('analyst.modals.show_tracking')
    @include('analyst.modals.show_tracking_verification')
    @include('analyst.modals.show_tracking_quality')
    @include('analyst.modals.show_tracking_salesforce')

@endsection

@section('scripts')
    @parent

    <script>
        var par = {!! json_encode($par) !!};
        var pro = {!! json_encode($pro) !!};
        var cli = {!! json_encode($cli) !!};
    </script>
    {!! Html::script('assets/js/registers/detail_column_chart.js') !!}

    <script>
        var token = '{{ csrf_token() }}';
        var ruta = "{{ route('task.document.get.tracking') }}";
        var ruta_verification = "{{ route('verification.get.tracking') }}";
        var ruta_quality = "{{ route('quality.get.tracking') }}";
    </script>

    <script>
        $('.show-tracking').on('click', function() {
            let datos = new FormData();
            let tracking_id = $(this).data('tracking');
            let title = $(this).data('title');
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

                        $('#modalTrackingTitle').text(title);
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
        $('.show-tracking-ve').on('click', function() {
            let datos = new FormData();
            let task = $(this).data('tracking');
            let title = $(this).data('title');
            datos.append('register_tasks_id', task);
            datos.append('_token', token);
            $.ajax({
                type: 'POST',
                url: ruta_verification,
                dataType: 'json',
                timeout: 10000,
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200) {
                        let documents = data.documents;
                        let table = '';

                        $.each(documents, function(key, val) {
                            let document_type='';
                            let commentary='';
                            if(val.document_type == 'OB'){
                                document_type= `<span class="badge border border-success text-success">Obligatorio</span>`
                            }else{
                                document_type= `<span class="badge border border-warning text-warning">Opcional</span>`
                            }
                            if(val.commentary!=null){
                                commentary = `<span class="d-block">${val.commentary}</span>`
                            }
                            table+=`
                            <tr>
                                <td>
                                    ${val.name}
                                    ${commentary}
                                </td>
                                <td>${document_type}</td>
                                <td>${val.validity??''}</td>
                                <td>${val.satisfy=='S'?'<span class="badge bg-primary text-white">Si</span>':'<span class="badge bg-secondary text-white">No</span>'}</td>
                                <td>${val.outcome??''}</td>
                            </tr>`;
                        })

                        $('#modalVerificationTitle').text(title);
                        $('#modal_table_tracking_verification').html(table);
                        $('#tracking_verification').modal('show');

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
        $('.show-tracking-ca').on('click', function() {
            let datos = new FormData();
            let task = $(this).data('tracking');
            let title = $(this).data('title');
            datos.append('register_tasks_id', task);
            datos.append('_token', token);

            $.ajax({
                type: 'POST',
                url: ruta_quality,
                dataType: 'json',
                timeout: 10000,
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200) {
                        let documents = data.documents;
                        let table = '';

                        $.each(documents, function(key, val) {
                            let document_type='';
                            let commentary='';
                            if(val.document_type == 'OB'){
                                document_type= `<span class="badge border border-success text-success">Obligatorio</span>`
                            }else{
                                document_type= `<span class="badge border border-warning text-warning">Opcional</span>`
                            }
                            if(val.commentary!=null){
                                commentary = `<span class="d-block">${val.commentary}</span>`
                            }
                            table+=`
                            <tr>
                                <td>
                                    ${val.name}
                                    ${commentary}
                                </td>
                                <td>${document_type}</td>
                                <td>${val.validity??''}</td>
                                <td>${val.satisfy=='S'?'<span class="badge bg-primary text-white">Si</span>':'<span class="badge bg-secondary text-white">No</span>'}</td>
                                <td>${val.fingering_review=='S'?'<span class="badge bg-primary text-white">Si</span>':'<span class="badge bg-secondary text-white">No</span>'}</td>
                                <td>${val.comments??''}</td>
                            </tr>`;
                        })

                        $('#modalQualityTitle').text(title);
                        $('#modal_table_tracking_quality').html(table);
                        $('#tracking_quality').modal('show');

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

        $('.show-tracking-salesforce').on('click', function() {
            $('#tracking_salesforce').modal('show');
        });

    </script>
@endsection

