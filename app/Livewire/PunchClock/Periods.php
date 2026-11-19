<?php

namespace App\Livewire\PunchClock;

use App\Models\Project;
use App\Models\WorkingPeriod;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Periods extends Component
{
    use WithPagination;

    public ?Project $project = null;

    public function selectProject(int $project): void
    {
        $this->project = auth()->user()->projects()->findOrFail($project);
        $this->resetPage();
    }

    #[On('punch-clock::updated')]
    public function refreshPeriods(): void {}

    public function render()
    {
        return view('livewire.punch-clock.periods', [
            'projects' => auth()->user()->projects()->get(),
            'periods' => WorkingPeriod::searchData($this->project?->id)
                ->whereNotNull('punch_out_id')->orderByDesc('punch_in_time')->paginate(15),
        ])->layout('components.app-layout', ['title' => 'Períodos trabalhados']);
    }
}
