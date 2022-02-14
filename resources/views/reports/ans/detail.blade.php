@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('reports.ans.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Detalle de la solicitud')}} : {{ $register->code }}</h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a class="btn btn-primary btn-type" title="{{ __('Exportar') }}" href="{{route('reports.ans.export.detail',$register->id)}}">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    {{ __('Descargar reporte') }}
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Etapa')}}</th>
                                    <th>{{__('Tarea')}}</th>
                                    <th>{{__('Duración estimada')}}</th>
                                    <th>{{__('Duración Real')}}</th>
                                    <th>{{__('Dur. gestión PAR')}}</th>
                                    <th>{{__('Dur. gestión Proveedor')}}</th>
                                    <th>{{__('Dur. gestión Cliente')}}</th>
                                    <th>{{__('Cumple ANS')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(count($register_times->times) > 0 )
                                        @foreach ($register_times->times as $task)
                                            <tr>
                                                <td>{{ $task->stage }}</td>
                                                <td>{{ $task->task }}</td>
                                                <td>{{ $task->estimate }} {{ __('Hrs') }}</td>
                                                <td>{{ $task->real }} {{ __('Hrs') }}</td>
                                                <td>{{ $task->par }} {{ __('Hrs') }}</td>
                                                <td>{{ $task->pro }} {{ __('Hrs') }}</td>
                                                <td>{{ $task->cli }} {{ __('Hrs') }}</td>
                                                <td class="font-weight-bold text-{{ $task->ans=='Si'?'success':'danger' }}">
                                                    {{ __($task->ans) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                <th colspan="2">{{ __('Total') }}</th>
                                                <td>{{ $total['estimate'] }} {{ __('Hrs') }}</td>
                                                <td>{{ $total['real'] }} {{ __('Hrs') }}</td>
                                                <td>{{ $total['par'] }} {{ __('Hrs') }}</td>
                                                <td>{{ $total['pro'] }} {{ __('Hrs') }}</td>
                                                <td>{{ $total['cli'] }} {{ __('Hrs') }}</td>
                                                <td class="font-weight-bold" >{{ $total['ans'] }} %</td>
                                            </tr>
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">
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
