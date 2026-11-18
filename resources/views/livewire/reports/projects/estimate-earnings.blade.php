<div class="flex flex-col items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full gap-4 md:flex-row md:items-start">
        <div class="flex flex-col justify-around w-full p-4 bg-white rounded-lg shadow-lg md:w-1/2">
            <x-select-project :projects="$projects" :project="$project" />
            @if($project)
                <div class="h-auto">
                    <h1 class="p-4 my-8 text-lg font-medium text-center border rounded text-primary">{{ $project->name }}</h1>
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
            @endif
        </div>
        <section class="w-full p-4 bg-white rounded-lg shadow-lg md:w-1/2">
            <h1 class="text-lg font-medium">Estimar valores</h1>

            @if($project)
                <div class="flex flex-col w-full gap-4 mt-4 md:flex-row">
                    <x-input error label labelText="Data inicial" wire:model.change="dateStart" name="dateStart" type="date" />
                    <x-input error label labelText="Data final" wire:model.change="dateEnd" name="dateEnd" type="date" />
                </div>

                @if($periodErrors?->any())
                    <p class="mt-2 text-error" role="alert">{{ $periodErrors->first() }}</p>
                @endif

                <div class="grid gap-4 mt-4 sm:grid-cols-2">
                    <div class="border border-zinc-200 rounded-lg stat">
                        <p class="stat-title">Horas por dia</p>
                        <div class="flex items-center gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-primary" aria-label="Diminuir horas por dia"
                                wire:click="decrementTimeWorked" wire:loading.attr="disabled" @disabled($timeWorked === 0)>−</button>
                            <output class="min-w-8 text-center text-xl font-medium" aria-label="Horas por dia">{{ $timeWorked }}</output>
                            <button type="button" class="btn btn-sm btn-primary" aria-label="Aumentar horas por dia"
                                wire:click="incrementTimeWorked" wire:loading.attr="disabled" @disabled($timeWorked === 24)>+</button>
                        </div>
                    </div>

                    <div class="border border-zinc-200 rounded-lg stat">
                        <p class="stat-title">Dias previstos de trabalho</p>
                        <output class="text-xl stat-value" aria-label="Dias previstos de trabalho">{{ $estimate['days'] ?? '—' }}</output>
                    </div>
                </div>

                <details class="p-4 mt-4 border border-zinc-200 rounded-lg" wire:key="weekdays-{{ $project->id }}" wire:ignore.self>
                    <summary class="font-medium cursor-pointer">
                        Configurar dias de trabalho
                        <span class="block mt-2 text-sm font-normal text-slate-600">
                            {{ $savedWeekdays ? 'Dias excluídos: ' . implode(', ', $savedWeekdays) : 'Todos os dias da semana são considerados.' }}
                        </span>
                    </summary>

                    <form class="mt-3 space-y-3" wire:submit="saveWeekdays">
                        <button type="button" class="btn btn-sm btn-outline" aria-pressed="{{ $weekendsExcluded ? 'true' : 'false' }}"
                            wire:click="toggleWeekends" wire:loading.attr="disabled">Desconsiderar fins de semana</button>

                        <fieldset>
                            <legend class="mb-2 font-medium">Desconsiderar estes dias</legend>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach($weekdays as $day => $label)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" class="checkbox checkbox-sm checkbox-primary"
                                            wire:model.live.number="excludedWeekdays" value="{{ $day }}">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        @if($errors->has('excludedWeekdays') || $errors->has('excludedWeekdays.*'))
                            <p class="text-error" role="alert">{{ $errors->first('excludedWeekdays') ?: $errors->first('excludedWeekdays.*') }}</p>
                        @endif

                        <div class="flex flex-wrap items-center gap-3">
                            <button class="btn btn-sm btn-primary" type="submit" wire:loading.attr="disabled">Salvar e aplicar</button>
                            @if($weekdaysSaved)
                                <p class="text-sm text-green-700" role="status">Configuração salva.</p>
                            @endif
                        </div>
                    </form>
                </details>

                <div class="mt-4 border border-zinc-200 border-r-4 border-r-green-500 rounded-lg stat">
                    <p class="stat-title">Valor estimado no período</p>
                    <output class="text-xl stat-value" aria-label="Valor estimado no período">{{ $estimate['total'] ?? '—' }}</output>
                </div>

                <button type="button" class="w-full mt-4 btn btn-primary" wire:click="generatePdf"
                    wire:loading.attr="disabled" @disabled(!$estimate)>Baixar PDF</button>
            @else
                <p class="mt-4 text-slate-600">Selecione um projeto para estimar os ganhos.</p>
            @endif
        </section>
    </div>
</div>
