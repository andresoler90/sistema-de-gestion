<div class="row">
    <div class="col">
        <div class="iq-card iq-card-block iq-card-stretch">
            <div class="iq-card-body">
                <div
                    class="bg-cobalt-blue p-3 rounded d-flex align-items-center justify-content-between mb-3">
                    <h5 class="text-white">
                        {{ $title??$register_task->task->label}}
                        <br>
                        {{__('SOLICITUD - ').$register->code}}
                    </h5>
                    <div class="rounded-circle iq-card-icon bg-white">
                        <i class="ri-layout-line text-cobalt-blue"></i>
                    </div>
                </div>
                <h4 class="mb-2">{{$register->business_name}}</h4>
                <div class="row align-items-center justify-content-between mt-3">
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Tipo de solicitud')}}</p>
                        <h6>
                            {{ words(Config::get("options.register_type.".$register->register_type)) }}
                        </h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Estado')}}</p>
                        <h6>{{$register->state->name}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('País')}}</p>
                        <h6>{{$register->country->name}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Identificación')}}</p>
                        <h6>{{$register->identification_type.' '.$register->identification_number}}</h6>
                    </div>
                </div>
                <div class="row align-items-center justify-content-between mt-3">
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Telefono')}}</p>
                        <h6>{{$register->telephone_contact}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Contacto')}}</p>
                        <h6>{{$register->name_contact}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Email')}}</p>
                        <h6>{{$register->email_contact}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Responsable del pago')}}</p>
                        <h6>{{$register->assumed_by_name}}</h6>
                    </div>

                </div>
                <div class="row align-items-center justify-content-between mt-3">
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Cliente')}}</p>
                        <h6>
                            {{$register->client->name}}
                        </h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Solicitado por')}}</p>
                        <h6>{{$register->user->name}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Creado por')}}</p>
                        <h6>{{$register->creatorUser->name}}</h6>
                    </div>
                    <div class="col-6 col-sm-3">
                        <p class="mb-0">{{__('Fecha de creación')}}</p>
                        <h6>
                            {{$register->created_at}}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
