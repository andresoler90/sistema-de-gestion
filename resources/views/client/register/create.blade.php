@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('registers.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Creación de solicitud')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('client.register.partial.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
