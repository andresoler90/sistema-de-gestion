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
                                    {{__('Gestión interna')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.management.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.internal.management.search'],'id'=>'form-filter'])}}
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
                                <h4 class="card-title">{{__('Reporte cantidad de tareas')}}</h4>
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
                                        <th>{{ __('Gestión Documental') }}</th>
                                        <th>
                                            <a href="{{ route('reports.internal.management.verification') }}">
                                                {{ __('Verificación') }}
                                            </a>
                                        </th>
                                        <th>{{ __('Subsanación') }}</th>
                                        <th>{{ __('Calidad') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($analysts_tasks))
                                        @foreach ($analysts_tasks as $analysts_task)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('reports.internal.management.analyst',$analysts_task->users_id) }}">
                                                        {{ $analysts_task->users_name }}
                                                    </a>
                                                </td>
                                                @foreach ($analysts_task->stages as $stage)
                                                    <td class="{{ $stage->tasks!=0?'font-weight-bold text-success':''}}">{{ $stage->tasks }}</td>
                                                @endforeach
                                                <td>{{ $analysts_task->total }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="font-weight-bold">{{ __('Total') }}</td>
                                            @foreach ($stage_tasks as $stage_task)
                                                <td>{{ $stage_task->tasks }}</td>
                                            @endforeach
                                            <td class="font-weight-bold">{{ $total }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">
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
@endsection


@section('scripts')
    @parent
    <script>
        var route_search = "{{route('reports.internal.management.search')}}";
        var route_export = "{{route('reports.internal.management.export')}}";
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
