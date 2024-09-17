<div>
    <dialog class="modal modal-bottom sm:modal-middle" @if($openModal) open @endif>
        <div class="modal-box">
            <h3 class="flex flex-col items-center justify-center gap-2 text-lg font-medium text-center">
                @if($typeModal == 'received')
                <div class="flex items-center justify-center w-16 h-16 border-2 rounded-full shadow-lg border-sky-600 text-sky-600 bg-sky-100">
                    <i class="text-3xl fa-solid fa-sack-dollar"></i>
                </div>
                <span class="text-sky-600">
                    Deseja atualizar o status dos periodos selecionados como recebido?
                </span>
                @elseif ($typeModal == 'pending')
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 border-2 rounded-full shadow-lg border-success text-success">
                    <i class="text-3xl fa-solid fa-clock"></i>
                </div>
                <span class="text-success">
                    Deseja atualizar o status dos periodos selecionados como pendente?
                </span>
                @endif
            </h3>

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
                        Atualizar Status
                        <i class="text-2xl fa-solid fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</div>
