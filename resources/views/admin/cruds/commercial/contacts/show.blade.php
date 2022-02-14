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
                                <h4 class="card-title">{{__('Información de contacto')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">

                            <div class="row">
                                <fieldset class="scheduler-border w-100">
                                    <legend class="scheduler-border">
                                        {{__('Datos del contacto')}}
                                    </legend>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('ID Contacto')}}</label>
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
                                                <label class="font-weight-bold">{{__('Tratamiento')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Salutation}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Sufijo')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Suffix}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Nombre')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->FirstName}}
                                                    @if(isset($dataJson->IsPrimary) && $dataJson->IsPrimary == true)
                                                        <span class="badge badge-primary">Principal</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Apellido')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->LastName}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Nombre Completo')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Name}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Cargo')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Title}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Correo')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Email}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Fax')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Fax}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Telefono')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Phone}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Celular')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->MobilePhone}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('País')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Pais__c}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Departamento')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Department}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Ciudad')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Ciudad__c}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{__('Dirección')}}</label>
                                                <span class="d-block">
                                                    {{$dataJson->Direcci_n__c}}
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
