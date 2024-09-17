<?php

namespace App\Livewire\Custom;

use Livewire\Attributes\On;
use Livewire\Component;

class LayoutAlerts extends Component
{
    #[On('alert::handle')]
    public function onAlertHandle(array $alert): void
    {
        $this->alert = $alert;
    }

    public array $alert = [];

    public function render()
    {
        return view('livewire.custom.layout-alerts');
    }
}
