@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{route('price.list.index')}}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Creación de lista de precios')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('admin.cruds.pricelist.partial.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
