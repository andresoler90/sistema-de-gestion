@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('priority.tasks.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Cambiar prioridad de la tarea')}}</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['priority.tasks.store']])}}
                                {{Form::hidden('task_id',$task->id)}}
                            <div class="form-group">
                                <label for="priority">{{__('Prioridad')}}</label>
                                {{Form::select("priority",Config::get('options.priority'),$task->priority,
                                ['class' => "custom-select", 'id'=>'priority','placeholder' =>  __('Seleccione...')])}}
                            </div>
                            <div class="form-group">
                                <label for="analyst">{{__('Re asignar')}}</label>
                                {{Form::select("analyst",$analyst,$task->analyst->id,
                                ['class' => "custom-select", 'id'=>'analyst','placeholder' =>  __('Seleccione...')])}}
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>

                            {{Form::close()}}
                            @include('partials._app_errors')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
