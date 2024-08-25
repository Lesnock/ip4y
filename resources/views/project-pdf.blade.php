@php
    function renderStatus($status) {
        $options = [
            'pendent' => 'Pendente',
            'in-progress' => 'Em progresso',
            'completed' => 'Finalizada'
        ];
        return $options[$status];
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IP4Y</title>
    <style>
        * {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            border: 1px solid #333;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1 style="font-size: 16px">
        Projeto # {{ $project->id }} - {{ $project->title }}
    </h1> <br>

    <div>
        {{ $project->description }} <br>
        Data de conclusão: {{ (new DateTime($project->due_date))->format('d/m/Y') }}
    </div>

    <br>

    <h1 style="font-size: 16px">
        Tarefas
    </h1>

    <div style="padding: 12px">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Responsável</th>
                    <th>Data de Vencimento</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($project->tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ renderStatus($task->status) }}</td>
                    <td>{{ $task->responsible->name }}</td>
                    <td>{{ (new DateTime($task->due_date))->format('d/m/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>