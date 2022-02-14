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
                                    {{__('Solicitudes')}}
                                    <span class="badge badge-cobalt-blue">{{ count($registers) }}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Código')}}</th>
                                    <th>{{__('Identificación')}}</th>
                                    <th>{{__('Razón Social')}}</th>
                                    <th>{{__('Cliente')}}</th>
                                    <th>{{__('Estado')}}</th>
                                    <th>{{__('Estado Salesforce')}}</th>
                                    <th>{{__('Acciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($registers))
                                    @foreach ($registers as $data)
                                        @php
                                            $register = $data->register;
                                        @endphp
                                        <tr>
                                            <td> {{$register->code}} </td>
                                            <td> {{$register->identification_number}} </td>
                                            <td> {{$register->business_name}} </td>
                                            <td> {{$register->user->name}} </td>
                                            <td> {{$register->state->name}} </td>
                                            <td>
                                                @if(method_exists($data,'statusSalesforce'))
                                                    {!! $data->statusSalesforce() !!}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('commercial.register.show',[$register])}}"
                                                   title="Ver" class="btn btn-primary btn-sm">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                @if($data->status == 'P')
                                                    <a href="{{route('execute.salesforce.opportunity',$data)}}"
                                                       role="button" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-cogs"></i>
                                                        {{__('Ejecutar Salesforce')}}
                                                    </a>
                                                @endif
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
                    </div>
                </div>
            </div>

        </div>
    </div>


@section('scripts')
    @parent
    {!! Html::script('assets/js/alert_confirm.js') !!}
@endsection
@endsection
