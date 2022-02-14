@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes')}} <span class="badge badge-cobalt-blue">{{$registers->total()}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                @if(Auth::user()->roles_id == 5)

                                    @if(Auth::user()->client->mp_clients_id!=null)
                                        @if($tasks)
                                            <a href="{{route('registers.create')}}" class="btn btn-outline-primary">{{__('Crear')}}</a>
                                        @else
                                            {{ __('Debe tener asociado al menos una tarea visible por etapa') }}
                                        @endif
                                    @else
                                        {{ __('No hay un cliente asociado de Mi Proveedor') }}
                                    @endif
                                @else
                                    <a href="{{route('registers.create')}}" class="btn btn-outline-primary">{{__('Crear')}}</a>
                                @endif
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Código')}}</th>
                                    <th>{{__('Etapa')}}</th>
                                    <th>{{__('Estado')}}</th>
                                    <th>{{__('Razón Social')}}</th>
                                    <th>{{__('Usuario Solicitante')}}</th>
                                    <th>{{__('Usuario Creador')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($registers))
                                @foreach($registers as $register)
                                    <tr>
                                        <td>{{$register->code}}</td>
                                        <td>{{$register->stage->name}}</td>
                                        <td>{{$register->state->name}}</td>
                                        <td>{{$register->business_name}}</td>
                                        <td>{{$register->user->name}}</td>
                                        <td>{{$register->creatorUser->name}}</td>
                                        <td>
                                            <a href="{{route('registers.show',$register->id)}}"
                                               class="btn btn-info">{{__('Ver')}}</a>
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
                        @if(count($registers))
                        <div class="card-footer">
                            {{$registers->links()}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
