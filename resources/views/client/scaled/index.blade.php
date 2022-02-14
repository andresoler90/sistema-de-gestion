@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes escaladas')}} <span class="badge badge-cobalt-blue">{{$scaled_registers->total()}}</span></h4>
                            </div>
                        </div>

                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Código')}}</th>
                                    <th>{{__('Estado')}}</th>
                                    <th>{{__('Razón Social')}}</th>
                                    <th>{{__('Etapa')}}</th>
                                    <th>{{__('Fecha de escalamiento')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($scaled_registers))
                                @foreach($scaled_registers as $scaled_register)
                                    <tr>
                                        <td>{{$scaled_register->code}}</td>
                                        <td>{{$scaled_register->state->name}}</td>
                                        <td>{{$scaled_register->business_name}}</td>
                                        <td>{{$scaled_register->stage->name}}</td>
                                        <td>{{ $scaled_register->lastEvent()->created_at}}</td>
                                        <td>
                                            @if($scaled_register->stage->id == 4)
                                                <a href="{{route('scaled.registers.document.management.show',$scaled_register->id)}}"
                                                    class="btn btn-info">{{__('Gestionar')}}</a>
                                            @else
                                               <a href="{{route('scaled.registers.retrieval.show',$scaled_register->id)}}"
                                                    class="btn btn-info">{{__('Gestionar')}}</a>
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
