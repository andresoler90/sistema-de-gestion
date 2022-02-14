@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('analyst.tasks.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>
            @include('analyst.partials.register_detail_card')
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__($register_event->description)}}
                                    <span class="badge badge-cobalt-blue">
                                        {{ count($client_documents) }}
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['task.verification.store'],'id'=>'form-verification'])}}
                            {{Form::hidden("registers_id",$register->id)}}
                            {{Form::hidden("register_tasks_id",$register_task->id)}}
                            {{Form::hidden("register_event_id",$register_event->id)}}
                            <div class="row table-wrapper-scroll-y my-custom-scrollbar-auto">
                                <table class="table table-striped table-bordered fixed-head d-table">
                                    <thead>
                                        <tr>
                                            <th>{{__('Documento')}}</th>
                                            <th>{{__('Tipo')}}</th>
                                            <th>{{__('Vigencia')}}</th>
                                            <th>{{__('¿Cumple?')}}</th>
                                            <th>{{__('Resultado verificación')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($client_documents as $document)
                                        {{Form::hidden("client_documents_id[]",$document->id,['class'=>'document'])}}
                                        <tr>
                                            <td>
                                                {{ $document->document->name }}
                                                @if($document->commentary)
                                                    <span class="d-block">{{ $document->commentary }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($document->document_type == 'OB')
                                                    <span class="badge border border-success text-success">{{ $document->document_type_name }}</span>
                                                @else
                                                    <span class="badge border border-warning text-warning">{{ $document->document_type_name }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $document->validity }}</td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-text custom-control-inline">
                                                    <div class="custom-switch-inner">
                                                    <input type="checkbox" name="satisfy[]" class="custom-control-input satisfy" id="satisfy-{{ $loop->index }}" value="{{ $document->id }}">
                                                    <label class="custom-control-label" for="satisfy-{{ $loop->index }}" data-on-label="Si" data-off-label="No">
                                                    </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="outcome[]" class="form-control outcome">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2 btn-question">{{__('Guardar')}}</button>
                            {{Form::close()}}
                            @include('partials._app_errors')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-body">
                            <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                               <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                        {{ __('Tiquetes relacionados') }}
                                    </a>
                               </li>
                            </ul>
                            <div class="tab-content" id="myTabContent-2">
                               <div class="tab-pane fade active show table-wrapper-scroll-y my-custom-scrollbar-200" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-striped table-bordered fixed-head d-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('N° de Tiquete')}}</th>
                                        <th>{{__('Cliente Ancla')}}</th>
                                        <th>{{__('Tipo de registro')}}</th>
                                        <th>{{__('Tipo de proveedor')}}</th>
                                        <th>{{__('Prioridad')}}</th>
                                        <th>{{__('Etapa')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($my_registers as $register)
                                            <tr>
                                                <td>
                                                    <a href="{{route(config('tasks-routes.'.$register->task->name),$register->id)}}"
                                                        class="btn btn-sm btn-link">
                                                        {{ $register->register->code }}
                                                    </a>
                                                </td>
                                                <td>{{ $register->register->client->name }}</td>
                                                <td>
                                                    {{ words(Config::get("options.register_type.".$register->register->register_type)) }}
                                                </td>
                                                <td>{{ $register->register->provider_type_name }}</td>
                                                <td>{{ $register->priority_name }}</td>
                                                <td>{{ $register->register->stage->name }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach ($other_registers as $register)
                                            <tr>
                                                <td>{{ $register->register->code }}</td>
                                                <td>{{ $register->register->client->name }}</td>
                                                <td>
                                                    {{ words(Config::get("options.register_type.".$register->register->register_type)) }}
                                                </td>
                                                <td>{{ $register->register->provider_type_name }}</td>
                                                <td>{{ $register->priority_name }}</td>
                                                <td>{{ $register->register->stage->name }}</td>
                                            </tr>
                                        @endforeach
                                        @if(count($my_registers) == 0 && count($other_registers) == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    {{__("No hay registros aún")}}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                               </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
