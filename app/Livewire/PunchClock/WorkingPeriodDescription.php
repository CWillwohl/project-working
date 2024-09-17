<?php

namespace App\Livewire\PunchClock;

use App\Models\WorkingPeriod;
use Livewire\Component;

class WorkingPeriodDescription extends Component
{
    public ?WorkingPeriod $workingPeriod;

    public ?string $description = null;

    public bool $openModal = false;

    public function mount(WorkingPeriod $workingPeriod): void
    {
        $this->workingPeriod = $workingPeriod;
        $this->description   = $workingPeriod->description;
    }

    public function render()
    {
        return view('livewire.punch-clock.description');
    }

    public function submit(): void
    {
        $this->workingPeriod->update(['description' => $this->description]);
        $this->openModal = false;

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Valores alterados com sucesso',
        ]);
    }
}
