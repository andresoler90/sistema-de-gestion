
<!-- Modal -->
<div class="modal fade" id="tracking" tabindex="-1" aria-labelledby="modalTracking" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTrackingTitle">{{__('Seguimiento')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row table-wrapper-scroll-y my-custom-scrollbar-400">
                <table class="table table-striped table-bordered fixed-head d-table">
                    <thead>
                        <tr>
                            <th>{{__('Documento')}}</th>
                            <th>{{__('Tipo')}}</th>
                            <th>{{__('Vigencia')}}</th>
                            <th>{{__('¿Existe?')}}</th>
                            <th>{{__('Resultado verificación')}}</th>
                        </tr>
                    </thead>
                    <tbody id="modal_table_tracking"></tbody>
                </table>
            </div>
            <div class="row mt-3">
                <fieldset class="scheduler-border w-100">
                    <legend class="scheduler-border">
                        {{__('Seguimiento')}}
                    </legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-row mb-1">
                                <label for="modal_date" class="col-sm-5 col-md-4 col-form-label">
                                    {{__('Fecha de seguimiento')}}:
                                </label>
                                {{Form::date("date", '', ['id'=>'modal_date','class' => "form-control col-sm-7 col-md-8",'readonly'])}}
                            </div>
                            <div class="form-row mb-1">
                                <label for="modal_contact_name" class="col-sm-5 col-md-4 col-form-label">
                                    {{__('Nombre de contacto')}}:
                                </label>
                                {{Form::text("contact_name",'', ['id'=>'modal_contact_name','class' => "form-control col-sm-7 col-md-8",'readonly'])}}
                            </div>
                            <div class="form-row mb-1">
                                <label for="modal_phone" class="col-sm-5 col-md-4 col-form-label">
                                    {{__('Teléfono')}}:
                                </label>
                                {{Form::text("phone",'', ['id'=>'modal_phone','class' => "form-control col-sm-7 col-md-8",'readonly'])}}
                            </div>
                            <div class="form-row mb-1">
                                <label for="modal_email" class="col-sm-5 col-md-4 col-form-label">
                                    {{__('Correo')}}:
                                </label>
                                {{Form::text("email",'', ['id'=>'modal_email', 'class' => "form-control col-sm-7 col-md-8",'readonly'])}}
                            </div>
                            <div class="form-row mb-1">
                                <label for="modal_type_contact" class="col-sm-5 col-md-4 col-form-label">
                                    {{__('Tipo de contacto')}}:
                                </label>
                                {{Form::text("type_contact",'', ['id' =>'modal_type_contact','class' => "form-control col-sm-7 col-md-8",'readonly'])}}
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="modal_observations">{{__('Observaciones')}}:</label>
                                {{Form::textarea("observations",'', ['id'=>'modal_observations', 'class' => "form-control",'readonly', 'rows'=>'3'])}}
                            </div>
                            <div class="form-row">
                                <label for="modal_consecutive_code" class="col-sm-6 col-md-6 col-form-label">
                                    {{__('Código consecutivo')}}:
                                </label>
                                {{Form::text("consecutive_code",'', ['id'=>'modal_consecutive_code','class' => "form-control col-sm-6 col-md-6",'readonly'])}}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                {{ __('Cerrar') }}
            </button>
        </div>
        </div>
    </div>
</div>
