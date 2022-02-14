@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Prioridad de las tareas')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$register_tasks->total()}}
                                    </span>
                                </h4>
                            </div>
                        </div>

                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Código')}}</th>
                                    <th>{{__('Cliente')}}</th>
                                    <th>{{__('Etapa actual')}}</th>
                                    <th>{{__('Evento')}}</th>
                                    <th>{{__('Prioridad')}}</th>
                                    <th>{{__('Analista')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($register_tasks))
                                @foreach($register_tasks as $register_task)
                                    <tr>
                                        <td>{{$register_task->register->code}}</td>
                                        <td>{{$register_task->register->client->name}}</td>
                                        <td>{{$register_task->register->stage->name}}</td>
                                        <td>{{$register_task->last_event->description}}</td>
                                        <td>{{$register_task->priority_name}}</td>
                                        <td>{{ isset($register_task->analyst)? $register_task->analyst->name :''}}</td>
                                        <td>
                                            <a href="{{route('priority.tasks.show',$register_task->id)}}"
                                                class="btn btn-info">{{__('Gestionar')}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{__("No hay registros aún")}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        @if(count($register_tasks))
                        <div class="card-footer">
                            {{$register_tasks->links()}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
