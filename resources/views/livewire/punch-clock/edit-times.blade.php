<div>
    @if($openModal)
        <dialog class="modal modal-open modal-bottom sm:modal-middle" open>
            <div class="modal-box">
                <h3 class="text-lg font-medium text-primary">Editar registro de ponto</h3>
                <form wire:submit="save" class="flex flex-col gap-4 mt-4">
                    <p class="text-sm">Ao salvar, o período será marcado como pendente de recebimento, pois o valor corrigido pode ainda não ter sido pago.</p>
                    @if($editStart)
                        <label class="form-control w-full">
                            <span class="label-text mb-2">Entrada</span>
                            <input type="datetime-local" class="input input-bordered w-full" wire:model="start" required>
                            @error('start') <span class="text-error">{{ $message }}</span> @enderror
                        </label>
                    @endif
                    @if($editEnd)
                        <label class="form-control w-full">
                            <span class="label-text mb-2">Saída</span>
                            <input type="datetime-local" class="input input-bordered w-full" wire:model="end" required>
                        </label>
                    @endif
                    @error('end') <span class="text-error">{{ $message }}</span> @enderror
                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="$set('openModal', false)">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </dialog>
    @endif
</div>
