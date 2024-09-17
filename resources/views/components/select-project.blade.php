@props([
    'projects' => [],
    'project' => null,
])
@if($projects)
<div class="w-full overflow-x-auto font-medium text-white collapse bg-primary">
    <input type="checkbox" />
    <div class="text-lg collapse-title">
        Selecione o Projeto - {{ $project->name ?? 'Nenhum projeto selecionado' }}
    </div>
    <div class="flex flex-col md:grid gap-4 xl:grid-cols-3 collapse-content max-h-[12em] overflow-y-auto">
        @foreach ($projects as $item)
            <button wire:click="selectProject({{ $item }})" class="h-auto p-2 m-2 bg-white btn text-primary">
                {{ $item->name }}
            </button>
        @endforeach
    </div>
</div>
@else
    <div class="w-full p-4 font-medium text-white rounded-lg bg-primary">
        Você ainda não possuí nenhum projeto cadastrado, para realizar o cadastro de um novo Projeto acesso a página de: <a href="{{ route('projects.index') }}" class="font-medium hover:underline">Gerenciar Projetos</a>.

    </div>
@endif
