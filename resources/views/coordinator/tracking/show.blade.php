@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('tracking.tasks.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Cantidad de seguimientos de la solicitud')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['tracking.tasks.store']])}}
                            {{Form::hidden("register_id", $register->id)}}

                            <div class="form-group">
                                <label for="tasks_gd">{{__('Seguimientos en gestión documental')}}</label>
                                {{Form::number("tasks_gd", $register->tasks_gd, ['class' => "form-control", 'required'])}}
                            </div>
                            <div class="form-group">
                                <label for="tasks_sub">{{__('Seguimientos en subsanación')}}</label>
                                {{Form::number("tasks_sub", $register->tasks_sub, ['class' => "form-control", 'required'])}}
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>

                            {{Form::close()}}
                            @include('partials._app_errors')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
