<div class="flex items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full p-4 bg-white rounded-lg">
        <div class="flex flex-col w-full min-h-[900px] md:min-h-[700px] p-4 overflow-x-auto md:overflow-hidden">

            <x-select-project :projects="$projects" :project="$project" />

            <div class="flex flex-col justify-between w-full gap-4 mt-8 xl:flex-row">
                <livewire:reports.projects.components.manage-receivements-filter />
                <x-manage-receivements-options />
            </div>
            <form action="" wire:submit.prevent>
                <table class="w-full mt-4 table-auto">
                    <thead class="text-left border border-x bg-slate-100 border-slate-200">
                        <th class="gap-2 p-4 rounded-t">
                            <input wire:model="selectAll" type="checkbox" class="checkbox checkbox-primary" wire:change="selectAllElements" />
                        </th>
                        <th class="p-4">HORARIO DE ENTRADA</th>
                        <th class="p-4">HORARIO DE SAIDA</th>
                        <th class="p-4">TEMPO TRABALHADO</th>
                        <th class="p-4 rounded-t">VALOR A RECEBER</th>
                        <th class="p-4 rounded-t">STATUS</th>
                        <th class="p-4 rounded-t">DESCRIÇÃO</th>
                    </thead>
                    <tbody class="border text-zinc-600">
                        @forelse ($workedPeriods as $index => $item)
                            <tr wire:key="{{ $item->id }}" class="border-b @if($index % 2 !== 0) bg-slate-50 @endif">
                                <td class="p-4 text-base">
                                    @if($item->punch_out_id)
                                        <input wire:model="selectedWorkedPeriods" type="checkbox" class="checkbox checkbox-primary worked-period-element" value="{{ $item->id }}" onclick="manageOptionsVisibility()" />
                                    @else
                                        <div class="tooltip tooltip-right" data-tip="Não é possivel selecionar um periodo que ainda não foi completado">
                                            <i class="text-2xl text-center fa-solid fa-circle-info text-info"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 text-base">
                                    <div class="p-2 text-sm font-medium text-center text-white rounded-full shadow-lg w-44 bg-gradient-to-r from-sky-700 to-sky-500">
                                        {{ date('d/m/Y H:i', strtotime($item->punch_in_time)) }}
                                    </div>
                                </td>
                                <td class="p-4 text-base">
                                    <div class="p-2 text-sm font-medium text-center text-white rounded-full shadow-lg w-44 bg-gradient-to-r from-sky-900 to-sky-700">
                                        {{ $item->punch_out_time ? date('d/m/Y H:i', strtotime($item->punch_out_time)) : 'Sem registro' }}
                                    </div>
                                </td>
                                <td class="p-4 text-base">
                                    {{ $item->time_worked }}
                                </td>
                                <td class="p-4 text-base">{{ $item->formatted_value_to_receive }}</td>
                                <td class="p-4 text-base">
                                    @if(!$item->received)
                                        <div class="p-2 text-sm font-medium text-center text-white rounded-full shadow-lg w-44 bg-gradient-to-r from-green-700 to-green-500">
                                            {{ $item->status_received_description }}
                                        </div>
                                    @else
                                        <div class="p-2 text-sm font-medium text-center text-white rounded-full shadow-lg w-44 bg-gradient-to-r from-sky-700 to-sky-500">
                                            {{ $item->status_received_description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 text-base">
                                    <livewire:punch-clock.working-period-description wire:key="description-{{ $item->id }}" :workingPeriod="$item" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4">Nenhum registro realizado até o momento</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
        <div class="flex flex-col items-center justify-between w-full gap-4 mt-8 md:flex-row">
            <span class="text-sm md:text-md">Total de registros: <span class="font-medium ">{{ $workedPeriods->total() }}</span></span>
            {{ $workedPeriods->links('livewire.custom.pagination') }}
        </div>
    </div>

    <livewire:reports.projects.components.update-receveid-and-pedings-values-modal />
</div>
