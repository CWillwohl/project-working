<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Estimativa de ganhos | Working</title>
    <style>
        @page { margin: 34pt 38pt 52pt; }
        body { margin: 0; color: #202038; font: 9pt/1.45 "DejaVu Sans", sans-serif; }
        h1, p { margin: 0; }
        .hero { padding: 18pt 22pt 20pt; background: #241453; border-top: 5pt solid #4a00ff; border-radius: 8pt; color: #fff; }
        .brand { font-size: 15pt; font-weight: bold; }
        .issued { float: right; color: #e0d7ff; font-size: 8pt; }
        h1 { margin-top: 15pt; font-size: 26pt; line-height: 1.18; }
        .project { margin-top: 8pt; color: #e0d7ff; font-size: 10pt; word-wrap: break-word; }
        .summary { margin: 15pt 0 21pt; padding: 14pt; border: 1pt solid #e2d9fa; border-radius: 6pt; background: #f4f0ff; color: #38217b; }
        .label { font-size: 8pt; font-weight: bold; letter-spacing: .6pt; text-transform: uppercase; }
        .amount { margin-top: 4pt; font-size: 20pt; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { padding: 10pt; border-bottom: .5pt solid #e0e3ee; text-align: left; vertical-align: top; }
        th { width: 43%; color: #4d566b; }
        .heading th { background: #f2f3f8; color: #38217b; font-size: 11pt; }
        .alternate { background: #f8f9fc; }
        footer { position: fixed; right: 0; bottom: -34pt; left: 0; padding-top: 7pt; border-top: .7pt solid #dfe2ee; color: #4d566b; font-size: 8pt; }
    </style>
</head>
<body>
    <footer>Working · Estimativa de ganhos</footer>
    <header class="hero">
        <span class="issued">Emitido em {{ now()->format('d/m/Y') }}</span>
        <p class="brand">Working</p>
        <h1>Estimativa de ganhos</h1>
        <p class="project">Projeto · {{ $estimate['project'] }}</p>
    </header>

    <div class="summary">
        <p class="label">Valor estimado</p>
        <p class="amount">{{ $estimate['total'] }}</p>
    </div>

    <table>
        <thead><tr class="heading"><th colspan="2">Detalhes da estimativa</th></tr></thead>
        <tbody>
            <tr><th scope="row">Período</th><td>{{ $estimate['start'] }} a {{ $estimate['end'] }}</td></tr>
            <tr class="alternate"><th scope="row">Dias da semana excluídos</th><td>{{ implode(', ', $estimate['excludedWeekdays']) ?: 'Nenhum' }}</td></tr>
            <tr><th scope="row">Dias previstos de trabalho</th><td>{{ $estimate['days'] }}</td></tr>
            <tr class="alternate"><th scope="row">Horas por dia</th><td>{{ $estimate['hoursPerDay'] }}</td></tr>
            <tr><th scope="row">Total de horas</th><td>{{ $estimate['hours'] }}</td></tr>
            <tr class="alternate"><th scope="row">Valor por hora</th><td>{{ $estimate['hourlyRate'] }}</td></tr>
        </tbody>
    </table>
</body>
</html>
