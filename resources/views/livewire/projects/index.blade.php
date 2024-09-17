<div class="flex items-center justify-center w-full">
    <div class="flex flex-col justify-center w-full p-4 overflow-x-auto bg-white rounded-lg md:overflow-hidden">
        <div class="flex flex-col justify-between w-full gap-4 mt-4 md:flex-row md:items-end">
            <livewire:projects.create />
            <div class="w-full md:w-1/4">
                <x-input wire:model.live="search" textColor="primary" name="search" type="text" placeholder="Pesquisar por projeto" />
            </div>
        </div>

        <table class="w-full mt-4 table-auto">
            <thead class="text-left border border-x bg-slate-100 border-slate-200">
                <th class="p-4 rounded-t">NOME DO PROJETO</th>
                <th class="p-4">VALOR POR HORA</th>
                <th class="p-4">VALORES RECEBIDOS</th>
                <th class="p-4">VALORES PARA RECEBER</th>
                <th class="p-4 rounded-t">OPÇÕES</th>
            </thead>
            <tbody class="border text-zinc-600">
                @forelse ($projects as $index => $item)
                    <tr wire:key="{{ $item->id }}" class="border-b @if($index % 2 !== 0) bg-slate-50 @endif">
                        <td class="p-4 text-base">{{ $item->name }}</td>
                        <td class="p-4 text-base">{{ $item->formatted_price_per_hour }}</td>
                        <td class="p-4 text-base">{{ $item->formatted_total_received }}</td>
                        <td class="p-4 text-base">{{ $item->formatted_price_to_receive }}</td>
                        <td class="p-4">
                            <div class="flex flex-col items-center justify-center w-full gap-4 md:flex-row md:justify-start">
                                <livewire:projects.update wire:key="edit-project-{{ $item->id }}" :project="$item" />
                                <livewire:projects.delete wire:key="delete-project-{{ $item->id }}" :project="$item" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-4">Nenhum projeto encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex flex-col items-center justify-between w-full gap-4 mt-8 md:flex-row">
            <span class="text-sm md:text-md">Total de registros: <span class="font-medium ">{{ $projects->total() }}</span></span>
            {{ $projects->links('livewire.custom.pagination') }}
        </div>
    </div>
</div>
