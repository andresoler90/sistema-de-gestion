@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('registers.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Ver solicitud')}}</h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <h4 class="card-title text-primary">{{ $data->code }}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('client.register.partial.form',compact('data'))
                        </div>
                    </div>
                </div>
            </div>
            @if(count($trackings_gd))
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="iq-card">
                            <div class="iq-card-body">
                                <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                                <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                            {{ __('Seguimientos - gestión documental') }}
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
                                            @foreach ($trackings_gd as $tracking)
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
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($trackings_sub))
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="iq-card">
                            <div class="iq-card-body">
                                <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                                <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                            {{ __('Seguimientos - subsanación') }}
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
                                            @foreach ($trackings_sub as $tracking)
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-link show-tracking" data-tracking='{{ $tracking->id }}'>
                                                            {{ $loop->count - $loop->index}} - {{ $tracking->stage_tasks->label }}
                                                        </button>
                                                    </td>
                                                    <td>{{ isset($tracking->date)?$tracking->date->format('d/m/Y'):$tracking->created_at->format('d/m/Y') }}</td>
                                                    <td>{{ $tracking->observations }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
@endsection
