
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <style>
        tr td {
    background-color: #ffffff;
}

tr > td {
    border-bottom: 1px solid #000000;
}
    </style>
</head>
<body>
    <table>
        <tr>
            <th>{{ __('Solicitud') }}</th>
            <th>{{ $register->code }}</th>
        </tr>
    </table>

    <table>
        <thead>
        <tr>
            <th>{{__('Etapa')}}</th>
            <th>{{__('Tarea')}}</th>
            <th>{{__('Duración estimada')}}</th>
            <th>{{__('Duración Real')}}</th>
            <th>{{__('Dur. gestión PAR')}}</th>
            <th>{{__('Dur. gestión Proveedor')}}</th>
            <th>{{__('Dur. gestión Cliente')}}</th>
            <th>{{__('Cumple ANS')}}</th>
        </tr>
        </thead>
        <tbody>
            @if(count($register_times->times) > 0 )
                @foreach ($register_times->times as $task)
                    <tr>
                        <td>{{ $task->stage }}</td>
                        <td>{{ $task->task }}</td>
                        <td>{{ $task->estimate }} {{ __('Hrs') }}</td>
                        <td>{{ $task->real }} {{ __('Hrs') }}</td>
                        <td>{{ $task->par }} {{ __('Hrs') }}</td>
                        <td>{{ $task->pro }} {{ __('Hrs') }}</td>
                        <td>{{ $task->cli }} {{ __('Hrs') }}</td>
                        <td>{{ __($task->ans) }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <th colspan="2">{{ __('Total') }}</th>
                        <td>{{ $total['estimate'] }} {{ __('Hrs') }}</td>
                        <td>{{ $total['real'] }} {{ __('Hrs') }}</td>
                        <td>{{ $total['par'] }} {{ __('Hrs') }}</td>
                        <td>{{ $total['pro'] }} {{ __('Hrs') }}</td>
                        <td>{{ $total['cli'] }} {{ __('Hrs') }}</td>
                        <td>{{ $total['ans'] }} %</td>
                    </tr>
            @else
                <tr>
                    <td colspan="8">
                        {{ __('Sin resultados') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
