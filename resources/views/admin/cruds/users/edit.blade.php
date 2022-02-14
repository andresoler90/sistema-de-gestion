@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('users.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            @if(Auth::user()->roles_id==1)
                <a class="btn btn-dark btn-sm float-right" href="{{route('impersonate', $data->id)}}"
                   title="{{ __('Impersonar') }}">
                    <i class="fas fa-people-arrows"></i>
                </a>
            @endif
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Edición de usuario')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('admin.cruds.users.partial.form',compact('data'))
                        </div>
                    </div>
                </div>
                @if ($data->roles_id == 2 || $data->roles_id == 5)
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="iq-card">
                                    <div class="iq-card-body">
                                        {{Form::open(['route'=>'users.add.permission'])}}
                                        {{Form::hidden('users_id',$data->id)}}
                                        <div class="input-group">
                                            {{Form::select('permissions_id',$permissions,null,['class'=>'custom-select select2-p','required','placeholder'=>__('Seleccione...')])}}
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">
                                                    {{__('Agregar')}}
                                                </button>
                                            </div>
                                        </div>
                                        {{Form::close()}}
                                        <table class="table table-striped table-bordered mt-3 d-table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Nombre')}}</th>
                                                <th>{{__('Fecha')}}</th>
                                                <th>{{__('Opciones')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($data->userPermissions))
                                                @foreach($data->userPermissions as $userPermission)
                                                    <tr>
                                                        <td>{{$loop->index+1}}</td>
                                                        <td>{{$userPermission->permission->label}}</td>
                                                        <td>{{$userPermission->created_at}}</td>
                                                        <td>
                                                            {{Form::open(['route' => ['users.destroy.permission',$userPermission->id]])}}
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    {{__('Eliminar')}}
                                                                </button>
                                                            {{Form::close()}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="4">{{__('No hay registros aún')}}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @include('partials._app_errors')
                                </div>
                            </div>
                            @if($data->roles_id==2)
                            <div class="col-md-12">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">
                                                {{__('Tareas')}}
                                                <span class="badge badge-cobalt-blue tasks">
                                                    {{count($data->tasks)}}
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body">
                                        <ul class="list-group">
                                            @foreach ($stages as $stage)
                                                {{-- @if($loop->index > 1) --}}
                                                @php
                                                    $has = $stage->userTasks($data->id);
                                                    $has_not = $stage->userTasks($data->id,false);
                                                @endphp
                                                    <li class="list-group-item d-flex justify-content-between align-items-center stages">
                                                        <div>
                                                            {{ $stage->name }}
                                                            <span class="badge bg-primary badge-pill text-white">
                                                                <span id="stage{{ $stage->id }}" >
                                                                    {{ count($has) }}
                                                                </span>
                                                                / {{ count($has) + count($has_not)}}
                                                            </span>
                                                        </div>
                                                        <i class="fas fa-angle-down text-primary"></i>
                                                    </li>
                                                    <div class="list-group p-4 border d-none mb--1px">
                                                        @foreach ($has as $task)
                                                            <div class="row d-flex border rounded p-1 mb--1px align-items-center">
                                                                <div class="col-10">
                                                                    <label class="pointer mt-2">
                                                                        <input class="mr-2 input-adjust" type="checkbox" value="{{ $task->id }}"
                                                                        checked id="option{{ $task->id }}">
                                                                        {{ $task->label }}
                                                                    </label>
                                                                </div>
                                                                <button type="button" class="col-2 btn btn-primary btn-sm btn-task btn-40px"
                                                                    data-option='{{ $task->id }}'
                                                                    data-stage='{{ $stage->id }}'
                                                                    >
                                                                    <i class="fas fa-save icon-perfect"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                        @foreach ($has_not as $task)
                                                            <div class="row d-flex border rounded p-1 mb--1px align-items-center">
                                                                <div class="col-10">
                                                                    <label class="pointer mt-2">
                                                                        <input class="mr-2 input-adjust" type="checkbox" value="{{ $task->id }}"
                                                                        id="option{{ $task->id }}">
                                                                        {{ $task->label }}
                                                                    </label>
                                                                </div>
                                                                <button type="button" class="col-2 btn btn-primary btn-sm btn-task btn-40px"
                                                                    data-option='{{ $task->id }}'
                                                                    data-stage='{{ $stage->id }}'
                                                                    >
                                                                    <i class="fas fa-save icon-perfect"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                {{-- @endif --}}
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.select2-p').select2({width: '80%'});
        });
        var token = '{{ csrf_token() }}';
        var user_id = {{ $data->id }};
        var ruta = "{{ route('analyst.task.save') }}";
    </script>
    {!! Html::script('assets/js/clients/task.js') !!}
@endsection
