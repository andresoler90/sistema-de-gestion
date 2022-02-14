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
                                    {{__('Calidad')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.quality.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.quality.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            <div class="row">
                                <div class="col-12 col-lg-7">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_start', __('Fecha'),['class'=>'col-form-label d-inline col-12 col-sm-3']) }}
                                        <div class="d-inline-block col-12 col-sm-9">
                                            {{ Form::date("date_start", $data->date_start??'', ['id'=>'date_start','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
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
                                <h4 class="card-title">{{__('Tareas de verificación fallidas')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <figure class="highcharts-figure">
                                <div id="chart_column"></div>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Tareas de verificación por analista')}}</h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <button type="button" class="btn btn-primary btn-type" title="{{ __('Exportar') }}" data-type="export">
                                   <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    {{ __('Descargar reporte') }}
                                </button>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Analista') }}</th>
                                        <th>{{ __('Verificación Basica') }}</th>
                                        <th>{{ __('Verificación Experiencias') }}</th>
                                        <th>{{ __('Verificación Financiera') }}</th>
                                        <th>{{ __('Verificación Documentos del Cliente') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($analysts_tasks))
                                        @foreach ($analysts_tasks as $analysts_task)
                                            <tr>
                                                <td>{{ $analysts_task->users_name }}</td>
                                                @foreach ($analysts_task->stages as $stage)
                                                    <td class="{{ $stage->tasks!=0?'font-weight-bold text-success':''}}">{{ $stage->tasks }}</td>
                                                @endforeach
                                                <td>{{ $analysts_task->total }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="font-weight-bold">{{ __('Total') }}</td>
                                            @foreach ($verification_tasks as $verification_task)
                                                <td>{{ $verification_task->tasks }}</td>
                                            @endforeach
                                            <td class="font-weight-bold">{{ $total }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                {{ __('Sin resultados') }}
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
@endsection

@section('scripts')
    @parent

    <script>
        var route_search = "{{route('reports.quality.search')}}";
        var route_export = "{{route('reports.quality.export')}}";
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
        var verification_tasks = {!! json_encode($verification_tasks) !!};
        var total = {{ $total }};

        var data_verification_tasks =[];
        var color = '#1ABC57';

        verification_tasks.forEach((element,index) => {
            let data = new Object();
            data.name = element.stage_tasks_label;
            data.y = element.tasks;
            data.percentage = element.tasks * 100 / total;
            data.color = color;
            data_verification_tasks.push(data);
        });

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
        // Create the chart
        Highcharts.chart('chart_column', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Tareas de verificación fallidas'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Cantidad de tareas'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f}%'
                    }
                }
            },
            tooltip: {
                pointFormat: 'Cantidad: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
            },
            credits: {
                enabled: false
            },
            series: [
                {
                    name: "Tareas de verificación fallidas",
                    colorByPoint: true,
                    data: data_verification_tasks,
                }
            ],
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
