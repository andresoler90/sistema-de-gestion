
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="2">
                    {{ __('Filtros de la búsqueda') }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('Cliente') }}</td>
                <td>
                    @if(Auth::user()->roles_id == 1)
                        {{ $data->client? $data->client_name : __('Todos') }}
                    @else
                        {{ Auth::user()->client->name }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ __('Estado') }}</td>
                <td>{{ $data->state? $data->state_name : __('Todos') }}</td>
            </tr>
            <tr>
                <td>{{ __('Etapa') }}</td>
                <td>{{ $data->stage? $data->stage_name : __('Todas') }}</td>
            </tr>
            <tr>
                <td>{{ __('Fecha') }}</td>
                <td>{{ $data->date_start?? __('Inicio') }} / {{ $data->end?? date('Y-m-d') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">{{ __('Estado de las solicitudes') }}</th>
            </tr>
            <tr >
                <th >{{ __('Estado') }}</th>
                <th >{{ __('Cantidad') }}</th>
                <th >{{ __('Porcentaje') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(count($registers_states ))
            @foreach ($registers_states as $registers_state)
                <tr >
                    <td >{{ $registers_state->name }}</td>
                    <td >{{ $registers_state->total }}</td>

                    @php($porcentaje = $total==0? $total : round($registers_state->total * 100 / ($total), 1))

                    <td >{{ $porcentaje }}%</td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="3" >
                        {{ __('Sin resultados') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">{{ __('Etapa de las solicitudes') }}</th>
            </tr>
            <tr>
                <th>{{ __('Etapa') }}</th>
                <th>{{ __('Cantidad') }}</th>
                <th>{{ __('Porcentaje') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(count($registers_stages ))
            @foreach ($registers_stages as $registers_stage)
                <tr >
                    <td >{{ $registers_stage->name }}</td>
                    <td >{{ $registers_stage->total }}</td>

                    @php($porcentaje = $total==0? $total : round($registers_stage->total * 100 / $total, 1))

                    <td >{{ $porcentaje }}%</td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="3">
                        {{ __('Sin resultados') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">{{ __('Responsable de la gestión de la solicitud') }}</th>
            </tr>
            <tr>
                <th>{{ __('Responsable') }}</th>
                <th>{{ __('Cantidad') }}</th>
                <th>{{ __('Porcentaje') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(count($registers_responsables))
            @foreach ($registers_responsables as $registers_responsable)
                <tr >
                    <td >{{ $registers_responsable->name }}</td>
                    <td >{{ $registers_responsable->total }}</td>

                    @php($porcentaje = $total==0? $total : round($registers_responsable->total * 100 / $total, 1))

                    <td >{{ $porcentaje }}%</td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="3">
                        {{ __('Sin resultados') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
