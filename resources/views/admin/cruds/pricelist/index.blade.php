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
                                    {{__('Filtro')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('price.list.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['price.list.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            {{Form::hidden('orderBy','client',['id'=>'orderBy'])}}
                            {{Form::hidden('order','ASC',['id'=>'order'])}}
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        <span class="col-12 col-sm-8">
                                            {{ Form::select("client",$clients, isset($data)?$data->client:null,
                                            ['id'=>'client','class' => "form-control select2",'placeholder' => 'Seleccione...'])}}
                                        </span>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Lista de precios')}}
                                    <span class="badge badge-cobalt-blue">{{$price_lists->total()}}</span>
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('price.list.create')}}"
                                class="btn btn-outline-primary">{{__('Crear')}}</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Nombre')}}</th>
                                    <th>{{__('País')}}</th>
                                    <th>{{__('Asumido por')}}</th>
                                    <th>{{__('Cliente')}}</th>
                                    <th>{{__('Tipo de registro')}}</th>
                                    <th>{{__('Tipo de proveedor')}}</th>
                                    <th>{{__('Divisa')}}</th>
                                    <th>{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($price_lists))
                                    @foreach($price_lists as $price_list)
                                        <tr>
                                            <td>{{ $price_list->name }}</td>
                                            <td>{{ $price_list->country->name }}</td>
                                            <td>{{ Config::get("options.register_assumed_by.$price_list->register_assumed_by")}}</td>
                                            <td>{{ $price_list->client->name }}</td>
                                            <td>{{ words(Config::get("options.register_type.$price_list->register_type")) }}</td>
                                            <td>{{ Config::get("options.provider_type.$price_list->provider_type")}}</td>
                                            <td>{{ $price_list->currency }}</td>
                                            <td>
                                                <a href="{{route('price.list.edit',$price_list->id)}}" title="Editar"
                                                class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>

                                                {{Form::open(['route' => ['price.list.destroy',$price_list->id],'class'=>'d-inline'])}}
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-destroy" title="Eliminar">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                                {{Form::close()}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            {{__("No hay registros aún")}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        @if(count($price_lists))
                            <div class="card-footer">
                                {{$price_lists->appends([
                                    'client'   =>$data->client??null,
                                    'orderBy'  =>$data->orderBy??null,
                                    'order'    =>$data->order??null,
                                ])->links()}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
