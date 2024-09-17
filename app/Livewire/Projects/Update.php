<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\View\View;
use Livewire\Component;

class Update extends Component
{
    public ?Project $project = null;

    public bool $openModal = false;

    public ?string $name = null;

    public ?string $price_per_hour = null;

    public function mount(Project $project)
    {
        $this->project        = $project;
        $this->name           = $project->name;
        $this->price_per_hour = $project->formatMoney($project->price_per_hour);
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'max:100'],
            'price_per_hour' => ['required', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name'           => 'nome',
            'price_per_hour' => 'preço por hora',
        ];
    }

    public function render(): View
    {
        return view('livewire.projects.update', [
            'project' => $this->project,
        ]);
    }

    public function submit(): void
    {
        $this->authorize('update', $this->project);

        $this->validate();

        $this->project->update([
            'name'           => $this->name,
            'price_per_hour' => str_replace(',', '.', str_replace('.', '', $this->price_per_hour)),
        ]);

        $this->dispatch('project::handle');

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Projeto : ' . $this->project->name . ' atualizado com sucesso',
        ]);

        $this->openModal = false;
    }
}
