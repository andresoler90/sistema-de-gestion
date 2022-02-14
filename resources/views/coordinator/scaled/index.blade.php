@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes escaladas')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$scaled_registers->total()}}
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
                                    <th>{{__('Fecha de respuesta')}}</th>
                                    <th>{{__('Decisión')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($scaled_registers))
                                @foreach($scaled_registers as $scaled_register)
                                    <tr>
                                        <td>{{$scaled_register->code}}</td>
                                        <td>{{$scaled_register->client->name}}</td>
                                        <td>{{$scaled_register->stage->name}}</td>
                                        <td>{{ date('Y/m/d', strtotime($scaled_register->scaled_created_at)) }}</td>
                                        <td>{{Config::get('options.decision.'.$scaled_register->decision)}}</td>

                                        <td>
                                            @if ($scaled_register->stage->id == 4)
                                                <a href="{{route('management.scaled.document.management.show',$scaled_register->id)}}"
                                               class="btn btn-info">{{__('Ver')}}</a>
                                               @else
                                               <a href="{{route('management.scaled.retrieval.show',$scaled_register->id)}}"
                                              class="btn btn-info">{{__('Ver')}}</a>
                                            @endif
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
                        @if(count($scaled_registers))
                        <div class="card-footer">
                            {{$scaled_registers->links()}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
