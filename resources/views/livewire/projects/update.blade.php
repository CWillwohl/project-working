<div>
    <button class="text-white btn btn-primary" wire:click="$set('openModal', true)">
        <span class="hidden 2xl:flex">Editar</span>
        <i class="text-lg text-white fa-solid fa-pen-to-square"></i>
    </button>

    <dialog id="updateModal" class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">

            <h3 class="flex items-center gap-2 text-lg font-medium text-primary">
                <i class="text-2xl fa-solid fa-pen-to-square"></i>
                Atualizar dados do projeto
            </h3>

            <hr class="my-4">

            <form action="#" class="w-full space-y-4">
                <x-input label error labelText="Nome do projeto:" wire:model="name" name="name" type="text" placeholder="Nome do projeto" value="{{ $project->name }}" />
                <div class="flex w-full">
                    <x-input label error labelText="Preço por hora:" wire:model="price_per_hour" name="price_per_hour" type="text" placeholder="R$ 0,00" value="{{ $project->formated_price_per_hour }}" data-mask="000.000.000.000.000,00" data-mask-reverse="true" />
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
