@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes suspendidas')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$suspended->total()}}
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
                                    <th>{{__('Proveedor')}}</th>
                                    <th>{{__('Etapa actual')}}</th>
                                    <th>{{__('Fecha en que se suspendio')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($suspended))
                                @foreach($suspended as $suspended_register)
                                    <tr>
                                        <td>{{$suspended_register->code}}</td>
                                        <td>{{$suspended_register->client->name}}</td>
                                        <td>{{$suspended_register->identification_number}}</td>
                                        <td>{{$suspended_register->stage->name}}</td>
                                        <td>{{ date('Y/m/d H:i:s', strtotime($suspended_register->date_suspended)) }}</td>
                                        <td>
                                            {{-- <a href="#" class="btn btn-primary mb-1">{{__('Detalle')}}</a> --}}
                                            <a href="{{ route('suspended.open',$suspended_register->id) }}" class="btn btn-success mb-1">{{__('Abrir solicitud')}}</a>
                                        </td>
                                    </tr>
                                @endforeach
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
                        @if(count($suspended))
                        <div class="card-footer">
                            {{$suspended->links()}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
