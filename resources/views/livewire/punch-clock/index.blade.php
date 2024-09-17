<div class="flex items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full p-4 bg-white rounded-lg">
        <div class="flex flex-col w-full p-4 overflow-x-auto md:overflow-hidden min-h-[600px]">
            <div class="flex flex-col justify-start w-full gap-4 mt-4 md:flex-row md:items-end">
                <livewire:punch-clock.filter-punch-clocks />
            </div>

            <table class="w-full mt-4 table-auto">
                <thead class="text-left border border-x bg-slate-100 border-slate-200">
                    <th class="p-4 rounded-t">PROJETO</th>
                    <th class="p-4">DATA DO REGISTRO</th>
                    <th class="p-4">TIPO DE REGISTRO</th>
                    <th class="p-4 rounded-t">OPÇÕES</th>
                </thead>
                <tbody class="border text-zinc-600">
                    @forelse ($punchClocks as $index => $item)
                        <tr wire:key="{{ $item->id }}" class="border-b @if($index % 2 !== 0) bg-slate-50 @endif">
                            <td class="p-4 text-base">{{ $item->project->name }}</td>
                            <td class="flex flex-col gap-2 p-4 text-base">
                                <div class="p-2 font-medium text-center text-white rounded-lg shadow-lg w-36 bg-success">
                                    {{ date('d/m/Y', strtotime($item->punch_time)) }}
                                    <hr>
                                    {{ date('H:i', strtotime($item->punch_time)) }}
                                </div>
                            </td>
                            <td class="p-4 text-base font-medium">
                                <div class="w-32 p-2 text-center text-white rounded-lg bg-gradient-to-b shadow-lg
                                @if ($item->punch_type == 0)
                                    from-sky-700 to-sky-500
                                @else
                                    from-sky-900 to-sky-700
                                @endif
                                ">
                                    @if($item->punch_type == 0)
                                        <i class="fa-solid fa-circle-chevron-right"></i>
                                    @else
                                        <i class="fa-solid fa-circle-chevron-left"></i>
                                    @endif
                                    {{ $item->punch_type_description }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col items-center justify-center w-full gap-4 md:flex-row md:justify-start">
                                    <livewire:punch-clock.update :punchClock="$item" wire:key="edit-punch-clock-{{ $item->id }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4">Nenhum registro realizado até o momento</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between w-full gap-4 mt-8 md:flex-row">
            <span class="text-sm md:text-md">Total de registros: <span class="font-medium ">{{ $punchClocks->total() }}</span></span>
            {{ $punchClocks->links('livewire.custom.pagination') }}
        </div>
    </div>
</div>
