
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
                <td>{{ $data->date_start?? __('Inicio') }}</td>
                <td>{{ $data->end?? date('Y-m-d') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="6">{{ __('Tareas de los analistas') }}</th>
            </tr>
            <tr>
                <th>{{ __('Analista') }}</th>
                <th>{{ __('Gestión Documental') }}</th>
                <th>{{ __('Verificación') }}</th>
                <th>{{ __('Subsanación') }}</th>
                <th>{{ __('Calidad') }}</th>
                <th>{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(count($analysts_tasks))
                @foreach ($analysts_tasks as $analysts_task)
                    <tr>
                        <td>{{ $analysts_task->users_name }}</td>
                        @foreach ($analysts_task->stages as $stage)
                            <td>{{ $stage->tasks }}</td>
                        @endforeach
                        <td>{{ $analysts_task->total }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>{{ __('Total') }}</td>
                    @foreach ($stage_tasks as $stage_task)
                        <td>{{ $stage_task->tasks }}</td>
                    @endforeach
                    <td>{{ $total }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6">
                        {{__("No hay registros aún")}}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
