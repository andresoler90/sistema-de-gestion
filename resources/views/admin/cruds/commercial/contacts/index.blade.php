<div class="row">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Nombre')}}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Teléfono')}}</th>
                        <th>{{__('Celular')}}</th>
                        <th>{{__('País')}}</th>
                        <th>{{__('Ciudad')}}</th>
                        <th>{{__('Dirección')}}</th>
                        <th>{{__('Acciones')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($contacts))
                        @foreach($contacts as $contact)
                            @php
                                $dataContact = json_decode($contact->data_json);
                            @endphp
                            <tr>
                                <td>
                                    {{$dataContact->Name}}
                                    @if(isset($dataContact->IsPrimary) && $dataContact->IsPrimary == true)
                                        <span class="badge badge-primary">Principal</span>
                                    @endif
                                </td>
                                <td>{{$dataContact->Email}}</td>
                                <td>{{$dataContact->Phone}}</td>
                                <td>{{$dataContact->MobilePhone}}</td>
                                <td>{{$dataContact->Pais__c}}</td>
                                <td>{{$dataContact->Ciudad__c}}</td>
                                <td>{{$dataContact->Direcci_n__c}}</td>
                                <td>
                                    <a href="{{route('commercial.contact.show',[$contact->register,$contact])}}"
                                       title="Ver" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                {{__("Sin registros")}}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
            @if(count($contacts))
                <div class="card-footer">
                    {{$contacts->links()}}
                </div>
            @endif
        </div>
    </div>
</div>

