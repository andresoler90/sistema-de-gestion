
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
                <th colspan="3">
                    {{ __('Filtros de la búsqueda') }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('Fecha') }}</td>
                <td>{{ isset($data->date_start)? date('Y-m-d H:i',strtotime($data->date_start)):__('Inicio') }}</td>
                <td>{{ isset($data->date_end)? date('Y-m-d H:i',strtotime($data->date_end)):date('Y-m-d H:i') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="7">{{ _('Reporte ANS') }}</th>
            </tr>
            <tr>
                <th>{{__('ID Ticket')}}</th>
                <th>{{__('NIT')}}</th>
                <th>{{__('Razon Social')}}</th>
                <th>{{__('Duración Real')}}</th>
                <th>{{__('Dur. gestión PAR')}}</th>
                <th>{{__('Dur. gestión Proveedor')}}</th>
                <th>{{__('Dur. gestión Cliente')}}</th>
            </tr>
        </thead>
        <tbody>
            @if($totalRegisters > 0 )
                @foreach($register_times->times as $register_time)
                    <tr>
                        <td>{{$register_time->code}}</td>
                        <td>{{$register_time->identification_number}}</td>
                        <td>{{$register_time->business_name}}</td>
                        <td>{{$register_time->real}} {{ __('Hrs') }}</td>
                        <td>{{$register_time->par}} {{ __('Hrs') }}</td>
                        <td>{{$register_time->pro}} {{ __('Hrs') }}</td>
                        <td>{{$register_time->cli}} {{ __('Hrs') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3">{{__('Total')}}</th>
                    <th>{{round($management_time['REAL'],2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['PAR'],2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['PRO'],2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['CLI'],2)}} {{ __('Hrs') }}</th>
                </tr>
                <tr>
                    <th colspan="3">{{__('Promedio')}}</th>
                    <th>{{round($management_time['REAL']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['PAR']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['PRO']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                    <th>{{round($management_time['CLI']/$totalRegisters,2)}} {{ __('Hrs') }}</th>
                </tr>
            @else
                <tr>
                    <td colspan="7">
                        {{ __('Sin resultados') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
