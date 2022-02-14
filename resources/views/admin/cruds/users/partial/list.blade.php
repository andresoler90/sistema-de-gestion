<div class="row">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">{{__('Usuario')}}
                        <span class="badge badge-cobalt-blue">{{$users->total()}}</span>
                    </h4>
                </div>
                <div class="iq-card-header-toolbar">
                    @if(!isset($clients_id))
                        @php($clients_id = null)
                    @endif
                    <a href="{{route('users.create',['clients_id' => $clients_id])}}"
                       class="btn btn-outline-primary">{{__('Crear')}}</a>
                </div>
            </div>
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>
                            {{__('Nombre')}}
                            @isset($roles)
                                @component('components.sort_by')
                                    @slot('data', $data??null)
                                    @slot('column', 'name')
                                @endcomponent
                            @endisset
                        </th>
                        <th>
                            {{__('Email')}}
                            @isset($roles)
                                @component('components.sort_by')
                                    @slot('data', $data??null)
                                    @slot('column', 'email')
                                @endcomponent
                            @endisset
                        </th>
                        @if(Auth::user()->roles_id==1)
                            <th>
                                {{__('Rol')}}
                                @isset($roles)
                                    @component('components.sort_by')
                                        @slot('data', $data??null)
                                        @slot('column', 'rol')
                                    @endcomponent
                                @endisset
                            </th>
                            <th>
                                {{__('Cliente')}}
                                @isset($roles)
                                    @component('components.sort_by')
                                        @slot('data', $data??null)
                                        @slot('column', 'client')
                                    @endcomponent
                                @endisset
                            </th>
                        @endif
                        <th style="width: 250px">{{__('Opciones')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($users))
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <a href="{{route('users.edit',$user->id)}}">{{$user->name}}</a>
                                </td>
                                <td>{{$user->email}}</td>
                                @if(Auth::user()->roles_id==1)
                                    <td>{{$user->role->name}}</td>
                                    <td>
                                        @isset($user->client)
                                        <a href="{{route('clients.edit',$user->clients_id)}}">
                                            <i class="fas fa-external-link-alt"></i>
                                            {{$user->client->name}}
                                        </a>
                                        @endisset
                                    </td>
                                @endif
                                <td>
                                    <a href="{{route('users.edit',$user->id)}}" title="Editar"
                                       class="btn btn-primary btn-sm"><i class="far fa-edit"></i></a>
                                    @if(Auth::user()->roles_id==1)
                                        <a class="btn btn-dark btn-sm" href="{{route('impersonate', $user->id)}}"
                                           title="Impersonar">
                                            <i class="fas fa-people-arrows"></i>
                                        </a>
                                    @endif

                                    {{Form::open(['route' => ['users.destroy',$user->id],'class'=>'d-inline'])}}
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-destroy" title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    {{Form::close()}}

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            @if(Auth::user()->roles_id==1)
                                <td colspan="5" class="text-center">
                                    {{__("No hay registros aún")}}
                                </td>
                            @else
                                <td colspan="3" class="text-center">
                                    {{__("No hay registros aún")}}
                                </td>
                            @endif
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
            @if(count($users))
                <div class="card-footer">
                    {{$users->appends([
                        'name'     =>$data->name??null,
                        'email'    =>$data->email??null,
                        'roles_id' =>$data->roles_id??null,
                        'client'   =>$data->client??null,
                        'orderBy'  =>$data->orderBy??null,
                        'order'    =>$data->order??null,
                    ])->links()}}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="d-none">
    <span class="alert_title">{{__('¿Seguro que desea eliminar el usuario?')}}</span>
    <span class="alert_confirmButtonText">{{__('Aceptar')}}</span>
    <span class="alert_cancelButtonText">{{__('Cancelar')}}</span>
</div>

@section('scripts')
    @parent
    {!! Html::script('assets/js/alert_confirm.js') !!}

    <script>
        $('.sort-selection').on('click', function() {
            let orderby = $(this).data('orderby');
            let order = $(this).data('order');

            $('#orderBy').val(orderby);
            $('#order').val(order);

            $('#form-filter').submit();
        });

    </script>
@endsection
