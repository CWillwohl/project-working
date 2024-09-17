<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class Delete extends Component
{
    public bool $openModal = false;

    public ?Project $project = null;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.projects.delete');
    }

    public function submit(): void
    {
        $this->authorize('delete', $this->project);

        $projectName = $this->project->name;

        $this->project->delete();

        $this->dispatch('project::handle');

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Projeto: ' . $projectName . ' deletado com sucesso',
        ]);

        $this->openModal = false;
    }
}
