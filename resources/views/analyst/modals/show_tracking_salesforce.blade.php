<!-- Modal -->
<div class="modal fade" id="tracking_salesforce" tabindex="-1" aria-labelledby="modalTracking" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTrackingTitle">{{__('Seguimiento')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if(count($register->salesforce_contacts))
                    <div class="row table-wrapper-scroll-y my-custom-scrollbar-400">
                        <h5>
                            {{__('Contactos')}}
                            <span class="badge badge-primary">
                                {{count($register->salesforce_contacts)}}
                            </span>
                        </h5>
                        <table class="table table-striped table-bordered fixed-head d-table">
                            <thead>
                            <tr>
                                <th>{{__('Nombre')}}</th>
                                <th>{{__('Correo')}}</th>
                                <th>{{__('Cargo')}}</th>
                                <th>{{__('Telefono')}}</th>
                                <th>{{__('Celular')}}</th>
                                <th>{{__('País')}}</th>
                                <th>{{__('Dirección')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($register->salesforce_contacts as $contacts)
                                @php($dataJson = json_decode($contacts->data_json))
                                <tr>
                                    <td>
                                        {{$dataJson->Name}}
                                        @if(isset($dataJson->IsPrimary) && $dataJson->IsPrimary == true)
                                            <span class="badge badge-primary">Principal</span>
                                        @endif
                                    </td>
                                    <td>{{$dataJson->Email}}</td>
                                    <td>{{$dataJson->Title}}</td>
                                    <td>{{$dataJson->Phone}}</td>
                                    <td>{{$dataJson->MobilePhone}}</td>
                                    <td>{{$dataJson->Pais__c}}</td>
                                    <td>{{$dataJson->Direcci_n__c}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(count($register->salesforce_activities))
                    <div class="row table-wrapper-scroll-y my-custom-scrollbar-400">
                        <h5>
                            {{__('Actividades')}}
                            <span
                                class="badge badge-primary">{{count($register->salesforce_activities)}}
                            </span>
                        </h5>
                        <table class="table table-striped table-bordered fixed-head d-table">
                            <thead>
                            <tr>
                                <th>{{__('Estado')}}</th>
                                <th>{{__('Prioridad')}}</th>
                                <th>{{__('Asunto')}}</th>
                                <th>{{__('Comentario')}}</th>
                                <th>{{__('Fecha vencimiento')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($register->salesforce_activities as $activity)
                                @php($dataJson = json_decode($activity->data_json))
                                <tr>
                                    <td>{{$dataJson->Status}}</td>
                                    <td>{{$dataJson->Priority}}</td>
                                    <td>{{$dataJson->Subject}}</td>
                                    <td>{{$dataJson->Description}}</td>
                                    <td>{{$dataJson->ActivityDate}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    {{ __('Cerrar') }}
                </button>
            </div>
        </div>
    </div>
</div>
