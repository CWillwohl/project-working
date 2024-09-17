<div class="flex flex-col items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full gap-8 md:flex-row">
        <div class="flex flex-col items-center w-full h-auto gap-4 p-4 bg-white rounded-lg shadow-lg md:flex-row">
            <div class="flex flex-row items-start justify-center w-full h-full gap-8 md:w-1/2">
                <div class="flex flex-col w-full h-full gap-4">
                    <x-select-project :projects="$projects" :project="$project" />
                    <x-clock />
                </div>
            </div>
            <div class="divider divider-horizontal"></div>
            <div class="flex flex-row items-start justify-center w-full h-full gap-8 md:w-1/2">
                <div class="flex flex-col justify-between w-full h-full gap-4">
                    <div class="h-auto text-white rounded-lg shadow-lg border-zinc-700 stat bg-gradient-to-b from-green-700 to-green-500">
                        <div class="flex flex-wrap text-sm text-white whitespace-pre-line stat-title">Ultimo registro realizado:</div>
                        <div class="flex flex-wrap mt-4 text-xl whitespace-pre-line md:flex-col stat-value">
                            @if($lastSixRegisters->count() >= 1)
                                <p>{{ $lastSixRegisters?->first()->punch_type_description }}</p>
                                <p>{{ date('d/m/Y - H:i', strtotime($lastSixRegisters?->first()->punch_time)) }}</p>
                            @else
                                <p>Nenhum registro realizado</p>
                            @endif
                        </div>
                    </div>
                    <button class="btn btn-primary" wire:click="submit" @if(!$project->id) disabled @endif>Registrar Ponto</button>
                </div>
            </div>
        </div>
    </div>
    @if($lastSixRegisters->count() >= 1)
    <hr class="my-4">
    <div class="flex flex-col justify-between w-full gap-8 bg-white md:flex-row">
        <div class="flex flex-col w-full gap-4 p-4 bg-white rounded-lg shadow-lg">
            <h1 class="text-lg font-medium">
                Últimos 6 registros:
            </h1>
            <div class="grid content-end w-full grid-cols-1 gap-4 md:grid-cols-3 md:grid-rows-2">
                @foreach ($lastSixRegisters as $item)
                    <div class="h-auto text-white rounded-lg shadow-lg border-zinc-700 stat bg-gradient-to-b
                    @if ($item->punch_type == 0)
                        from-sky-700 to-sky-500
                    @else
                        from-sky-900 to-sky-700
                    @endif
                    ">
                        <div class="flex-wrap text-sm text-white whitespace-pre-line stat-title">{{ $item->punch_type_description }}</div>
                        <div class="flex flex-row items-center justify-between w-full">
                            <div class="w-1/2 text-lg stat-value">
                                <p>Data: {{ date('d/m/Y', strtotime($item->punch_time)) }}</p>
                                <p>Horario: {{ date('H:i', strtotime($item->punch_time)) }}</p>
                            </div>
                            <div class="items-center justify-center hidden w-16 h-16 p-2 text-center rounded-lg shadow-lg lg:flex bg-white/90 text-sky-700">
                                @if($item->punch_type == 0)
                                    <i class="text-4xl fa-solid fa-circle-chevron-right"></i>
                                @else
                                    <i class="text-4xl fa-solid fa-circle-chevron-left"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>




