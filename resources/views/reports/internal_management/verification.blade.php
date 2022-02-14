@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <a href="{{ route('reports.internal.management.index') }}" role="button" class="btn btn-primary mb-3">
                <i class="fas fa-caret-left"></i>
                {{__('Regresar')}}
            </a>

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Verificación Básica')}}
                                    <span class="badge badge-cobalt-blue">{{$verification_1->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Analista') }}</th>
                                        <th>{{ __('Solicitud') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($verification_1))
                                        @foreach ( $verification_1 as $task)
                                            <tr>
                                                <td>{{ $task->users_name}}</td>
                                                <td>{{ $task->code}}</td>
                                                <td>{{ Config::get("options.status.$task->status")}}</td>
                                                <td>{{ Config::get("options.management_status.$task->ans")}}</td>
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
                        @if(count($verification_1))
                            <div class="card-footer">
                                {{ $verification_1->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Verificación de Experiencias')}}
                                    <span class="badge badge-cobalt-blue">{{$verification_2->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Analista') }}</th>
                                        <th>{{ __('Solicitud') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($verification_2))
                                        @foreach ( $verification_2 as $task)
                                            <tr>
                                                <td>{{ $task->users_name}}</td>
                                                <td>{{ $task->code}}</td>
                                                <td>{{ Config::get("options.status.$task->status")}}</td>
                                                <td>{{ Config::get("options.management_status.$task->ans")}}</td>
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
                        @if(count($verification_2))
                            <div class="card-footer">
                                {{ $verification_2->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Verificación Financiera')}}
                                    <span class="badge badge-cobalt-blue">{{$verification_3->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Analista') }}</th>
                                        <th>{{ __('Solicitud') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($verification_3))
                                        @foreach ( $verification_3 as $task)
                                            <tr>
                                                <td>{{ $task->users_name}}</td>
                                                <td>{{ $task->code}}</td>
                                                <td>{{ Config::get("options.status.$task->status")}}</td>
                                                <td>{{ Config::get("options.management_status.$task->ans")}}</td>
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
                        @if(count($verification_3))
                            <div class="card-footer">
                                {{ $verification_3->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Verificación de Documentos del Cliente')}}
                                    <span class="badge badge-cobalt-blue">{{$verification_4->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Analista') }}</th>
                                        <th>{{ __('Solicitud') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($verification_4))
                                        @foreach ( $verification_4 as $task)
                                            <tr>
                                                <td>{{ $task->users_name}}</td>
                                                <td>{{ $task->code}}</td>
                                                <td>{{ Config::get("options.status.$task->status")}}</td>
                                                <td>{{ Config::get("options.management_status.$task->ans")}}</td>
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
                        @if(count($verification_4))
                            <div class="card-footer">
                                {{ $verification_4->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
