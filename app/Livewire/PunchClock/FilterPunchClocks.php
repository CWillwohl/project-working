<?php

namespace App\Livewire\PunchClock;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FilterPunchClocks extends Component
{
    #[Validate('nullable|before_or_equal:dateEnd|required_with:dateEnd', as: 'data inicial')]
    public ?string $dateStart = null;

    #[Validate(as: 'data final')]
    public ?string $dateEnd = null;

    public ?int $project = null;

    public function render()
    {
        /** @var User $user */
        $user = auth()->user();

        return view('livewire.punch-clock.filter-punch-clocks', [
            'projects' => $user->projects,
        ]);
    }

    public function filterData(): void
    {
        $this->validate();

        $this->dispatch('punch-clocks::filter-data', [
            'dateStart' => $this->dateStart,
            'dateEnd'   => $this->dateEnd,
            'project'   => $this->project,
        ]);
    }
}
