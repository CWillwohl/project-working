<div>
    <button class="w-48 text-white btn btn-success" wire:click="$set('openModal', true)">
        <i class="text-xl fa-solid fa-square-plus"></i>
        Novo Projeto
    </button>

    <dialog id="createModal" class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">

            <h3 class="flex items-center gap-2 text-lg font-medium text-primary">
                <i class="text-2xl fa-solid fa-circle-plus"></i>
                Cadastrar novo projeto
            </h3>

            <hr class="my-4">

            <form action="#" class="w-full space-y-4">
            @csrf
                <x-input label error labelText="Nome do projeto:" wire:model="name" name="name" type="text" placeholder="Nome do projeto" />
                <x-input label error labelText="Preço por hora:" wire:model="price_per_hour" name="price_per_hour" type="text" placeholder="R$ 0,00" data-mask="000.000.000.000.000,00" data-mask-reverse="true" />
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
                        Finalizar Cadastro
                        <i class="text-2xl fa-solid fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</div>
