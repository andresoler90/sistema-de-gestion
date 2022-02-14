{{-- @if(isset($data))
    {{Form::open(['route' => ['clients.countries.update',$data->id]])}}
    @method('put')
@else --}}
    {{Form::open(['route' => ['clients.countries.store']])}}
{{-- @endif --}}
{{-- {{Form::hidden("clients_id", isset($data)?$data->clients_id:$clients_id)}} --}}
{{Form::hidden("clients_id",$clients_id)}}
<div class="form-group">
    <label for="countries_id">{{__('País')}}</label>
        {{-- {{Form::select("countries_id",$countries, isset($data)?$data->countries_id:null, --}}
        {{Form::select("countries_id",$countries,null,
        ['class' => "custom-select", 'id'=>'countries_id', 'required','placeholder' => 'Seleccione...'])}}
</div>
<button type="submit" class="btn btn-primary">{{__('Guardar')}}</button>
{{Form::close()}}

@include('partials._app_errors')
