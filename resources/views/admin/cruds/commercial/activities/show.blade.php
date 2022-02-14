@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('commercial.register.show',$register) }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Información de actividades')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">

                            <div class="row">
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Datos de la actividad')}}
                                    </legend>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('ID Actividad')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Id}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('ID Cuenta')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->AccountId}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Estado')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Status}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Prioridad')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Priority}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Asunto')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Subject}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Comentario')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Description}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Divisa de la actividad')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->CurrencyIsoCode}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Fecha de vencimiento')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->ActivityDate}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Fecha de creación')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->CreatedDate}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Fecha de modificación')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->LastModifiedDate}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Recordatorio establecido')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->ReminderDateTime}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Tipo de actividad')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->TaskSubtype}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
