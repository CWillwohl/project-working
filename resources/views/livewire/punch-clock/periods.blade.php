<div class="w-full">
    <div class="w-full p-4 bg-white rounded-lg shadow-lg md:p-6">
        <x-select-project :projects="$projects" :project="$project" />
        @if(!$project)
            <p class="py-10 text-sm text-center text-slate-500">Selecione um projeto para visualizar os períodos trabalhados.</p>
        @else
            <div class="mt-6 overflow-x-auto border rounded-lg border-slate-200">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs font-semibold uppercase bg-slate-100 text-slate-600">
                        <tr><th scope="col" class="px-4 py-4">Entrada</th><th scope="col" class="px-4 py-4">Saída</th><th scope="col" class="px-4 py-4">Tempo trabalhado</th><th scope="col" class="px-4 py-4">Valor</th><th scope="col" class="px-4 py-4">Status</th><th scope="col" class="px-4 py-4">Ações</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($periods as $period)
                            <tr wire:key="period-{{ $period->id }}" class="transition-colors hover:bg-slate-50">
                                <td class="px-4 py-4 font-medium">{{ date('d/m/Y H:i', strtotime($period->punch_in_time)) }}</td>
                                <td class="px-4 py-4">{{ date('d/m/Y H:i', strtotime($period->punch_out_time)) }}</td>
                                <td class="px-4 py-4">{{ $period->time_worked }}</td>
                                <td class="px-4 py-4 font-medium">{{ $period->formatted_value_to_receive }}</td>
                                <td class="px-4 py-4"><span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $period->received ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-800' }}">{{ $period->status_received_description }}</span></td>
                                <td class="px-4 py-4"><button type="button" class="btn btn-outline btn-primary btn-sm" wire:click="$dispatchTo('punch-clock.edit-times', 'punch-clock::edit', { periodId: {{ $period->id }}, field: 'both' })"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Editar</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Nenhum período fechado encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center justify-between gap-4 mt-6 md:flex-row">
                <span class="text-sm">Total de registros: <span class="font-medium">{{ $periods->total() }}</span></span>
                {{ $periods->links('livewire.custom.pagination') }}
            </div>
        @endif
    </div>
    <livewire:punch-clock.edit-times />
</div>
