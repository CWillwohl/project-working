<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de recebimentos | Working</title>
    <style>
        @page {
            margin: 34pt 38pt 52pt;
        }

        body {
            margin: 0;
            color: #202038;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9pt;
            line-height: 1.35;
        }

        h1, p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .hero {
            padding: 15pt 22pt 18pt;
            background: #241453;
            border-top: 5pt solid #4a00ff;
            border-radius: 8pt;
            color: #fff;
        }

        .brand {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: -0.4pt;
        }

        .issued {
            color: #e0d7ff;
            font-size: 8pt;
            text-align: right;
            white-space: nowrap;
        }

        h1 {
            margin-top: 13pt;
            font-size: 26pt;
            line-height: 1.18;
            letter-spacing: -0.8pt;
        }

        .project {
            margin-top: 8pt;
            color: #e0d7ff;
            font-size: 10pt;
            word-wrap: break-word;
        }

        .summary {
            margin: 15pt 0 21pt;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            page-break-inside: avoid;
        }

        .summary td {
            padding: 0 4.5pt;
            vertical-align: top;
        }

        .summary td:first-child {
            padding-left: 0;
        }

        .summary td:last-child {
            padding-right: 0;
        }

        .card {
            padding: 11pt 12pt;
            border: 1pt solid;
            border-radius: 6pt;
        }

        .card-total {
            border-color: #e2d9fa;
            background: #f4f0ff;
            color: #38217b;
        }

        .card-pending {
            border-color: #f3dda9;
            background: #fff7e6;
            color: #774006;
        }

        .card-received {
            border-color: #c9e9dc;
            background: #eaf8f1;
            color: #145d47;
        }

        .card-label {
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: .6pt;
            text-transform: uppercase;
        }

        .card-amount {
            margin: 3pt 0 2pt;
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: -0.5pt;
        }

        .card-count {
            color: #4d566b;
            font-size: 8pt;
        }

        .records {
            margin-bottom: 16pt;
            table-layout: fixed;
        }

        .records thead tr {
            page-break-after: avoid;
        }

        .group-title th {
            padding: 7pt 10pt;
            text-align: left;
            font-size: 11pt;
        }

        .pending .group-title th {
            border-left: 3pt solid #c67c16;
            background: #fff3da;
            color: #774006;
        }

        .received .group-title th {
            border-left: 3pt solid #258369;
            background: #e7f6ef;
            color: #145d47;
        }

        .column-labels th {
            padding: 7pt 8pt;
            color: #4d566b;
            background: #f2f3f8;
            border-bottom: 1pt solid #dfe2ee;
            font-size: 7.5pt;
            letter-spacing: .35pt;
            text-transform: uppercase;
            text-align: left;
        }

        .records tbody td {
            padding: 6pt 8pt;
            border-bottom: .5pt solid #e0e3ee;
            vertical-align: top;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        .alternating {
            background: #f8f9fc;
        }

        .timestamp, .amount {
            white-space: nowrap;
        }

        .column-labels .amount, .amount {
            text-align: right;
        }

        .amount {
            font-weight: bold;
        }

        .description {
            color: #424b60;
            white-space: pre-line;
            word-wrap: break-word;
        }

        .continuation {
            margin-bottom: 3pt;
            color: #4d566b;
            font-size: 7.5pt;
        }

        .subtotal {
            page-break-before: avoid;
        }

        .records .subtotal td {
            background: #f2f3f8;
            font-weight: bold;
        }

        .empty {
            padding: 18pt;
            border: 1pt solid #e0e3ee;
            border-radius: 6pt;
            color: #4d566b;
            text-align: center;
        }

        footer {
            position: fixed;
            right: 0;
            bottom: -34pt;
            left: 0;
            padding-top: 7pt;
            border-top: .7pt solid #dfe2ee;
            color: #4d566b;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <footer>Working · Relatório de recebimentos</footer>

    <header class="hero">
        <table>
            <tr>
                <td class="brand">Working</td>
                <td class="issued">Emitido em {{ $issuedOn }}</td>
            </tr>
        </table>
        <h1>Relatório de recebimentos</h1>
        <p class="project">Projeto · {{ $projectName ?? 'Sem projeto selecionado' }}</p>
    </header>

    <table class="summary">
        <tr>
            <td>
                <div class="card card-total">
                    <p class="card-label">Total da seleção</p>
                    <p class="card-amount">R$ {{ number_format($total, 2, ',', '.') }}</p>
                    <p class="card-count">{{ $recordCount }} {{ $recordCount === 1 ? 'registro' : 'registros' }}</p>
                </div>
            </td>
            @foreach ($sections as $status => $section)
                <td>
                    <div class="card card-{{ $status }}">
                        <p class="card-label">{{ $section['label'] }}</p>
                        <p class="card-amount">R$ {{ number_format($section['total'], 2, ',', '.') }}</p>
                        <p class="card-count">{{ $section['rows']->count() }} {{ $section['rows']->count() === 1 ? 'registro' : 'registros' }}</p>
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    @if ($recordCount === 0)
        <p class="empty">Nenhum registro selecionado para este relatório.</p>
    @endif

    @foreach ($sections as $status => $section)
        @if ($section['rows']->isEmpty())
            @continue
        @endif

        <table class="records {{ $status }}">
            <thead>
                <tr class="group-title">
                    <th colspan="5">{{ $section['title'] }}</th>
                </tr>
                <tr class="column-labels">
                    <th scope="col" style="width: 18%">Entrada</th>
                    <th scope="col" style="width: 18%">Saída</th>
                    <th scope="col" style="width: 10%">Duração</th>
                    <th scope="col" style="width: 37%">Descrição</th>
                    <th scope="col" style="width: 17%" class="amount">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($section['rows'] as $row)
                    @php($stripe = $loop->even ? 'alternating' : '')
                    <tr class="{{ $stripe }}">
                        <td class="timestamp">{{ $row['entry'] }}</td>
                        <td class="timestamp">{{ $row['exit'] }}</td>
                        <td>{{ $row['duration'] }}</td>
                        <td class="description">{{ $row['description'][0] }}</td>
                        <td class="amount">{{ $row['amount'] }}</td>
                    </tr>
                    @foreach (array_slice($row['description'], 1) as $part)
                        <tr class="{{ $stripe }}">
                            <td colspan="5">
                                <p class="continuation">Descrição de {{ $row['entry'] }} (continuação)</p>
                                <p class="description">{{ $part }}</p>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="subtotal">
                    <td colspan="4">Subtotal · {{ $section['label'] }}</td>
                    <td class="amount">R$ {{ number_format($section['total'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>
