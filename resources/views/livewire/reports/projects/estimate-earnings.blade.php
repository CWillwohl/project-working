<div class="flex flex-col items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full gap-4 md:flex-row">
        <div class="flex flex-col justify-around w-full p-4 bg-white rounded-lg shadow-lg md:w-1/2">
            <x-select-project :projects="$projects" :project="$project"  />
            <div class="h-auto">
                <h1 class="p-4 my-8 text-lg font-medium text-center border rounded text-primary">{{ $project->name ?? 'Selecione um Projeto' }}</h1>
                <div class="flex flex-col w-full space-y-4">
                    <div class="h-auto rounded-lg border border-zinc-200 border-l-4 border-l-primary stat">
                        <div class="flex-wrap text-sm whitespace-pre-line stat-title">Valor recebido por hora trabalhada:</div>
                        <div class="text-lg text-slate-600 stat-value">
                        {{ $project->formatted_price_per_hour }}
                        </div>
                    </div>
                    <div class="h-auto rounded-lg border border-zinc-200 border-l-4 border-l-green-500 stat">
                        <div class="flex-wrap text-sm whitespace-pre-line stat-title">Total de valores recebidos:</div>
                        <div class="text-lg text-slate-600 stat-value">
                        {{ $project->formatted_total_received }}
                        </div>
                    </div>
                    <div class="h-auto rounded-lg border border-zinc-200 border-l-4 border-l-sky-500 stat">
                        <div class="flex-wrap text-sm whitespace-pre-line stat-title">Total de valores para receber:</div>
                        <div class="text-lg text-slate-600 stat-value">
                        {{ $project->formatted_price_to_receive }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-between w-full p-4 bg-white rounded-lg shadow-lg md:w-1/2">
            <div class="flex-col w-full">
                <h1 class="text-lg font-medium">
                    Estimar valores:
                </h1>
                <div class="flex flex-col w-full gap-4 mt-8 md:flex-row">
                    <x-input error label labelText="Data inicial:" wire:model="dateStart" name="dateStart" type="date" placeholder="exemplo@exemplo.com" />
                    <x-input error label labelText="Data final:" wire:model="dateEnd" name="dateEnd" type="date" placeholder="exemplo@exemplo.com" />
                </div>
                <button class="w-full mt-4 btn btn-primary" wire:click="calculateEarnings">Calcular ganhos</button>
            </div>

            <div class="flex flex-col items-center justify-center w-full gap-4 mt-8 md:flex-row">
                <div class="flex flex-col items-center justify-center w-full gap-4">
                    <p class="text-base text-center">Quantas horas serão trabalhas por dia?</p>
                    <div class="flex items-center justify-center w-48 gap-4 p-4 border rounded-lg shadow">
                        <p class="w-24 text-zinc-600 text-6xl font-medium">{{ $timeWorked }}</p>
                        <div class="h-16 border"></div>
                        <div class="flex flex-col items-center w-16 gap-2">
                            <button class="btn btn-md btn-primary" wire:click="incrementTimeWorked">
                                <i class="text-white fa-solid fa-plus"></i>
                            </button>
                            <button class="btn btn-md btn-primary" wire:click="decrementTimeWorked">
                                <i class="text-white fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col w-full space-y-4">
                    <div class="h-auto rounded-lg border border-zinc-200 stat border-r-4 border-r-primary">
                        <div class="flex-wrap text-sm whitespace-pre-line stat-title">Valor recebido por hora trabalhada:</div>
                        <div class="text-lg stat-value text-zinc-600">
                        {{ $project->formatted_price_per_hour }}
                        </div>
                    </div>
                    <div class="h-auto rounded-lg border border-zinc-200 stat border-r-4 border-r-green-500">
                        <p class="flex-wrap text-sm whitespace-pre-line stat-title">Valor estimado no período selecionado:</p>
                        <div class="text-lg stat-value text-zinc-600">
                            {{ $totalEarnings }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-col w-full gap-4 p-4 mt-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-lg font-medium">Ganhos estimados: </h1>
        <div class="flex flex-col items-center justify-center w-full gap-4 mt-4 md:flex-row">
            <div class="rounded-lg border border-zinc-200 border-l-4 border-l-primary stat">
                <div class="text-sm whitespace-pre-line stat-title mb-1">Valor recebido por um dia:</div>
                <div class="text-xl md:text-4xl stat-value text-slate-600">
                    {{ isset($estimatedValues['Day']) ? $estimatedValues['Day'] : 'R$ 0,00' }}
                </div>
            </div>

            <div class="rounded-lg border border-zinc-200 border-l-4 border-l-primary stat">
                <div class="text-sm whitespace-pre-line stat-title mb-1">Valor recebido por 15 dias:</div>
                <div class="text-xl md:text-4xl stat-value text-slate-600">
                    {{ isset($estimatedValues['15Days']) ? $estimatedValues['15Days'] : 'R$ 0,00' }}
                </div>
            </div>

            <div class="rounded-lg border border-zinc-200 border-l-4 border-l-primary stat">
                <div class="text-sm whitespace-pre-line stat-title mb-1">Valor recebido por 30 dias:</div>
                <div class="text-xl md:text-4xl stat-value text-slate-600">
                    {{ isset($estimatedValues['Monthly']) ? $estimatedValues['Monthly'] : 'R$ 0,00' }}
                </div>
            </div>
        </div>
    </div>
</div>

