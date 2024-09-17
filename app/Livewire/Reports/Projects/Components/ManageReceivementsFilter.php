<?php

namespace App\Livewire\Reports\Projects\Components;

use Livewire\Attributes\Validate;
use Livewire\Component;

class ManageReceivementsFilter extends Component
{
    #[Validate('nullable|before_or_equal:dateEnd|required_with:dateEnd', as: 'data inicial')]
    public ?string $dateStart = null;

    #[Validate(as: 'data final')]
    public ?string $dateEnd = null;

    #[Validate('nullable|boolean', as: 'status')]
    public ?int $received = null;

    public function filterData(): void
    {
        $this->validate();

        $this->dispatch('manage-receivements::filter-data', [
            'dateStart' => $this->dateStart,
            'dateEnd'   => $this->dateEnd,
            'received'  => $this->received,
        ]);
    }

    public function render()
    {
        return view('livewire.reports.projects.components.manage-receivements-filter');
    }
}
