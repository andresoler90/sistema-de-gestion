@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Tareas')}} <span
                                        class="badge badge-cobalt-blue">{{$tasks->total()}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('tasks.create')}}"
                                   class="btn btn-outline-primary">{{__('Crear')}}</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Etapa')}}</th>
                                    <th>{{__('Label')}}</th>
                                    <th>{{__('Nombre')}}</th>
                                    <th>{{__('Tiempo Estimado')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($tasks))
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{$task->stage->name}}</td>
                                        <td>{{$task->label}}</td>
                                        <td>{{$task->name}}</td>
                                        <td>{{$task->estimated_time}}</td>
                                        <td>
                                            <a href="{{route('tasks.edit',$task->id)}}"
                                               class="btn btn-info">{{__('Editar')}}</a>
                                            {{Form::open(['route' => ['tasks.destroy',$task->id],'class'=>'d-inline'])}}
                                               @method('DELETE')
                                               <button type="submit" class="btn btn-outline-danger btn-destroy">
                                                   {{__('Eliminar')}}
                                               </button>
                                           {{Form::close()}}
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{__("No hay registros aún")}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer">
                            {{$tasks->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
