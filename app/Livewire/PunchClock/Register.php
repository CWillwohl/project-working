<?php

namespace App\Livewire\PunchClock;

use App\Actions\PunchClock\RegisterNewPunchClockAction;
use App\Models\{Project, PunchClock, WorkingPeriod};
use Livewire\Component;

class Register extends Component
{
    public ?Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function selectProject(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user             = auth()->user();
        $projects         = $user->projects()->get();
        $lastSixRegisters = $this->project->getLastSixPunchs();

        return view('livewire.punch-clock.register', [
            'projects'         => $projects,
            'lastSixRegisters' => $lastSixRegisters,
        ])->layout('components.app-layout', ['title' => 'Registrar Ponto']);
    }

    public function submit(): void
    {
        (new RegisterNewPunchClockAction($this->project))->execute();

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Registro de ponto realizado com sucesso!',
        ]);
    }
}
