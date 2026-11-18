<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Estimativa de ganhos</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 22px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { width: 45%; }
        .total { background-color: #f1f5f9; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Estimativa de ganhos</h1>
    <p>{{ $estimate['project'] }}</p>

    <table>
        <tbody>
            <tr><th scope="row">Período</th><td>{{ $estimate['start'] }} a {{ $estimate['end'] }}</td></tr>
            <tr><th scope="row">Dias da semana excluídos</th><td>{{ implode(', ', $estimate['excludedWeekdays']) ?: 'Nenhum' }}</td></tr>
            <tr><th scope="row">Dias previstos de trabalho</th><td>{{ $estimate['days'] }}</td></tr>
            <tr><th scope="row">Horas por dia</th><td>{{ $estimate['hoursPerDay'] }}</td></tr>
            <tr><th scope="row">Total de horas</th><td>{{ $estimate['hours'] }}</td></tr>
            <tr><th scope="row">Valor por hora</th><td>{{ $estimate['hourlyRate'] }}</td></tr>
            <tr class="total"><th scope="row">Valor estimado</th><td>{{ $estimate['total'] }}</td></tr>
        </tbody>
    </table>
</body>
</html>
