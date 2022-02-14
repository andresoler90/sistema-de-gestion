@if(isset($data))
    {{Form::open(['route' => ['price.list.update',$data->id]])}}
    @method('put')
@else
    {{Form::open(['route' => ['price.list.store']])}}
@endif
<div class="form-group">
    <label for="name">{{__('Nombre')}}</label>
    {{Form::text("name", isset($data)?$data->name:"", ['id'=>'name', 'class' => "form-control"])}}
</div>
<div class="form-group">
    <label for="countries_id">{{__('País')}}</label>
        {{Form::select("countries_id",$countries, isset($data)?$data->countries_id:null,
        ['class' => "custom-select", 'id'=>'countries_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<div class="form-group">
    <label for="register_assumed_by">{{__('Asumido por')}}</label>
    {{Form::select("register_assumed_by",Config::get('options.register_assumed_by'), isset($data)?$data->register_assumed_by:null,
    ['class' => "custom-select", 'id'=>'register_assumed_by', 'required','placeholder' => __('Seleccione...')])}}
</div>
<div class="form-group">
    <label for="">{{__('Cliente ancla')}}</label>
    {{Form::select("clients_id",$clients, isset($data)?$data->clients_id:null,
    ['class' => "custom-select", 'id'=>'clients_id','placeholder' => 'Seleccione...','required'])}}
</div>
<div class="form-group">
    <label for="register_type">{{__('Tipo de registro')}}</label>
    <select name="register_type" id="register_type" class="custom-select" required placeholder="{{ __('Seleccione...') }}">
        <option>{{ __('Seleccione...') }}</option>
        @foreach ( Config::get('options.register_type') as $key => $type)
            <option value="{{ $key }}" {{ (isset($data) && $data->register_type ==$key)? 'selected':'' }}>
                {{ words($type) }}
            </option>
        @endforeach
    </select>
    {{-- {{Form::select("register_type",Config::get('options.register_type'), isset($data)?$data->register_type:null,
    ['class' => "custom-select", 'id'=>'register_type', 'required','placeholder' => __('Seleccione...')])}} --}}
</div>
<div class="form-group">
    <label for="provider_type">{{__('Tipo de proveedor')}}</label>
    {{Form::select("provider_type",Config::get('options.provider_type'), isset($data)?$data->provider_type:null,
    ['class' => "custom-select", 'id'=>'provider_type','placeholder' => __('Seleccione...')])}}
</div>

<div class="form-group">
    <label for="currency">{{__('Divisa')}}</label>
    {{Form::select("currency",Config::get('options.currency'), isset($data)?$data->currency:null,
    ['class' => "custom-select", 'id'=>'currency', 'required','placeholder' => __('Seleccione...')])}}
</div>

<div class="form-group">
    <label for="salesforce_id">{{__('Asociar SalesForce')}}</label>
    {{Form::select("salesforce_id",[],null,['class' => "custom-select select2", 'id'=>'salesforce_id','placeholder' => 'Seleccione...'])}}
</div>

<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')

@section('scripts')
    @parent
    <script type="text/javascript">

        $(document).ready(function () {
            let sf_id = '{{@$data->salesforce_id}}';
            getPriceSalesforce(sf_id)
        });

        // consulta de lista de precios salesforce
        function getPriceSalesforce(sf_id = '') {
            $.get("{{route('price.list.salesforce')}}", function (result, status) {
                $.each(result, function (key, value) {
                    $('#salesforce_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (sf_id !== '') {
                    $('#salesforce_id').val(sf_id)
                }
            })
        }

    </script>

@endsection
