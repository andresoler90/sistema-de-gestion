@extends('layouts.sgpar')

@section('content')

    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Campos adicionales')}} <span
                                        class="badge badge-cobalt-blue">{{count($settingFields)}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#campsModal_">
                                    {{__('Crear')}}
                                </button>
                            </div>

                            <!-- Modal -->
                            @include('admin.cruds.settings.fields.modal')
                        </div>
                        <div class="iq-card-body p-0">
                            @include('admin.cruds.settings.fields.table')
                        </div>
                    </div>
                </div>
            </div>

            @component('admin.cruds.settings.clients.table')
                @slot('settingClients',$settingClients)
                @slot('fields',$fields)
                @slot('clients',$clients)
                @slot('title',__('Asociar campo a Cliente'))
                @slot('edit',false)
                @slot('created',true)
            @endcomponent

        </div>
    </div>
@endsection
