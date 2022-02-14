<div class="row">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        {{__('Estados Salesforce')}}
                        <span class="badge badge-cobalt-blue">{{ count($register_salesforce) }}</span>
                    </h4>
                </div>
            </div>
            <div class="iq-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Estado')}}</th>
                        <th>{{__('Fecha ejecución')}}</th>
                        <th>{{__('Acciones')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($register_salesforce))
                    @foreach ($register_salesforce as $salesforce)
                        <tr>
                            <td>
                                {!! $salesforce->status() !!}
                            </td>
                            <td>
                                {{$salesforce->dateExecute()}}
                            </td>
                            <td>
                                @if($salesforce->status == 'P')
                                    <a href="{{route('execute.salesforce.opportunity',$salesforce->id)}}"
                                       role="button" class="btn btn-secondary mb-3">
                                        <i class="fas fa-cogs"></i>
                                        {{__('Ejecutar Salesforce')}}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">
                                {{__("No hay registros aún")}}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            @if(count($register_salesforce))
                <div class="card-footer">
                    {{$register_salesforce->links()}}
                </div>
            @endif
        </div>
    </div>
</div>

