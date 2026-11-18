<?php

namespace App\Livewire\Reports\Projects;

use App\Actions\WorkingPeriod\PdfReport;
use App\Models\{Project, WorkingPeriod};
use Illuminate\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\{Component, WithPagination};
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageReceivements extends Component
{
    use WithPagination;

    public ?Project $project = null;

    #[Validate('before_or_equal:dateEnd', as: 'data inicial')]
    public ?string $dateStart = null;

    public ?string $dateEnd = null;

    public ?int $received = 0;

    public ?bool $selectAll = false;

    public ?array $selectedWorkedPeriods = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->refreshSelection();
    }

    #[On('manage-receivements::filter-data')]
    public function onDataFiltered(array $data): void
    {
        $this->dateStart = $data['dateStart'];
        $this->dateEnd   = $data['dateEnd'];
        $this->resetPage();
        $this->refreshSelection();
    }

    #[On('manage-receivements::updated-elements')]
    public function onUpdatedElements(): void
    {
        $this->refreshSelection();
    }

    public function selectProject(Project $project): void
    {
        $this->project               = $project;
        $this->resetPage();
        $this->refreshSelection();

        $this->dispatch('manage-receivements::change-project', $project);
    }

    public function filterStatus(int $status): void
    {
        abort_unless(in_array($status, [0, 1], true), 422);
        $this->received = $status;
        $this->resetPage();
        $this->refreshSelection();
    }

    public function updatedSelectedWorkedPeriods(): void
    {
        $this->selectAll = false;
    }

    private function refreshSelection(): void
    {
        $this->selectAll = $this->received === 0;
        $this->selectAllElements();
    }

    public function openModal(string $type): void
    {
        $this->dispatch('manage-receivements::open-modal', [
            'typeModal'             => $type,
            'selectedWorkedPeriods' => $this->selectedWorkedPeriods,
        ]);
    }

    public function generatePdf(): StreamedResponse
    {
        $pdf = (new PdfReport($this->selectedWorkedPeriods, $this->project))->handle();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Relatório de Períodos de Trabalho.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function selectAllElements(): array
    {
        if($this->selectAll) {
            return $this->selectedWorkedPeriods = WorkingPeriod::searchData(
                $this->project->id ?? null,
                [
                    'dateStart' => $this->dateStart,
                    'dateEnd'   => $this->dateEnd,
                    'received'  => $this->received,
                ]
            )
            ->where('punch_out_id', '!=', null)
            ->pluck('id')->toArray();
        }

        return $this->selectedWorkedPeriods = [];
    }

    public function render(): View
    {
        return view('livewire.reports.projects.manage-receivements', [
            'workedPeriods' => WorkingPeriod::searchData(
                $this->project->id ?? null,
                [
                    'dateStart' => $this->dateStart,
                    'dateEnd'   => $this->dateEnd,
                    'received'  => $this->received,
                ]
            )
            ->orderBy('received', 'asc')
            ->orderBy('punch_in_time', 'desc')
            ->paginate(15),
            'projects' => Project::where('user_id', auth()->user()->id ?? null)->get(),
        ])->layout('components.app-layout', ['title' => 'Gerenciar Recebimentos']);
    }
}
