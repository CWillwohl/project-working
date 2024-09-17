<div>
    <button type="button" class="w-48 text-white btn btn-info" wire:click="$set('openModal', true)">
        <i class="fa-regular fa-eye"></i>
        Descrição
    </button>

    <dialog id="createModal" class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">

            <h3 class="flex items-center gap-2 text-lg font-medium text-primary">
                <i class="text-2xl fa-solid fa-circle-plus"></i>
                Gerenciar Descrição
            </h3>

            <hr class="my-4">

            <form action="#" class="w-full space-y-4">
            @csrf
                <x-input label error labelText="Descrição:" wire:model="description" name="description" type="text" placeholder="Exemplo: Atuação no Modulo X" />
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
                        Finalizar
                        <i class="text-2xl fa-solid fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</div>
