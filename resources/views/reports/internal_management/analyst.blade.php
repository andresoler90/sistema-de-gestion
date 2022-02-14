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
                                <h4 class="card-title">{{__('Reporte de gestion interna por analista')}}</h4>
                            </div>
                            <div class="iq-card-header-toolbar">

                            </div>
                        </div>
                        <div class="iq-card-body">
                           <span>{{ __('Analista') }} : </span>
                           <a href="{{route('users.edit',$analyst->id)}}" title="Ver Analista">
                                {{ $analyst->name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__('Etapa : Verificación')}}
                                    <span class="badge badge-cobalt-blue">{{$verifications->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered d-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Código') }}</th>
                                        <th>{{ __('Tarea') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($verifications))
                                        @foreach ($verifications as $verification)
                                            <tr>
                                                <td>{{ $verification->code }}</td>
                                                <td>{{ $verification->name }}</td>
                                                <td>{{ Config::get("options.management_status.$verification->ans")}}</td>
                                                <td>{{ Config::get("options.status.$verification->status")}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                {{__("No hay registros aún")}}
                                            </td>
                                        </tr>
                                    @endif
                            </table>
                        </div>
                        @if(count($verifications))
                            <div class="card-footer">
                                {{ $verifications->links() }}
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
                                <h4 class="card-title">
                                    {{__('Etapa : Subsanación')}}
                                    <span class="badge badge-cobalt-blue">{{$retrievals->total()}}</span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered d-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Código') }}</th>
                                        <th>{{ __('Tarea') }}</th>
                                        <th>{{ __('Estado ANS') }}</th>
                                        <th>{{ __('Estado') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($retrievals))
                                        @foreach ($retrievals as $retrieval)
                                            <tr>
                                                <td>{{ $retrieval->code }}</td>
                                                <td>{{ $retrieval->name }}</td>
                                                <td>{{ Config::get("options.management_status.$retrieval->ans")}}</td>
                                                <td>{{ Config::get("options.status.$retrieval->status")}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                {{__("No hay registros aún")}}
                                            </td>
                                        </tr>
                                    @endif
                            </table>
                        </div>
                        @if(count($retrievals))
                            <div class="card-footer">
                                {{ $retrievals->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

@endsection

