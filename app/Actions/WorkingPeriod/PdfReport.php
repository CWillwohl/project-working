<?php

namespace App\Actions\WorkingPeriod;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\{Project, WorkingPeriod};

class PdfReport
{
    public function __construct(
        protected ?array $workedPeriods,
        protected ?Project $project
    ) {
    }

    public function handle()
    {
        $receivedPeriods = WorkingPeriod::query()
            ->whereIn('id', $this->workedPeriods)
            ->where('received', true)
            ->get();

        $pendingPeriods = WorkingPeriod::query()
            ->whereIn('id', $this->workedPeriods)
            ->where('received', false)
            ->get();

        $data = array_merge([
            'received' => $receivedPeriods,
            'pending'  => $pendingPeriods,
            'project'  => $this->project,
        ]);

        return PDF::loadView('pdf.working-period-report', compact('data'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
    }
}
