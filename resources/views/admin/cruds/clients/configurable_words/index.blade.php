<div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">{{__('Palabras configurables')}}
                <span class="badge badge-cobalt-blue">{{$words->total()}}</span>
            </h4>
        </div>
        <div class="iq-card-header-toolbar">
            <a href="{{route('configurable.words.clients.create',['clients_id' => $clients_id])}}"
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
            @if(count($words))
                @foreach($words as $word)
                    <tr>
                        <td>{{ $word->name }}</td>

                        <td>
                            <a href="{{route('configurable.words.clients.edit',$word->id)}}" title="Editar"
                            class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>

                            {{Form::open(['route' => ['configurable.words.clients.destroy',$word->id],'class'=>'d-inline'])}}
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
                    <td colspan="2" class="text-center">
                        {{__("No hay registros aún")}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>

    </div>
    @if(count($words))
        <div class="card-footer">
            {{$words->links()}}
        </div>
    @endif
</div>
