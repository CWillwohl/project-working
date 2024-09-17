<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Período de Trabalho - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 30px;
        }

        header {
            background-color: #e0f2fe;
            color: #333;
            padding: 20px;
            text-align: center;
        }

        h1 {
            text-align: center;
            font-size: 24px;
        }

        .resumo-financeiro, .tabela-historico {
            margin-bottom: 30px;
            border-radius: 10px;
            padding: 20px;
        }

        .valores {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .valores p {
            margin: 0;
        }

        .valores span#valorTotalReceber, .valores span#valorTotalRecebido {
            font-weight: bold;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        tbody tr:nth-child(odd) {
            background-color: #fff;
        }
    </style>
</head>
<body>
    @if(!empty($data))
    <h1>{{ $data['project']->name }} - {{ date('d/m/Y') }}</h1>
    @if(array_key_exists('pending', $data) && $data['pending']->count() > 0)
        <section class="tabela-historico">
            <h2>Histórico de Registros Pendentes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Horário de Entrada</th>
                        <th>Horário de Saida</th>
                        <th>Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['pending'] as $item)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($item->punch_in_time)) }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->punch_out_time)) }}</td>
                            <td>{{ $item->formatted_value_to_receive }}</td>
                        </tr>
                        <tr>
                            <td colspan="1">Descrição:</td>
                            <td colspan="2">{{ $item->description ?? 'Sem descrição.' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1">Total:</td>
                        <td colspan="2" style="text-align: right"><span style="font-weight: bold; color:#4CAF50">R$ {{ number_format($data['pending']->sum('value_to_receive'), 2, ',', '.') }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    @endif

    @if(array_key_exists('received', $data) && $data['received']->count() > 0)
        <section class="tabela-historico">
            <h2>Histórico de Registros Recebidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Horário de Entrada</th>
                        <th>Horário de Saida</th>
                        <th>Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['received'] as $item)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($item->punch_in_time)) }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->punch_out_time)) }}</td>
                            <td>{{ $item->formatted_value_to_receive }}</td>
                        </tr>
                        <tr>
                            <td colspan="1">Descrição:</td>
                            <td colspan="2">{{ $item->description ?? 'Sem descrição.' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1">Total:</td>
                        <td colspan="2" style="text-align: right"><span style="font-weight: bold; color:#4CAF50">R$ {{ number_format($data['received']->sum('value_to_receive'), 2, ',', '.') }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    @endif

    @endif
</body>
</html>
