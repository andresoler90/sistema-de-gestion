<div class="row">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">{{__('Matriz Documental')}} <span
                            class="badge badge-cobalt-blue">{{$documents->total()}}</span></h4>
                </div>
                <div class="iq-card-header-toolbar">
                    <a href="{{route('client.document.create',['clients_id' => $clients_id])}}"
                       class="btn btn-outline-primary">{{__('Crear')}}</a>
                </div>
            </div>
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Nombre')}}</th>
                        <th>{{__('Tipo de registro')}}</th>
                        <th>{{__('Tipo de proveedor')}}</th>
                        <th>{{__('Tipo de documento')}}</th>
                        <th>{{__('Vigencia')}}</th>
                        <th style="width: 250px">{{__('Opciones')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($documents))
                        @foreach($documents as $document)
                            <tr>
                                <td>
                                    {{ $document->document->name }}
                                    @if($document->commentary)
                                        <span class="d-block">{{ $document->commentary }}</span>
                                    @endif
                                </td>
                                <td>{{ words(Config::get("options.register_type.$document->register_type")) }}</td>
                                <td>{{ $document->provider_type_name }}</td>
                                <td>{{ $document->document_type_name }}</td>
                                <td>{{ $document->validity }}</td>
                                <td>
                                    <a href="{{route('client.document.edit',$document->id)}}" title="Editar"
                                       class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>

                                    {{Form::open(['route' => ['client.document.destroy',$document->id],'class'=>'d-inline'])}}
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    {{Form::close()}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">
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
