@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('commercial.register.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            @php($register_task = $register->tasks->last())

            @include('analyst.partials.register_detail_card')

            @include('admin.cruds.commercial.salesforce.additional_info',compact($salesforce = $register_salesforce[0]))

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                    <h4 class="card-title">{{__('Contactos')}}
                                        <span
                                            class="badge badge-cobalt-blue">{{$contacts->total()}}
                                        </span>
                                    </h4>
                                </a>
                                <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                                    <h4 class="card-title">{{__('Actividades')}} <span
                                            class="badge badge-cobalt-blue">{{$activities->total()}}
                                        </span>
                                    </h4>
                                </a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                @include('admin.cruds.commercial.contacts.index',compact($contacts))
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                @include('admin.cruds.commercial.activities.index',compact($activities))
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.cruds.commercial.salesforce.index')

        </div>
    </div>


@section('scripts')
    @parent
    {!! Html::script('assets/js/alert_confirm.js') !!}
@endsection
@endsection
