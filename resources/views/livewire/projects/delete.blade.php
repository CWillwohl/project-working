<div>
    <button class="text-white btn btn-error" wire:click="$set('openModal', true)">
        <span class="hidden 2xl:flex">Deletar</span>
        <i class="text-lg text-white fa-solid fa-trash"></i>
    </button>

    <dialog id="deleteModal" class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">
            <h3 class="flex items-center gap-2 text-lg font-medium text-primary">
                <i class="fa-solid fa-trash-can"></i>
                Deletar Projeto
            </h3>

            <hr class="my-4">
            <p>Tem certeza de deseja deletar esse projeto?</p>

            <div class="modal-action">
                <div class="flex justify-between w-full">
                    <form method="dialog">
                        <button class="text-white btn btn-primary">
                            <i class="text-lg fa-solid fa-circle-xmark"></i>
                            Fechar
                        </button>
                    </form>
                    <button type="button" class="text-white btn btn-error" wire:click="submit">
                        Deletar Projeto
                        <i class="text-2xl fa-solid fa-ban"></i>
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</div>
