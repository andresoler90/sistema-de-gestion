@extends('layouts.sgpar')

@section('content')
    @php
        $clients_id = $data->id;
    @endphp
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('clients.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Edición de cliente')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('admin.cruds.clients.partial.form',compact('data'))
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__('Flujo de Proceso')}}
                                    <span class="badge badge-cobalt-blue tasks">
                                        {{count($tasks)}}
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['clients.task.save']])}}
                                {{Form::hidden("client_id", $clients_id)}}
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{__('Nombre')}}</th>
                                        <th>{{__('Tiempo estimado (Hrs)')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            {{Form::hidden("stage_task_id[]", $task->stage_task_id)}}
                                            <tr style="line-height: 14px;">
                                                <td>
                                                    <label class="{{ $task->visible == 'S'? 'pointer':'' }}">
                                                        @if(isset($task->client_task_id))
                                                            <input class="mr-2 input-adjust" type="checkbox" name="checkbox_id[]"
                                                                value="{{ $task->stage_task_id }}" {{ $task->visible == 'N'? 'disabled':'' }}
                                                                checked id="option{{ $task->stage_task_id }}">
                                                        @else
                                                            <input class="mr-2 input-adjust" type="checkbox" name="checkbox_id[]"
                                                                value="{{ $task->stage_task_id }}" {{ $task->visible == 'N'? 'disabled':'' }}
                                                                id="option{{ $task->stage_task_id }}">
                                                        @endif
                                                        {{ $task->stage_task_label }}
                                                    </label>
                                                </td>
                                                <td>
                                                    {{Form::number("ans_time[]", $task->client_task_ans_time ??"",
                                                    ['class' => "form-control",'style'=>"height:30px",'step'=>0.5,'min'=>0, 'placeholder'=>__('En horas')])}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Guardar') }}
                                </button>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @include('admin.cruds.users.partial.list',compact('users', 'clients_id'))
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @include('admin.cruds.clients.documents.index',compact('documents', 'clients_id'))
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    @include('admin.cruds.clients.configurable_words.index',compact('words','clients_id'))
                </div>

                <div class="col-md-6">
                    @include('admin.cruds.clients.countries.index', compact('countries','clients_id'))
                </div>
            </div>

            @component('admin.cruds.settings.clients.table')
                @slot('settingClients',$settingClients)
                @slot('title',__('Campos adicionales'))
                @slot('edit',true)
                @slot('created',false)
            @endcomponent

        </div>
    </div>

    {{-- <script>
        var token = '{{ csrf_token() }}';
        var user_id = {{ $clients_id }};
        var ruta = "{{ route('client.task.save') }}";
    </script>
    {!! Html::script('assets/js/clients/task.js') !!} --}}
@endsection

@section('scripts')
    @parent

@endsection


