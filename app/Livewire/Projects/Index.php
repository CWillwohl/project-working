<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\{On, Url};
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;

    #[On('project::handle')]
    public function onProjectCreated()
    {
        $this->render();
    }

    #[Url]
    public ?string $search = '';

    public function render()
    {
        return view('livewire.projects.index', [
            'projects' => Project::searchData(['search' => $this->search ?? null])->paginate(50),
        ])
        ->layout('components.app-layout', ['title' => 'Gerenciar Projetos']);
    }
}
