@if(!isset($data))
    {{Form::open(['route' => ['registers.store']])}}
@endif
<div class="row">
    <fieldset class="scheduler-border w-100">
        <legend class="scheduler-border">
            {{__('Datos del solicitante')}}
        </legend>
        <div class="row">
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <label for="client_name">{{__('Cliente ancla')}}</label>
                    <span class="d-block">
                        @if(isset($data))
                            {{isset($data->user->client)?$data->user->client->name:''}}
                        @else
                            @if (Auth::user()->roles_id!=5)
                                {{Form::select("clients_id",$clients, null,
                                ['class' => "custom-select select2", 'id'=>'client_name', 'required','placeholder' => 'Selecciona uno...'])}}
                            @else
                                {{isset(Auth::user()->client)?Auth::user()->client->name: ''}}
                            @endif
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="form-group">
                    <label for="client_country_id">{{__('País')}}</label>

                    @if(isset($data))
                        <span class="d-block" id="country_name">
                            {{isset($data->client_country_id)?$data->client_country->name:''}}
                        </span>
                    @else
                        @if (Auth::user()->roles_id!=5)
                            <select name="client_country_id" id="client_country_id" class="custom-select" required>
                                <option value="">{{ __('Selecciona uno...') }}</option>
                            </select>
                        @else
                            <select name="client_country_id" id="client_country_id" class="custom-select" required>
                                <option value="">{{ __('Selecciona uno...') }}</option>
                                @foreach (Auth::user()->client->countries as $country)
                                    <option value="{{ $country->country->id }}">{{ $country->country->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label for="requesting_users_id">{{__('Usuario')}}</label>
                    <span class="d-block">
                        @if(isset($data))
                            {{isset($data)?$data->user->name:Auth::user()->name}}
                        @else
                            @if (Auth::user()->roles_id!=5)
                                {{Form::select("requesting_users_id",[], null,
                                ['class' => "custom-select select2", 'id'=>'requesting_users_id', 'required','placeholder' => 'Selecciona uno...'])}}
                            @else
                                {{isset($data)?$data->user->name:Auth::user()->name}}
                            @endif
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </fieldset>
</div>

<div class="row">
    <fieldset class="scheduler-border w-100">
        <legend class="scheduler-border">
            {{__('Datos básicos de la solicitud')}}
        </legend>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="register_type">{{__('Tipo de registro')}}</label>
                    @if(!isset($data))
                        <select name="register_type" id="register_type" class="custom-select" required placeholder="{{ __('Seleccione...') }}">
                            <option>{{ __('Seleccione...') }}</option>
                            @foreach ( Config::get('options.register_type') as $key => $type)
                                <option value="{{ $key }}">{{ words($type) }}</option>
                            @endforeach
                        </select>
                    @else
                        <span class="d-block">
                            {{ words(Config::get("options.register_type.$data->register_type")) }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="provider_countries_id">{{__('País del proveedor')}}</label>
                    @if(!isset($data))
                        {{Form::select("countries_id",$countries, isset($data)?$data->countries_id:null,
                        ['class' => "custom-select select2", 'id'=>'provider_countries_id', 'required','placeholder' =>  __('Seleccione...')])}}
                    @else
                        <span class="d-block" id='provider_countries' data-provider_countries_id= {{$data->countries_id}}>
                            {{$data->country->name}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="identification_type">{{__('Tipo de identificación')}}</label>
                    @if(!isset($data))
                        {{Form::select("identification_type",Config::get('options.identification_type'), isset($data)?$data->identification_type:null,
                        ['class' => "custom-select select2", 'id'=>'identification_type', 'required','placeholder' => __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                        {{Config::get('options.identification_type.'.$data->identification_type)}}
                    </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-12 col-lg-9" id="identification">
                        <div class="form-group">
                            <label for="identification_number">{{__('Identificación')}}</label>
                            <div class="input-group">
                                @if(!isset($data))
                                    {{Form::text("identification_number", isset($data)?$data->identification_number:"",
                                    ['class' => "form-control", 'id'=> 'identification_number', 'required'])}}
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="d-block">
                                        {{$data->identification_number}}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!isset($data))
                        <div class="col-12 col-lg-3" id='d_check_digit'>
                            <div class="form-group">
                                <label for="check_digit">{{__('DV')}}</label>
                                    {{Form::number("check_digit", isset($data)?$data->check_digit:"",
                                    ['class' => "form-control", 'id'=> 'check_digit', 'required', 'min'=>'0', 'max'=>'9'])}}
                            </div>
                        </div>
                    @endif
                    @if(isset($data) && isset($data->check_digit))
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <label for="check_digit">{{__('Digito de verificación')}}</label>
                                <span class="d-block">
                                    {{ $data->check_digit }}
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 d-none" id="alert_dv">
                        <div class="alert alert-danger">
                            <small>
                                {{__('Por favor ingrese el dígito de verificación (DV)')}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="business_name">{{__('Nombre/Razón social')}}</label>
                    @if(!isset($data))
                        {{Form::text("business_name", isset($data)?$data->business_name:"",
                        ['class' => "form-control", 'id'=>'business_name', 'required'])}}
                    @else
                        <span class="d-block">
                            {{$data->business_name}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="telephone_contact">{{__('Teléfono de contacto')}}</label>
                    @if(!isset($data))
                        {{Form::text("telephone_contact", isset($data)?$data->telephone_contact:"",
                        ['class' => "form-control", 'id'=> 'telephone_contact', 'required'])}}
                    @else
                        <span class="d-block">
                            {{$data->telephone_contact}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="name_contact">{{__('Nombre de contacto')}}</label>
                    @if(!isset($data))
                        {{Form::text("name_contact", isset($data)?$data->name_contact:"",
                        ['class' => "form-control", 'id'=>'name_contact', 'required'])}}
                    @else
                        <span class="d-block">
                            {{$data->name_contact}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="email_contact">{{__('Correo de contacto')}}</label>
                    @if(!isset($data))
                        {{Form::email("email_contact", isset($data)?$data->email_contact:"",
                        ['class' => "form-control", 'id'=>'email_contact', 'required'])}}
                    @else
                        <span class="d-block">
                            {{$data->email_contact}}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if(!isset($data))
            <div class="row d-none" id="box_register_assumed_by">
                <div class="col-12 col-sm-6">
                    <div class="alert alert-primary">
                        <small>
                            {{__('Existe más de un registro por favor informar quien asume el pago')}}
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="register_assumed_by">{{__('Solicitud asumida por')}}</label>
                        {{Form::select("register_assumed_by",Config::get('options.register_assumed_by'),null,
                        ['class' => "custom-select", 'id'=>'register_assumed_by','placeholder' =>  __('Seleccione...')])}}
                    </div>
                </div>
            </div>
            <div class="row d-none" id="responsable">
                <div class="col-12 col-sm-6">
                    <div class="alert alert-danger">
                        <small id="responsable-text"></small>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>{{__('Tipo de proveedor')}}</label>
                        <span class="d-block">
                            @if($data->countries_id == $data->client->countries_id)
                                {{ __('Nacional') }}
                            @else
                                {{ __('Internacional') }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>{{__('Solicitud asumida por')}}</label>
                        <span class="d-block">
                            {{Config::get('options.register_assumed_by.'.$data->register_assumed_by)}}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </fieldset>
</div>

@if(Auth::user()->roles_id == 5)
    @if(!isset($data) || (isset($data) && isset($data->additional_field)))
        @if(setting_client('view_activities'))
            @component('client.additional_fields.activities')
                @if(isset($data))
                    @slot('register',$data)
                @endif
            @endcomponent
        @endif
    @endif
@endif

@if(!isset($data))
    <button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
    {{Form::close()}}
    @include('partials._app_errors')
@endif

@section('scripts')
    @parent
    <script>
        var token = '{{ csrf_token() }}';
        var route_provider = "{{route('register.get.provider')}}";
        var route_pricelist = "{{route('register.get.pricelist')}}";
    </script>
    {!! Html::script('assets/js/registers/get_users.js') !!}
    {!! Html::script('assets/js/registers/check_digit.js') !!}
    {!! Html::script('assets/js/registers/search_provider.js') !!}
    {!! Html::script('assets/js/registers/register_assumed_by.js') !!}
@endsection
