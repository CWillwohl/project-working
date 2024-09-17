<?php

namespace App\Livewire\Reports\Projects;

use App\Actions\WorkingPeriod\PdfReport;
use App\Models\{Project, WorkingPeriod};
use Illuminate\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\{Component, WithPagination};

class ManageReceivements extends Component
{
    use WithPagination;

    public ?Project $project = null;

    #[Validate('before_or_equal:dateEnd', as: 'data inicial')]
    public ?string $dateStart = null;

    public ?string $dateEnd = null;

    public ?int $received = null;

    public ?bool $selectAll = false;

    public ?array $selectedWorkedPeriods = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    #[On('manage-receivements::filter-data')]
    public function onDataFiltered(array $data): void
    {
        $this->dateStart = $data['dateStart'];
        $this->dateEnd   = $data['dateEnd'];
        $this->received  = $data['received'];

        $this->render();
    }

    #[On('manage-receivements::updated-elements')]
    public function onUpdatedElements(): void
    {
        $this->selectedWorkedPeriods = [];
        $this->selectAll             = false;

        $this->render();
    }

    public function selectProject(Project $project): void
    {
        $this->project               = $project;
        $this->selectAll             = false;
        $this->selectedWorkedPeriods = [];

        $this->dispatch('manage-receivements::change-project', $project);
    }

    public function openModal(string $type): void
    {
        $this->dispatch('manage-receivements::open-modal', [
            'typeModal'             => $type,
            'selectedWorkedPeriods' => $this->selectedWorkedPeriods,
        ]);
    }

    public function generatePdf()
    {
        $pdf = ((new PdfReport($this->selectedWorkedPeriods, $this->project))->handle());

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->stream();
        }, 'Relatório de Períodos de Trabalho.pdf');
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
