<div class="row">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Estado')}}</th>
                        <th>{{__('Prioridad')}}</th>
                        <th>{{__('Asunto')}}</th>
                        <th>{{__('Comentario')}}</th>
                        <th>{{__('Divisa')}}</th>
                        <th>{{__('Fecha vencimiento')}}</th>
                        <th>{{__('Acciones')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($activities))
                        @foreach($activities as $activity)
                            @php
                                $dataActivity = json_decode($activity->data_json);
                            @endphp
                            <tr>
                                <td>{{$dataActivity->Status}}</td>
                                <td>{{$dataActivity->Priority}}</td>
                                <td>{{$dataActivity->Subject}}</td>
                                <td>{{$dataActivity->Description}}</td>
                                <td>{{$dataActivity->CurrencyIsoCode}}</td>
                                <td>{{$dataActivity->ActivityDate}}</td>
                                <td>
                                    <a href="{{route('commercial.activity.show',[$activity->register,$activity])}}"
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
            @if(count($activities))
                <div class="card-footer">
                    {{$activities->links()}}
                </div>
            @endif
        </div>
    </div>
</div>

