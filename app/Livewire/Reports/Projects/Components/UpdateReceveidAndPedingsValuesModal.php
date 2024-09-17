<?php

namespace App\Livewire\Reports\Projects\Components;

use App\Models\{Project, WorkingPeriod};
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateReceveidAndPedingsValuesModal extends Component
{
    public bool $openModal = false;

    public ?string $typeModal = null;

    public $selectedWorkedPeriods = null;

    public ?Project $project = null;

    #[On('manage-receivements::change-project')]
    public function onProjectChange(Project $project): void
    {
        $this->project   = $project;
        $this->openModal = false;
    }

    #[On('manage-receivements::open-modal')]
    public function onSelectedTypeModal(array $data): void
    {
        $this->typeModal             = $data['typeModal'];
        $this->selectedWorkedPeriods = WorkingPeriod::whereIn('id', $data['selectedWorkedPeriods'])->get();

        $this->openModal = true;
    }

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.reports.projects.components.update-receveid-and-pedings-values-modal');
    }

    public function submit(): void
    {
        $elements = $this->selectedWorkedPeriods->pluck('id')->toArray();

        $this->project->updateReceivedAndPendingValues($elements, $this->typeModal);

        $this->openModal = false;

        $this->dispatch('manage-receivements::updated-elements');

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Valores alterados com sucesso',
        ]);
    }
}
