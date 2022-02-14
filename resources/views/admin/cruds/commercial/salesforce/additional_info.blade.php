@php($data = json_decode($salesforce->response_ws))

@if($data)

    <div class="row">
        <div class="col">
            <div class="iq-card iq-card-block iq-card-stretch">
                <div class="iq-card-body">
                    <div
                        class="bg-cobalt-blue p-3 rounded d-flex align-items-center justify-content-between mb-3">
                        <h5 class="text-white">
                            {{__('Información Adicional Salesforce')}}
                        </h5>
                    </div>

                    <div class="row align-items-center justify-content-between mt-3">
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Nombre de la oportunidad')}}</p>
                            @if(isset($data->Name))
                                <h6>{{ $data->Name }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Nombre de la cuenta')}}</p>
                            @if(isset($data->CuentaparaTemplate__c))
                                <h6>{{ $data->CuentaparaTemplate__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Descripción')}}</p>
                            @if(isset($data->Description))
                                <h6>{{ $data->Description }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Fecha de cierre')}}</p>
                            @if(isset($data->CloseDate))
                                <h6>{{ $data->CloseDate }}</h6>
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between mt-3">
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Producto')}}</p>
                            @if(isset($data->OpportunityProductTemplate__c))
                                <h6>{{ $data->OpportunityProductTemplate__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Importe')}}</p>
                            @if(isset($data->CurrencyIsoCode))
                                <h6>{{ $data->CurrencyIsoCode }} {{ number_format($data->Amount,2) }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Probabilidad (%)')}}</p>
                            @if(isset($data->Amount))
                                <h6>{{ $data->Probability }} %</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Descuento aplicado')}}</p>
                            @if(isset($data->Probability))
                                <h6>{{ number_format($data->Descuento_aplicado__c,2) }} %</h6>
                            @endif
                        </div>

                    </div>
                    <div class="row align-items-center justify-content-between mt-3">
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Tipo de oportunidad')}}</p>
                            @if(isset($data->Descuento_aplicado__c))
                                <h6>{{ $data->Tipo_de_oportunidad__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Origen de oportunidad')}}</p>
                            @if(isset($data->Tipo_de_oportunidad__c))
                                <h6>{{ str_replace("_"," ",$data->Origen_de_oportunidad__c) }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Motivo de no interes')}}</p>
                            @if(isset($data->Origen_de_oportunidad__c))
                                <h6>{{ $data->Motivo_de_no_interes__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Etapa Actual')}}</p>
                            <h6> {!! $salesforce->statusSalesforce() !!}</h6>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between mt-3">
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Dirección')}}</p>
                            @if(isset($data->Motivo_de_no_interes__c))
                                <h6>{{ $data->Direcci_n__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Pais')}}</p>
                            @if(isset($data->Direcci_n__c))
                                <h6>{{ $data->Pais__c }}</h6>

                            @endif                    </div>
                        <div class="col-6 col-sm-3">
                            <p class="mb-0">{{__('Ciudad')}}</p>
                            @if(isset($data->Pais__c))
                                <h6>{{ $data->Ciudad__c }}</h6>
                            @endif
                        </div>
                        <div class="col-6 col-sm-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
