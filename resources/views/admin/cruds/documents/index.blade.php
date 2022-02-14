@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Documentos')}} <span
                                        class="badge badge-cobalt-blue">{{$documents->total()}}</span></h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('documents.create')}}"
                                class="btn btn-outline-primary">{{__('Crear')}}</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{__('Nombre')}}</th>
                                    <th style="width: 250px">{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($documents))
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>{{ $document->name }}</td>

                                            <td>
                                                <a href="{{route('documents.edit',$document->id)}}" title="Editar"
                                                class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>

                                                {{Form::open(['route' => ['documents.destroy',$document->id],'class'=>'d-inline'])}}
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
                        @if(count($documents))
                            <div class="card-footer">
                                {{$documents->links()}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
