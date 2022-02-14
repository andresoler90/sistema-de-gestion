@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$registers->total()}}
                                    </span>
                                </h4>
                            </div>
                        </div>

                        <div class="iq-card-body">
                            @include('partials._app_errors')

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Código')}}</th>
                                    <th>{{__('Cliente')}}</th>
                                    <th>{{__('Razón social')}}</th>
                                    <th>{{__('Etapa actual')}}</th>
                                    <th>{{__('Gestionar')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($registers))
                                @foreach($registers as $register)
                                    <tr>
                                        <td>{{$register->code}}</td>
                                        <td>{{$register->client->name}}</td>
                                        <td>{{$register->business_name}}</td>
                                        <td>{{$register->stage->name}}</td>
                                        <td>
                                            <a href="{{route('tracking.tasks.show',$register->id)}}"
                                                class="btn btn-info">{{__('Gestionar')}}</a>
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
