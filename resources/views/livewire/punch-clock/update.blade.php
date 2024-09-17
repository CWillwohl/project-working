<div>
    <button class="btn btn-primary" wire:click="$set('openModal', true)">
        <i class="text-lg text-white fa-solid fa-pen-to-square"></i>
        <span class="hidden md:flex">Editar</span>
    </button>

    <dialog id="updateModal" class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">

            <h3 class="flex items-center gap-2 text-lg font-medium text-primary">
                <i class="text-2xl fa-solid fa-pen-to-square"></i>
                Atualizar Registro de Ponto #{{ $punchClock->id }}
            </h3>

            <hr class="my-4">
            <form action="#" class="flex flex-col w-full gap-4">
                <div class="text-lg font-medium text-center">
                    Data do registro:
                    <p>{{ date('d/m/Y', strtotime($punchClock->punch_time)) }}</p>
                </div>
                <hr>
                <div class="flex flex-row w-full gap-4">
                    <x-select label error labelText="Hora:" wire:model="hours" name="hours">
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </x-select>

                    <x-select label error labelText="Minuto:" wire:model="minutes" name="minutes" class="w-1/2">
                        @for ($i = 0; $i < 60; $i++)
                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </x-select>
                </div>
            </form>

            <hr class="my-4">

            <div class="modal-action">
                <div class="flex justify-between w-full">
                    <form method="dialog">
                        <button class="text-white btn btn-error">
                            <i class="text-lg fa-solid fa-circle-xmark"></i>
                            Fechar
                        </button>
                    </form>
                    <button type="button" class="text-white btn btn-success" wire:click="submit">
                        Finalizar Edição
                        <i class="text-2xl fa-solid fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</div>
