@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__('Gestión')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.management.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.management.search'],'id'=>'form-filter'])}}
                            @method('GET')

                            <div class="row">
                                <div class="col-6 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('state', __('Estado'),['class'=>'col-form-label col-12 col-sm-3']) }}
                                        {{ Form::select("state", $states,$data->state??null, ['id'=>'state','class' => "custom-select col-12 col-sm-9",'placeholder'=>__('Seleccione...')])}}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('stage', __('Etapa'),['class'=>'col-form-label col-12 col-sm-3']) }}
                                        {{ Form::select("stage", $stages,$data->stage??null, ['id'=>'stage','class' => "custom-select col-12 col-sm-9",'placeholder'=>__('Seleccione...')])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if(Auth::user()->roles_id!=5)
                                <div class="col-6 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente '),['class'=>'col-form-label col-12 col-sm-3']) }}
                                        {{ Form::select("client", $clients,$data->client??null, ['id'=>'client','class' => "custom-select col-12 col-sm-9 select2",'placeholder'=>__('Seleccione...')])}}
                                    </div>
                                </div>
                                @endif
                                <div class="col-12 col-lg-7">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_start', __('Fecha de gestión'),['class'=>'col-form-label d-inline col-12 col-sm-3']) }}
                                        <div class="d-inline-block col-12 col-sm-9">
                                            {{ Form::date("date_start", $data->date_start?? '', ['id'=>'date_start','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                            {{ Form::date("date_end", $data->date_end?? date('Y-m-d'), ['id'=>'date_end','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2 btn-type" data-type="search">
                                {{ __('Buscar') }}
                            </button>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Resultado de busqueda')}}</h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <button type="button" class="btn btn-primary btn-type" title="{{ __('Exportar') }}" data-type="export">
                                   <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    {{ __('Descargar reporte') }}
                                </button>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Gestión por estado')}}
                                    </legend>
                                    <div class="row justify-content-center mb-3">
                                        <div class="col text-center">
                                            <h5>
                                                {{__('Resultados')}} : {{ $data->date_start??'Inicio' }} - {{ $data->date_end??date('Y-m-d') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-lg-6 table-responsive">
                                            <table class='table table-bordered d-table'>
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th class="text-center">{{ __('Estado') }}</th>
                                                        <th class="text-center">{{ __('Cantidad') }}</th>
                                                        <th class="text-center" style="min-width: 190px">{{ __('Porcentaje') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($registers_states ))
                                                    @foreach ($registers_states as $registers_state)
                                                        <tr>
                                                            <td>{{ $registers_state->name }}</td>
                                                            <td>
                                                                <span class="badge bg-success text-white">
                                                                    {{ $registers_state->total }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php($porcentaje = $total==0? $total : round($registers_state->total * 100 / ($total), 1))
                                                                <span class="d-inline-block" style="width: 30%">
                                                                    {{ $porcentaje }}%
                                                                </span>
                                                                <span class="d-inline-block" style="width: 65%">
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                        style="max-width: {{ $porcentaje }}%; min-width: {{ $porcentaje }}%;" aria-valuenow="{{ $registers_state->total }}"
                                                                        aria-valuemin="0" aria-valuemax="{{ $total }}">
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            {{ __('Sin resultados') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="pie_state"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row">
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Etapa actual')}}
                                    </legend>
                                    <div class="row justify-content-center mb-3">
                                        <div class="col text-center">
                                            <h5>
                                                {{__('Resultados')}} : {{ $data->date_start??'Inicio' }} - {{ $data->date_end??date('Y-m-d') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="pie_stage"></div>
                                            </figure>
                                        </div>
                                        <div class="col-12 col-lg-6 table-responsive">
                                            <table class='table table-bordered d-table'>
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th class="text-center">{{ __('Etapa') }}</th>
                                                        <th class="text-center">{{ __('Cantidad') }}</th>
                                                        <th class="text-center" style="min-width: 185px">{{ __('Porcentaje') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($registers_stages ))
                                                    @foreach ($registers_stages as $registers_stage)
                                                        <tr>
                                                            <td>{{ $registers_stage->name }}</td>
                                                            <td>
                                                                <span class="badge bg-success text-white">
                                                                    {{ $registers_stage->total }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php($porcentaje = $total==0? $total : round($registers_stage->total * 100 / $total, 1))
                                                                <span class="d-inline-block" style="width: 30%">
                                                                    {{ $porcentaje }}%
                                                                </span>
                                                                <span class="d-inline-block" style="width: 64%">
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                        style="max-width: {{ $porcentaje }}%; min-width: {{ $porcentaje }}%;" aria-valuenow="{{ $registers_stage->total }}"
                                                                        aria-valuemin="0" aria-valuemax="{{ $total }}">
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            {{ __('Sin resultados') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row">
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Responsable de gestión')}}
                                    </legend>
                                    <div class="row justify-content-center mb-3">
                                        <div class="col text-center">
                                            <h5>
                                                {{__('Resultados')}} : {{ $data->date_start??'Inicio' }} - {{ $data->date_end??date('Y-m-d') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-lg-6 table-responsive">
                                            <table class='table table-bordered d-table'>
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th class="text-center">{{ __('Responsable') }}</th>
                                                        <th class="text-center">{{ __('Cantidad') }}</th>
                                                        <th class="text-center" style="min-width: 185px">{{ __('Porcentaje') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($registers_responsables))
                                                    @foreach ($registers_responsables as $registers_responsable)
                                                        <tr>
                                                            <td>{{ $registers_responsable->name }}</td>
                                                            <td>
                                                                <span class="badge bg-success text-white">
                                                                    {{ $registers_responsable->total }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php($porcentaje = $total==0? $total : round($registers_responsable->total * 100 / $total, 1))
                                                                <span class="d-inline-block" style="width: 30%">
                                                                    {{ $porcentaje }}%
                                                                </span>
                                                                <span class="d-inline-block" style="width: 64%">
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                        style="max-width: {{ $porcentaje }}%; min-width: {{ $porcentaje }}%;" aria-valuenow="{{ $registers_responsable->total }}"
                                                                        aria-valuemin="0" aria-valuemax="{{ $total }}">
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            {{ __('Sin resultados') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="pie_responsable"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        var route_search = "{{route('reports.management.search')}}";
        var route_export = "{{route('reports.management.export')}}";
    </script>

    <script>
        $('.btn-type').on('click', function(){
            let type = $(this).data('type');
            let form = $('#form-filter');

            if(type === 'search'){
                form.attr('action', route_search);
            }else{
                form.attr('action', route_export);
            }
            form.submit();
        });
    </script>

    <script>
        var registers_states = {!! json_encode($registers_states) !!};
        var registers_stages = {!! json_encode($registers_stages) !!};
        var registers_responsables = {!! json_encode($registers_responsables) !!};
        var total = {{ $total }};
    </script>

    <script>
        Highcharts.setOptions({
            lang: {
                viewFullscreen:'Ver en pantalla completa',
                exitFullscreen:'Salir de pantalla completa',
                downloadPNG:'Descargar como PNG',
                downloadJPEG:'Descargar como JPEG',
                downloadPDF:'Descargar como PDF',
                downloadCSV:'Descargar CSV',
                downloadXLS:'Descargar XLS',
            }
        });
    </script>

    <script>
        var data_registers_states =[];
        var colors_states = ['#B5D9FF','#71B6FF','#007CFF','#0054AC','#00346B','#002348'];

        registers_states.forEach((element,index) => {
            if(element.total !=0){
                let data = new Object();
                data.name = element.name;
                data.y = element.total;
                data.percentage = element.total * 100 / total;
                data.color = colors_states[index]
                data_registers_states.push(data);
            }
        });

        Highcharts.chart('pie_state', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Estado de las solicitudes'
            },
            tooltip: {
                pointFormat: 'Cantidad: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Cantidad',
                colorByPoint: true,
                data: data_registers_states
            }],
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ['viewFullscreen', 'downloadPDF','downloadPNG','downloadJPEG','downloadXLS','downloadCSV']
                    }
                }
            }
        });
    </script>

    <script>
        var data_registers_stages =[];
        var colors_stages = ['#AEFFC6','#46EE77','#00C239','#088E2F','#056020','#064118','#021E0A'];

        registers_stages.forEach((element,index) => {
            if(element.total !=0){
                let data = new Object();
                data.name = element.name;
                data.y = element.total;
                data.percentage = element.total * 100 / total;
                data.color = colors_stages[index]
                data_registers_stages.push(data);
            }
        });

        Highcharts.chart('pie_stage', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Etapa de las solicitudes'
            },
            tooltip: {
                pointFormat: 'Cantidad: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Cantidad',
                colorByPoint: true,
                data: data_registers_stages
            }],
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ['viewFullscreen', 'downloadPDF','downloadPNG','downloadJPEG','downloadXLS','downloadCSV']
                    }
                }
            }
        });
    </script>

    <script>
        var data_registers_responsables =[];
        var colors_responsables = ['#989898','#3C3C3C','#121212'];

        registers_responsables.forEach((element,index) => {
            if(element.total !=0){
                let data = new Object();
                data.name = element.name;
                data.y = element.total;
                data.percentage = element.total * 100 / total;
                data.color = colors_responsables[index]
                data_registers_responsables.push(data);
            }
        });

        Highcharts.chart('pie_responsable', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Responsable de gestión'
            },
            tooltip: {
                pointFormat: 'Cantidad: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Cantidad',
                colorByPoint: true,
                data: data_registers_responsables
            }],
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ['viewFullscreen', 'downloadPDF','downloadPNG','downloadJPEG','downloadXLS','downloadCSV']
                    }
                }
            }
        });
    </script>

@endsection

