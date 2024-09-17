<?php

namespace App\Livewire\Reports\Projects;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EstimateEarnings extends Component
{
    public ?Project $project;

    #[Validate('required|date|before_or_equal:dateEnd', as: 'data inicial')]
    public ?string $dateStart = null;

    #[Validate('required|date', as: 'data final')]
    public ?string $dateEnd = null;

    public ?string $totalEarnings = 'R$ 0,00';

    public ?int $timeWorked = 0;

    public ?array $estimatedValues = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function selectProject(Project $project): void
    {
        $this->project = $project;

        $this->updateEarnings();
        $this->calculateEstimatedValues();
    }

    public function calculateEarnings(): void
    {
        $this->validate();

        $this->updateEarnings();
    }

    public function incrementTimeWorked(): void
    {
        if($this->timeWorked == 24) {
            return;
        }

        $this->timeWorked++;

        $this->updateEarnings();
        $this->calculateEstimatedValues();
    }

    public function decrementTimeWorked()
    {
        if($this->timeWorked == 0) {
            return;
        }
        $this->timeWorked--;

        $this->updateEarnings();
        $this->calculateEstimatedValues();
    }

    public function render(): View
    {
        /** @var \App\Models\User $user */
        $user     = auth()->user();
        $projects = $user->projects()->get();

        return view('livewire.reports.projects.estimate-earnings', [
            'projects' => $projects,
        ])->layout('components.app-layout', ['title' => 'Estimar Ganhos de Projetos']);
    }

    private function updateEarnings(): void
    {
        $this->totalEarnings = $this->project->calculateEarnings(
            dateStart: $this->dateStart,
            dateEnd: $this->dateEnd,
            timeWorked: $this->timeWorked
        );
    }

    private function calculateEstimatedValues(): void
    {
        $this->estimatedValues = $this->project->calculateEstimatedValues(
            timeWorked: $this->timeWorked
        );
    }
}
