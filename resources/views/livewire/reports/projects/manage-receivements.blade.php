<div class="w-full">
    <div class="w-full p-4 bg-white rounded-lg shadow-lg md:p-6">
        <x-select-project :projects="$projects" :project="$project" />

        <div class="flex flex-col justify-between gap-4 mt-6 xl:flex-row">
            <div class="flex flex-wrap items-center gap-2">
                <livewire:reports.projects.components.manage-receivements-filter />
                <button type="button" wire:click="filterStatus(0)" aria-pressed="{{ $received === 0 ? 'true' : 'false' }}" class="btn {{ $received === 0 ? 'btn-primary' : 'btn-outline' }}">Pendente</button>
                <button type="button" wire:click="filterStatus(1)" aria-pressed="{{ $received === 1 ? 'true' : 'false' }}" class="btn {{ $received === 1 ? 'btn-primary' : 'btn-outline' }}">Recebido</button>
            </div>
            <x-manage-receivements-options />
        </div>

        <div class="mt-6 overflow-x-auto border rounded-lg border-slate-200">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs font-semibold uppercase bg-slate-100 text-slate-600">
                    <tr>
                        <th scope="col" class="px-4 py-4"><input wire:model="selectAll" type="checkbox" class="checkbox checkbox-primary checkbox-sm" wire:change="selectAllElements" aria-label="Selecionar todos os períodos" /></th>
                        <th scope="col" class="px-4 py-4">Entrada</th>
                        <th scope="col" class="px-4 py-4">Saída</th>
                        <th scope="col" class="px-4 py-4">Tempo trabalhado</th>
                        <th scope="col" class="px-4 py-4">Valor</th>
                        <th scope="col" class="px-4 py-4">Status</th>
                        <th scope="col" class="px-4 py-4">Descrição</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($workedPeriods as $item)
                        <tr wire:key="{{ $item->id }}" class="transition-colors hover:bg-slate-50">
                            <td class="px-4 py-4">
                                @if($item->punch_out_id)
                                    <input wire:model="selectedWorkedPeriods" type="checkbox" class="checkbox checkbox-primary checkbox-sm worked-period-element" value="{{ $item->id }}" aria-label="Selecionar período {{ $item->id }}" />
                                @else
                                    <span class="tooltip tooltip-right" data-tip="Não é possível selecionar um período que ainda não foi completado"><i class="fa-solid fa-circle-info text-info" aria-label="Período em aberto"></i></span>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-medium">{{ date('d/m/Y H:i', strtotime($item->punch_in_time)) }}</td>
                            <td class="px-4 py-4">{{ $item->punch_out_time ? date('d/m/Y H:i', strtotime($item->punch_out_time)) : 'Sem registro' }}</td>
                            <td class="px-4 py-4">{{ $item->time_worked }}</td>
                            <td class="px-4 py-4 font-medium">{{ $item->formatted_value_to_receive }}</td>
                            <td class="px-4 py-4"><span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $item->received ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-800' }}">{{ $item->status_received_description }}</span></td>
                            <td class="px-4 py-4"><livewire:punch-clock.working-period-description wire:key="description-{{ $item->id }}" :workingPeriod="$item" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">Nenhum registro encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-4 mt-6 md:flex-row">
            <span class="text-sm">Total de registros: <span class="font-medium">{{ $workedPeriods->total() }}</span></span>
            {{ $workedPeriods->links('livewire.custom.pagination') }}
        </div>
    </div>
    <livewire:reports.projects.components.update-receveid-and-pedings-values-modal />
</div>
