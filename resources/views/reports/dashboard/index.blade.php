@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            @if(Auth::user()->roles_id!=5)
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__('Dashboard')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.dashboard.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.dashboard.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente '),['class'=>'col-form-label col-12 col-sm-3']) }}
                                        {{ Form::select("client", $clients,$data->client??null, ['id'=>'client','class' => "custom-select col-12 col-sm-9 select2",'placeholder'=>__('Seleccione...')])}}
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2 btn-type" data-type="search">
                                {{ __('Buscar') }}
                            </button>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes escaladas')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <figure class="highcharts-figure">
                                        <div id="pie"></div>
                                    </figure>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row table-wrapper-scroll-y my-custom-scrollbar-400">
                                        <table class="table table-striped table-bordered fixed-head d-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Código') }}</th>
                                                    <th>{{ __('NIT') }}</th>
                                                    <th>{{ __('Razón social') }}</th>
                                                    <th>{{ __('Tipo de registro') }}</th>
                                                    <th>{{ __('Fecha de escalamiento') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($scaled_requests))
                                                    @foreach($scaled_requests as $register)
                                                        <tr>
                                                            <td>{{ $register->code }}</td>
                                                            <td>{{ $register->identification_number }}</td>
                                                            <td>{{ $register->business_name }}</td>
                                                            <td>{{ words(Config::get("options.register_type.$register->register_type"))}}</td>
                                                            <td>{{ $register->lastEvent()->created_at}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center">
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Información de solicitudes realizadas')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <figure class="highcharts-figure">
                                        <div id="bar"></div>
                                    </figure>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row table-wrapper-scroll-y my-custom-scrollbar-400">
                                        <table class="table table-striped table-bordered fixed-head d-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Solicitante') }}</th>
                                                    <th>{{ __('Abierto') }}</th>
                                                    <th>{{ __('Escalado') }}</th>
                                                    <th>{{ __('Cerrado') }}</th>
                                                    <th>{{ __('Anulado') }}</th>
                                                    <th>{{ __('Cancelado') }}</th>
                                                    <th>{{ __('Total') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($all_requests))
                                                    @foreach($all_requests as $register)
                                                        <tr>
                                                            <td>{{ $register->name }}</td>
                                                            <td>{{ $register->abierto }}</td>
                                                            <td>{{ $register->escalado }}</td>
                                                            <td>{{ $register->cerrado }}</td>
                                                            <td>{{ $register->anulado }}</td>
                                                            <td>{{ $register->cancelado }}</td>
                                                            <td>{{ $register->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center">
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
        var open_requests = {!! json_encode($open_requests) !!};
        var scaled_requests = {!! json_encode($scaled_requests) !!};
    </script>

    <script>
        var all_requests = {!! json_encode($all_requests) !!};
        var data_all_requests =[];
        var categorias = [];

        var abierto = []
        var cerrado = [];
        var escalado = [];
        var anulado = [];
        var cancelado = [];

        all_requests.forEach((element,index) => {
            categorias.push(element.name);
            abierto.push(element.abierto);
            cerrado.push(element.cerrado);
            escalado.push(element.escalado);
            anulado.push(element.anulado);
            cancelado.push(element.cancelado);

        });
        let obj1 = new Object();
        let obj2 = new Object();
        let obj3 = new Object();
        let obj4 = new Object();
        let obj5 = new Object();
        obj1.name = 'Abiertos';
        obj2.name = 'Cerrados';
        obj3.name = 'Escalados';
        obj4.name = 'Anulados';
        obj5.name = 'Cancelados';
        obj1.data = abierto;
        obj2.data = cerrado;
        obj3.data = escalado;
        obj4.data = anulado;
        obj5.data = cancelado;
        data_all_requests.push(obj1)
        data_all_requests.push(obj2)
        data_all_requests.push(obj3)
        data_all_requests.push(obj4)
        data_all_requests.push(obj5)

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
        Highcharts.chart('pie', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Solicitudes'
            },
            subtitle: {
               text: 'Abiertas vs Escaladas'
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
                data: [
                    {
                        name: 'Abiertas',
                        y: open_requests,
                    },
                    {
                        name: 'Escaladas',
                        y: scaled_requests.length,
                    },
                ]
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
        Highcharts.chart('bar', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Estado de las solicitudes por cliente'
            },
            xAxis: {
                categories: categorias,
                title: {
                    text: null
                },
                // labels: {
                //    step: 1
                // }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de solicitudes',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: data_all_requests,
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
