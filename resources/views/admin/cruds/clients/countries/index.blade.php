<div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">{{__('Países del cliente')}}
                <span class="badge badge-cobalt-blue">{{$countries->total()}}</span>
            </h4>
        </div>
        <div class="iq-card-header-toolbar">
            <a href="{{route('clients.countries.create',['clients_id' => $clients_id])}}"
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
            @if(count($countries))
                @foreach($countries as $country)
                    <tr>
                        <td>{{ $country->country->name }}</td>
                        <td>
                            {{Form::open(['route' => ['clients.countries.destroy',$country->id],'class'=>'d-inline'])}}
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
    @if(count($countries))
        <div class="card-footer">
            {{$countries->links()}}
        </div>
    @endif
</div>
