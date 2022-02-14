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
                                    {{__('Filtro para usuarios')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('users.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['users.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            {{Form::hidden('orderBy','rol',['id'=>'orderBy'])}}
                            {{Form::hidden('order','ASC',['id'=>'order'])}}
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('name', __('Nombre'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("name", isset($data)?$data->name:"", ['id'=>'name','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('email', __('Email'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("email", isset($data)?$data->email:"", ['id'=>'email','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('roles_id', __('Rol'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::select("roles_id",$roles, isset($data)?$data->roles_id:null,
                                        ['class' => "custom-select", 'id'=>'roles_id','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente Ancla'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("client", isset($data)?$data->client:"", ['id'=>'client','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                {{ __('Buscar') }}
                            </button>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.cruds.users.partial.list',compact('users'))
        </div>
    </div>
@endsection
