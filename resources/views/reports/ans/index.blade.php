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
                                    {{__('Filtros')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.ans.index')}}" class="btn btn-primary"
                                   title="{{ __('Borrar filtros') }}">
                                    <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.ans.search'],'method'=>'POST','id'=>'form-filter'])}}
                            @method('GET')
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_start', __('Fecha inicial'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        <input type="datetime-local" name="date_start" id="date_start" value="{{ $data->date_start??'' }}"
                                        class="form-control col-12 col-sm-8" style="max-width:239px">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_end', __('Fecha final'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        <input type="datetime-local" name="date_end" id="date_end" value="{{ $data->date_end??date('Y-m-d\TH:i') }}"
                                        class="form-control col-12 col-sm-8" style="max-width:239px">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    {{ __('Tiquetes creados en ese rango de fechas en estado: cerrado, escalado, anulado, cancelado') }}
                                </div>
                            </div>
                            <div class="row align-items-end">
                                <div class="col">
                                    <button type="button" class="btn btn-primary mt-2 btn-type" data-type="search">
                                        {{ __('Buscar') }}
                                    </button>
                                </div>
                            </div>
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
                                <h4 class="card-title">{{__('Registros de ANS')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$totalRegisters}}
                                    </span>
                                </h4>
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
                                    <th>{{__('ID Ticket')}}</th>
                                    <th>{{__('NIT')}}</th>
                                    <th>{{__('Razon Social')}}</th>
                                    <th>{{__('Duración Real')}}</th>
                                    <th>{{__('Dur. gestión PAR')}}</th>
                                    <th>{{__('Dur. gestión Proveedor')}}</th>
                                    <th>{{__('Dur. gestión Cliente')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if($totalRegisters > 0 )
                                        @foreach($register_times->times as $register_time)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('reports.ans.detail',$register_time->id) }}">
                                                        {{$register_time->code}}
                                                    </a>
                                                </td>
                                                <td>{{$register_time->identification_number}}</td>
                                                <td>{{$register_time->business_name}}</td>
                                                <td>{{$register_time->real}} {{ __('Hrs') }}</td>
                                                <td>{{$register_time->par}} {{ __('Hrs') }}</td>
                                                <td>{{$register_time->pro}} {{ __('Hrs') }}</td>
                                                <td>{{$register_time->cli}} {{ __('Hrs') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="3">{{__('Total')}}</th>
                                            <th>{{round($management_time['REAL'],2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['PAR'],2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['PRO'],2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['CLI'],2)}} {{ __('Hrs') }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">{{__('Promedio')}}</th>
                                            <th>{{round($management_time['REAL']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['PAR']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['PRO']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                                            <th>{{round($management_time['CLI']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                                        </tr>
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
@endsection

@section('scripts')
    @parent
    <script>
        var route_search = "{{route('reports.ans.search')}}";
        var route_export = "{{route('reports.ans.export')}}";
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
@endsection
