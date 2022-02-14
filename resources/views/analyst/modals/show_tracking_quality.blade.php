
<!-- Modal -->
<div class="modal fade" id="tracking_quality" tabindex="-1" aria-labelledby="modalTrackingQuality" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalQualityTitle"></h5>
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
                            <th>{{__('¿Cumple?')}}</th>
                            <th>{{__('¿Revisión de digitación?')}}</th>
                            <th>{{__('Comentarios')}}</th>
                        </tr>
                    </thead>
                    <tbody id="modal_table_tracking_quality"></tbody>
                </table>
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
