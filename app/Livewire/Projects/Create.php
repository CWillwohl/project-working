<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    public bool $openModal = false;

    public ?string $name = null;

    public ?string $price_per_hour = null;

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
        return view('livewire.projects.create');
    }

    public function submit(): void
    {
        $this->validate();

        $project = Project::create([
            'user_id'        => auth()->user()->id,
            'name'           => $this->name,
            'price_per_hour' => str_replace(',', '.', str_replace('.', '', $this->price_per_hour)),
        ]);

        $this->openModal = false;

        $this->reset(['name', 'price_per_hour']);

        $this->dispatch('project::handle');

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Projeto: ' . $project->name . ' cadastrado com sucesso',
        ]);
    }
}
