<!-- Modal -->
<div class="modal fade" id="campOfClientModal" tabindex="-1" role="dialog" aria-labelledby="campOfClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="campOfClientModalLabel">{{__('Asociar campo a cliente')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('admin.cruds.settings.clients.partial._form')
        </div>
    </div>
</div>
