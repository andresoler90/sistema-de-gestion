@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Alertas')}} <span
                                        class="badge badge-cobalt-blue">{{''}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addModal">
                                    {{__('AGREGAR')}}
                                </button>
                            </div>
                        </div>
                        <div class="iq-card-body">

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Nombre')}}</th>
                                    <th>{{__('Periodicidad')}}</th>
                                    <th>{{__('Cliente')}}</th>
                                    <th>{{__('Fecha')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($configs))
                                    @php($i=1)
                                    @foreach($configs as $config)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$config->name}}</td>
                                            <td>
                                                {{Form::select('periodicity',config('options.periodicity'),$config->periodicity,['class'=>'custom-select','id'=>'periodicity','required'])}}
                                            </td>
                                            <td>{{$config->client->name}}</td>
                                            <td>{{$config->created_at->format('d/m/Y')}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{__('No se poseen registros asociados')}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <!-- Modal -->
                            <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
                                 aria-labelledby="addModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">{{__('Agregar alerta')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {{Form::open(['route'=>'client.config.alert.store'])}}
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="clients_id">{{__('Cliente')}}</label>
                                                        {{Form::select('clients_id',$clients,null,['class'=>'custom-select','id'=>'clients_id','placeholder'=>'Seleccione...','required'])}}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="config_alerts">{{__('Alerta')}}</label>
                                                        {{Form::select('configuration_alert',config('options.configuration_alert'),null,['class'=>'custom-select','id'=>'config_alerts','placeholder'=>'Seleccione...','required'])}}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="periodicity">{{__('Periodicidad')}}</label>
                                                        {{Form::select('periodicity',config('options.periodicity'),null,['class'=>'custom-select','id'=>'periodicity','placeholder'=>'Seleccione...','required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{__('Cerrar')}}</button>
                                            {{Form::submit('Guardar',['class'=>'btn btn-primary'])}}
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
