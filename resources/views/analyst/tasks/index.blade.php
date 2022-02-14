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
                                    {{__('Tareas pendientes')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('analyst.tasks.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['analyst.tasks.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            {{Form::hidden('orderBy','codigo',['id'=>'orderBy'])}}
                            {{Form::hidden('order','ASC',['id'=>'order'])}}
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('code', __('Código'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("code", isset($data)?$data->code:"", ['id'=>'code','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente Ancla'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("client", isset($data)?$data->client:"", ['id'=>'client','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('priority', __('Prioridad'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{Form::select("priority",Config::get('options.priority'), isset($data)?$data->priority:null,
                                        ['class' => "custom-select", 'id'=>'priority','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('stage', __('Etapa'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{Form::select("stage",$stages, isset($data)?$data->stage:null,
                                        ['class' => "custom-select", 'id'=>'stage','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('business_name', __('Razón social'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("business_name", isset($data)?$data->business_name:"", ['id'=>'business_name','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('state_ans', __('Estado de gestión'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::select("state_ans",Config::get('options.management_status'), isset($data)?$data->state_ans:null,
                                        ['class' => "custom-select", 'id'=>'state_ans','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_start', __('Fecha'),['class'=>'col-form-label d-inline col-12 col-sm-2']) }}
                                        <div class="d-inline-block col-12 col-sm-10">
                                            {{ Form::date("date_start", isset($data)?$data->date_start:"", ['id'=>'date_start','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                            {{ Form::date("date_end", isset($data)?$data->date_end:"", ['id'=>'date_end','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
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
                                <h4 class="card-title">{{__('Tareas')}} <span
                                        class="badge badge-cobalt-blue">{{$tasks->total()}}</span></h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            {{__('Código')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'codigo')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Fecha')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'fecha')
                                            @endcomponent
                                        </th>
                                        {{-- <th>
                                            {{__('Estado')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'estado')
                                            @endcomponent
                                        </th> --}}
                                        <th>
                                            {{__('Prioridad')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'prioridad')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Etapa')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'etapa')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Descripción')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'descripcion')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Razón social')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'razon_social')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Tipo de registro')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'tipo_registro')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Tipo de proveedor')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'tipo_proveedor')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Estado de gestión')}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'estado_ans')
                                            @endcomponent
                                        </th>
                                        <th>
                                            {{__('Gestión')}} {{-- PAR / PRO --}}
                                            @component('components.sort_by')
                                                @slot('data', $data??null)
                                                @slot('column', 'gestion')
                                            @endcomponent
                                        </th>
                                        <th>{{__('Opciones')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($tasks))
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>{{$task->register->code}}</td>
                                            <td>{{$task->start_date->format('d/m/Y')}}</td>
                                            {{-- <td>{{$task->status_name}}</td> --}}
                                            <td>{{$task->priority_name}}</td>
                                            <td>{{$task->task->stage->name}}</td>
                                            <td>{{$task->description}}</td>
                                            <td>{{$task->register->business_name}}</td>
                                            <td>
                                                {{ words(Config::get("options.register_type.".$task->register->register_type)) }}
                                            </td>
                                            <td>{{$task->register->provider_type_name}}</td>
                                            <td>{{$task->management_status_name}}</td>
                                            <td>
                                                @if($task->management =='PAR')
                                                    @php($color = 'primary')
                                                @else
                                                    @php($color = 'success')
                                                @endif
                                                <span class="badge bg-{{ $color }} text-white">
                                                    {{config('options.management.'.$task->management) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($task->management == 'PRO')
                                                    @if ($task->task->stage->id == 4)
                                                        <a href="{{route('execute.provider.task',$task->id)}}"
                                                            role="button" class="btn btn-secondary btn-sm">
                                                            {{__('Cerrar')}}
                                                        </a>
                                                    @elseif($task->task->stage->id == 6)
                                                        <a href="{{route('execute.provider.retrieval.task',$task->id)}}"
                                                            role="button" class="btn btn-secondary btn-sm">
                                                            {{__('Cerrar')}}
                                                        </a>
                                                    @endif

                                                @elseif (config('tasks-routes.'.$task->task->name))
                                                    <a href="{{route(config('tasks-routes.'.$task->task->name),$task->register_tasks_id)}}"
                                                        class="btn btn-sm btn-info">{{__('Gestionar')}}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            {{__("No hay registros aún")}}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{$tasks->appends([
                                'code'          =>$data->code??null,
                                'client'        =>$data->client??null,
                                'priority'      =>$data->priority??null,
                                'stage'         =>$data->stage??null,
                                'business_name' =>$data->business_name??null,
                                'state_ans'     =>$data->state_ans??null,
                                'date_start'    =>$data->date_start??null,
                                'date_end'      =>$data->date_end??null,
                                'orderBy'       =>$data->orderBy??null,
                                'order'         =>$data->order??null,
                            ])->links()}}
                            {{-- {{$tasks->count()}} {{ _('de') }} {{ $tasks->total() }} --}}
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
        $('.sort-selection').on('click', function() {
            let orderby = $(this).data('orderby');
            let order = $(this).data('order');

            $('#orderBy').val(orderby);
            $('#order').val(order);

            $('#form-filter').submit();
        });

    </script>
@endsection
