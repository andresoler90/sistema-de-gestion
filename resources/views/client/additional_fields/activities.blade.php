<div class="row">
    <fieldset class="scheduler-border w-100">
        <legend class="scheduler-border">
            {{__('Actividades')}}
        </legend>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="act1_id">{{__('Maestra')}}</label>
                    @if(!isset($register))
                        {{Form::select("act1_id",[],null,
                        ['class' => "custom-select", 'id'=>'act1_id', 'placeholder' => __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->master_name()}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="act10_id">{{__('Tipo')}}</label>
                    @if(!isset($register))
                        {{Form::select("act10_id",[],null,
                        ['class' => "custom-select", 'id'=>'act10_id', 'placeholder' => __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->type_name()}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="act11_id">{{__('Categoria')}}</label>
                    @if(!isset($register))
                        {{Form::select("act11_id",[],null,
                        ['class' => "custom-select", 'id'=>'act11_id', 'placeholder' => __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->category_name()}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="act3_id">{{__('Grupo')}}</label>
                    @if(!isset($register))
                        {{Form::select("act3_id",[],null,
                        ['class' => "custom-select", 'id'=>'act3_id', 'placeholder' =>  __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->group_name()}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="act2_id">{{__('Actividad')}}</label>
                    @if(!isset($register))
                        {{Form::select("act2_id",[],null,
                        ['class' => "custom-select", 'id'=>'act2_id', 'placeholder' => __('Seleccione...')])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->activity_name()}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="code_activity">{{__('Código actividad')}}</label>
                    @if(!isset($register))
                        {{Form::text("code_activity",'',
                        ['class' => "form-control", 'id'=>'code_activity'])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->code_activity}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="code_intern">{{__('Código Interno')}}</label>
                    @if(!isset($register))
                        {{Form::text("code_intern",'',
                        ['class' => "form-control", 'id'=>'code_intern'])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->code_intern}}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="experience_verify">{{__('Experiencia Verificada')}}</label>
                    @if(!isset($register))
                        {{Form::checkbox("experience_verify",null,'',
                        ['class' => "form-check", 'id'=>'experience_verify'])}}
                    @else
                        <span class="d-block">
                            {{$register->additional_field->verify_status()}}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </fieldset>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">

        // Carga de actividades select Maestra
        $(document).ready(function () {
            let act1_id = '{{@$register->additional_field->act1_id}}';
            getUrlMaster(act1_id)
        });

        //si el registro escogido es liviano se debe escoger de la matriz de actividades una obligatoriamente
        $('#register_type').on('change', function () {
            let type = $('select[id=register_type]').val();
            if (type === 'L'){
                $('#act1_id').prop("required", true)
                $('#act2_id').prop("required", true)
                $('#act3_id').prop("required", true)
                $('#act10_id').prop("required", true)
                $('#act11_id').prop("required", true)
            }else {
                $('#act1_id').prop("required", false)
                $('#act2_id').prop("required", false)
                $('#act3_id').prop("required", false)
                $('#act10_id').prop("required", false)
                $('#act11_id').prop("required", false)
            }
        });

        // MAESTRA
        $('#act1_id').on('change', function () {

            let act1_id = $('#act1_id').val();

            emptySelect('act10_id');
            emptySelect('act11_id');
            emptySelect('act3_id');
            emptySelect('act2_id');

            if (act1_id !== '') {
                getUrlType(act1_id)
            }
        });
        // TIPO
        $('#act10_id').on('change', function () {

            let act1_id = $('#act1_id').val();
            let act10_id = $('#act10_id').val();

            emptySelect('act11_id');
            emptySelect('act3_id');
            emptySelect('act2_id');

            if (act10_id !== '') {
                getUrlCategory(act1_id, act10_id)
            }
        });
        //CATEGORIA
        $('#act11_id').on('change', function () {

            let act1_id = $('#act1_id').val();
            let act10_id = $('#act10_id').val();
            let act11_id = $('#act11_id').val();

            emptySelect('act3_id');
            emptySelect('act2_id');

            if (act11_id !== '') {
                getUrlGroup(act1_id, act10_id, act11_id)
            }
        });
        // GRUPO
        $('#act3_id').on('change', function () {

            let act1_id = $('#act1_id').val();
            let act10_id = $('#act10_id').val();
            let act11_id = $('#act11_id').val();
            let act3_id = $('#act3_id').val();

            emptySelect('act2_id');

            if (act3_id !== '') {
                getUrlActivity(act1_id, act10_id, act11_id, act3_id)
            }
        });

        // Consultamos maestra
        function getUrlMaster(act1_id = '') {
            $.get(`../../../activities/master/`, function (result, status) {
                $.each(result, function (key, value) {
                    $('#act1_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (act1_id !== '') {
                    $('#act1_id').val(act1_id) //se asigna valor si existe en base
                }
            })
        }

        // Consultamos tipo
        function getUrlType(act1_id, act10_id = '') {
            $.get(`../../../activities/type/${act1_id}`, function (result, status) {
                $.each(result, function (key, value) {
                    $('#act10_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (act10_id !== '') {
                    $('#act10_id').val(act10_id) //se asigna valor si existe en base
                }
            })
        }

        // Consultamos categoria
        function getUrlCategory(act1_id, act10_id, act11_id = '') {
            $.get(`../../../activities/category/${act1_id}/${act10_id}`, function (result, status) {
                $.each(result, function (key, value) {
                    $('#act11_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (act11_id !== '') {
                    $('#act11_id').val(act11_id) //se asigna valor si existe en base
                }
            })
        }

        // Consultamos grupo
        function getUrlGroup(act1_id, act10_id, act11_id, act3_id = '') {
            $.get(`../../../activities/group/${act1_id}/${act10_id}/${act11_id}`, function (result, status) {
                $.each(result, function (key, value) {
                    $('#act3_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (act3_id !== '') {
                    $('#act3_id').val(act3_id) //se asigna valor si existe en base
                }
            })
        }

        // Consultamos actividad
        function getUrlActivity(act1_id, act10_id, act11_id, act3_id, act2_id = '') {
            $.get(`../../../activities/activity/${act1_id}/${act10_id}/${act11_id}/${act3_id}`, function (result, status) {
                $.each(result, function (key, value) {
                    $('#act2_id').append(
                        $("<option></option>")
                            .attr("value", key)
                            .text(value)
                    );
                });
                if (act2_id !== '') {
                    $('#act2_id').val(act2_id) //se asigna valor si existe en base
                }
            })
        }

        // Vaciamos los select
        function emptySelect($id) {
            $(`#${$id}`).empty().append(
                $("<option></option>")
                    .attr("value", '')
                    .text('Seleccione...')
            );
        }
    </script>
@endsection
