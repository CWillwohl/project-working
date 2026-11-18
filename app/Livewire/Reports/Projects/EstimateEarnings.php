<?php

namespace App\Livewire\Reports\Projects;

use App\Actions\Project\EstimateEarnings as CalculateEstimate;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\{Computed, Locked};
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EstimateEarnings extends Component
{
    #[Locked]
    public ?Project $project = null;

    public ?string $dateStart = null;

    public ?string $dateEnd = null;

    #[Locked]
    public int $timeWorked = 0;

    public array $excludedWeekdays = [];

    #[Locked]
    public bool $weekdaysSaved = false;

    public function selectProject(Project $project): void
    {
        $this->authorize('update', $project);

        $this->project          = $project;
        $this->excludedWeekdays = $project->excluded_weekdays ?? [];
        $this->weekdaysSaved    = false;
        $this->resetValidation();
    }

    public function updatedExcludedWeekdays(): void
    {
        $this->weekdaysSaved = false;
    }

    public function toggleWeekends(): void
    {
        $weekend                = [6, 7];
        $this->excludedWeekdays = array_diff($weekend, $this->excludedWeekdays) === []
            ? array_values(array_diff($this->excludedWeekdays, $weekend))
            : array_values(array_unique(array_merge($this->excludedWeekdays, $weekend)));
        $this->weekdaysSaved = false;
    }

    public function saveWeekdays(): void
    {
        abort_unless($this->project, 404);
        $this->authorize('update', $this->project);

        $validated = $this->validate([
            'excludedWeekdays'   => ['array', 'max:7'],
            'excludedWeekdays.*' => ['required', 'integer', 'between:1,7', 'distinct'],
        ], [], [
            'excludedWeekdays'   => 'dias excluídos',
            'excludedWeekdays.*' => 'dia excluído',
        ]);

        $this->excludedWeekdays = array_map('intval', array_values($validated['excludedWeekdays']));
        sort($this->excludedWeekdays);

        $this->project->excluded_weekdays = $this->excludedWeekdays;
        $this->project->save();

        $this->weekdaysSaved = true;
        unset($this->estimate);
    }

    public function incrementTimeWorked(): void
    {
        $this->timeWorked = min(24, $this->timeWorked + 1);
    }

    public function decrementTimeWorked(): void
    {
        $this->timeWorked = max(0, $this->timeWorked - 1);
    }

    #[Computed]
    public function estimate(): ?array
    {
        if (!$this->project || $this->periodValidator()->fails()) {
            return null;
        }

        return (new CalculateEstimate())->handle($this->project, $this->dateStart, $this->dateEnd, $this->timeWorked);
    }

    public function generatePdf(): StreamedResponse
    {
        abort_unless($this->project, 404);
        $this->authorize('update', $this->project);
        $this->periodValidator()->validate();

        $pdf = Pdf::loadView('pdf.project-earnings-estimate', ['estimate' => $this->estimate])
            ->setPaper('a4', 'portrait')
            ->setOption('isFontSubsettingEnabled', true);

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

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'estimativa-de-ganhos.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function render(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return view('livewire.reports.projects.estimate-earnings', [
            'projects'         => $user->projects()->get(),
            'estimate'         => $this->estimate,
            'weekdays'         => CalculateEstimate::WEEKDAYS,
            'savedWeekdays'    => array_intersect_key(CalculateEstimate::WEEKDAYS, array_flip($this->project?->excluded_weekdays ?? [])),
            'weekendsExcluded' => array_diff([6, 7], $this->excludedWeekdays) === [],
            'periodErrors'     => $this->dateStart && $this->dateEnd ? $this->periodValidator()->errors() : null,
        ])->layout('components.app-layout', ['title' => 'Estimar Ganhos de Projetos']);
    }

    private function periodValidator(): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make([
            'dateStart' => $this->dateStart,
            'dateEnd'   => $this->dateEnd,
        ], [
            'dateStart' => ['required', 'date_format:Y-m-d'],
            'dateEnd'   => ['required', 'date_format:Y-m-d', 'after_or_equal:dateStart'],
        ], [
            'dateEnd.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
        ], [
            'dateStart' => 'data inicial',
            'dateEnd'   => 'data final',
        ]);
    }
}
