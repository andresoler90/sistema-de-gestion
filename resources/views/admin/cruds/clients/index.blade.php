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
                                <a href="{{route('clients.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['clients.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            {{Form::hidden('orderBy','clients_id',['id'=>'orderBy'])}}
                            {{Form::hidden('order','ASC',['id'=>'order'])}}
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('clients_id', __('Nombre'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        <span class="col-12 col-sm-8">
                                            {{ Form::select("clients_id",$clients_name, isset($data)?$data->clients_id:null,
                                            ['id'=>'clients_id','class' => "form-control select2",'placeholder' => 'Seleccione...'])}}
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
                                <h4 class="card-title">{{__('Cliente')}} <span
                                        class="badge badge-cobalt-blue">{{$clients->total()}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('clients.create')}}"
                                   class="btn btn-outline-primary">{{__('Crear')}}</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Acrónimo')}}</th>
                                    <th>{{__('Nombre')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th style="width: 350px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($clients))
                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{$client->acronym}}</td>
                                        <td>
                                            <a href="{{route('clients.edit',$client->id)}}">
                                                {{$client->name}}
                                            </a>
                                        </td>
                                        <td>{{$client->email}}</td>
                                        <td>
                                            <a href="{{route('clients.edit',$client->id)}}"
                                               class="btn btn-info">{{__('Editar')}}</a>
                                            {{Form::open(['route' => ['clients.destroy',$client->id],'class'=>'d-inline'])}}
                                               @method('DELETE')
                                               <button type="button" class="btn btn-outline-danger btn-destroy">
                                                   {{__('Eliminar')}}
                                               </button>
                                           {{Form::close()}}
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        {{__("No hay registros aún")}}
                                    </td>
                                </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer">
                            {{$clients->appends([
                                'clients_id'   =>$data->clients_id??null,
                                'orderBy'  =>$data->orderBy??null,
                                'order'    =>$data->order??null,
                            ])->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none">
        <span class="alert_title">{{__('¿Seguro que desea eliminar el cliente?')}}</span>
        <span class="alert_confirmButtonText">{{__('Aceptar')}}</span>
        <span class="alert_cancelButtonText">{{__('Cancelar')}}</span>
    </div>

    @section('scripts')
        @parent
        {!! Html::script('assets/js/alert_confirm.js') !!}
    @endsection

@endsection
