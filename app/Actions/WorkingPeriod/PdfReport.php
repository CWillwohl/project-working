<?php

namespace App\Actions\WorkingPeriod;

use App\Models\{Project, WorkingPeriod};
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfDocument;
use Carbon\Carbon;

class PdfReport
{
    public function __construct(
        protected ?array $workedPeriods,
        protected ?Project $project
    ) {
    }

    public function handle(): PdfDocument
    {
        $periods = WorkingPeriod::searchData($this->project?->id)
            ->whereIn('id', $this->workedPeriods ?? [])
            ->orderByDesc('punch_in_time')
            ->orderByDesc('id')
            ->get();

        [$received, $pending] = $periods->partition(fn (WorkingPeriod $period) => $period->received);

        $groups = [
            'pending'  => ['A receber', 'Registros pendentes', $pending],
            'received' => ['Recebido', 'Registros recebidos', $received],
        ];

        $sections = [];

        foreach ($groups as $status => [$label, $title, $group]) {
            $sections[$status] = [
                'label' => $label,
                'title' => $title,
                'total' => $group->sum('value_to_receive'),
                'rows'  => $group->map(fn (WorkingPeriod $period) => [
                    'entry'       => Carbon::parse($period->punch_in_time)->format('d/m/Y H:i'),
                    'exit'        => $period->punch_out_time ? Carbon::parse($period->punch_out_time)->format('d/m/Y H:i') : '—',
                    'duration'    => $period->time_worked,
                    'amount'      => $period->formatted_value_to_receive,
                    'description' => $this->descriptionParts($period->description),
                ]),
            ];
        }

        $pdf = Pdf::loadView('pdf.working-period-report', [
            'projectName' => $this->project?->name,
            'issuedOn'    => now()->format('d/m/Y'),
            'sections'    => $sections,
            'total'       => $sections['pending']['total'] + $sections['received']['total'],
            'recordCount' => $periods->count(),
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('isFontSubsettingEnabled', true)
            ->setWarnings(false);

        $pdf->render();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_text(
            $canvas->get_width() - 110,
            $canvas->get_height() - 29,
            'Página {PAGE_NUM} / {PAGE_COUNT}',
            $dompdf->getFontMetrics()->getFont('DejaVu Sans'),
            8,
            [0.29, 0.33, 0.42],
        );

        return $pdf;
    }

    private function descriptionParts(?string $description): array
    {
        $description = trim(str_replace(["\r\n", "\r"], "\n", $description ?? ''));

        if ($description === '') {
            return ['—'];
        }

        // Dompdf can split a table between rows, but each row must fit on one page.
        preg_match_all('/[^\n]{1,160}(?:\n|$)?|\n/u', $description, $lines);

        return array_map(fn (array $part) => implode('', $part), array_chunk($lines[0], 4));
    }
}
