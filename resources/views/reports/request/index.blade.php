@extends('layouts.sgpar')

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    {{__('Estado de las solicitudes')}}
                                </h4>
                            </div>
                            <div class="iq-card-header-toolbar">
                                <a href="{{route('reports.request.index')}}" class="btn btn-primary" title="{{ __('Borrar filtros') }}">
                                   <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {{Form::open(['route' => ['reports.request.search'],'id'=>'form-filter'])}}
                            @method('GET')
                            {{Form::hidden('orderBy','codigo',['id'=>'orderBy'])}}
                            {{Form::hidden('order','ASC',['id'=>'order'])}}
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('code', __('Código'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("code", isset($data)?$data->code:"", ['id'=>'code','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('identification', __('Identificación'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("identification", isset($data)?$data->identification:"", ['id'=>'identification','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('business_name', __('Razón social'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("business_name", isset($data)?$data->business_name:"", ['id'=>'business_name','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('client', __('Cliente Ancla'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::text("client", isset($data)?$data->client:"", ['id'=>'client','class' => "form-control col-12 col-sm-8"]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('stage', __('Etapa'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{Form::select("stage",$stages, isset($data)?$data->stage:null,
                                        ['class' => "custom-select", 'id'=>'stage','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="form-row mb-2">
                                        {{ Form::label('state', __('Estado'),['class'=>'col-form-label col-12 col-sm-4']) }}
                                        {{ Form::select("state",$states, isset($data)?$data->state:null,
                                        ['class' => "custom-select", 'id'=>'state','class' => "form-control col-12 col-sm-8",'placeholder' => 'Seleccione...'])}}
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-row mb-2">
                                        {{ Form::label('date_start', __('Fecha'),['class'=>'col-form-label d-inline col-12 col-sm-2']) }}
                                        <div class="d-inline-block col-12 col-sm-10">
                                            {{ Form::date("date_start", isset($data)?$data->date_start:"", ['id'=>'date_start','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                            {{ Form::date("date_end", isset($data)?$data->date_end:"", ['id'=>'date_end','class' => "form-control d-inline", 'style'=>'max-width:175px']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                {{ __('Buscar') }}
                            </button>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">{{__('Solicitudes de clientes')}}
                                    <span class="badge badge-cobalt-blue">
                                        {{$registers->total()}}
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>
                                        {{__('Código')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'codigo')
                                        @endcomponent
                                    </th>

                                    <th>
                                        {{__('Identificación')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'identificacion')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Razón Social')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'razon_social')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Cliente')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'cliente')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Etapa')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'etapa')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Estado')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'estado')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Gestión')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'gestion')
                                        @endcomponent
                                    </th>
                                    <th>
                                        {{__('Fecha creación')}}
                                        @component('components.sort_by')
                                            @slot('data', $data??null)
                                            @slot('column', 'fecha')
                                        @endcomponent
                                    </th>
                                    <th>{{__('% de progreso')}}</th>
                                    <th>{{__('Opciones')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($registers))
                                @foreach($registers as $register)
                                    <tr>
                                        <td>{{$register->code}}</td>
                                        <td>{{$register->identification_number}}</td>
                                        <td>{{$register->business_name}}</td>
                                        <td>
                                            @if(Auth::user()->roles_id==1)
                                                <a href="{{route('clients.edit',$register->client->id)}}">
                                                    {{$register->client->name}}
                                                </a>
                                            @else
                                                {{$register->client->name}}
                                            @endif
                                        </td>
                                        <td>{{$register->stage->name}}</td>
                                        <td>{{$register->state->name}}</td>
                                        <td>
                                            @php($management = $register->management)
                                            @switch($management)
                                                @case('PAR')
                                                    @php($color = 'primary')
                                                    @break
                                                @case('PRO')
                                                    @php($color = 'success')
                                                    @break
                                                @case('CLI')
                                                    @php($color = 'danger')
                                                    @break
                                            @endswitch
                                            <span class="badge bg-{{ $color }} text-white">
                                                {{config('options.management.'.$management) }}
                                            </span>

                                        </td>
                                        <td>{{$register->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{ $register->previous_stage->accumulated }}%</td>
                                        <td>
                                            <a href="{{route('reports.request.detail',$register->id)}}" class="btn btn-primary btn-sm">Detalle</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10" class="text-center">
                                        {{__("No hay registros aún")}}
                                    </td>
                                </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer">
                            {{$registers->appends([
                                'code'          =>$data->code??null,
                                'identification'=>$data->identification??null,
                                'business_name' =>$data->business_name??null,
                                'client'        =>$data->client??null,
                                'stage'         =>$data->stage??null,
                                'state'         =>$data->state??null,
                                'gestion'         =>$data->gestion??null,
                                'date_start'    =>$data->date_start??null,
                                'date_end'      =>$data->date_end??null,
                                'orderBy'       =>$data->orderBy??null,
                                'order'         =>$data->order??null,
                            ])->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
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
