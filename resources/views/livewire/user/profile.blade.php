<div class="flex items-center justify-center w-full">
    <div class="flex flex-col justify-between w-full p-4 overflow-x-auto bg-white rounded-lg md:overflow-hidden">
        <div class="flex flex-row w-full gap-4">
            <div class="items-start justify-center hidden w-1/2 md:flex">
                <div class="flex items-center justify-center p-4 rounded-full shadow-md w-96 h-96 bg-slate-100 text-primary/50">
                    <p class="text-7xl">{{ $user->name[0] }}</p>
                </div>
            </div>
            <div class="flex flex-col justify-between w-full gap-4 md:w-2/3">
                <div class="flex flex-col gap-4">
                    <x-input wire:model="name" label labelText="Nome:" name="name" type="text" placeholder="Nome" />
                    <x-input wire:model="email" label labelText="E-mail:" name="email" type="text" placeholder="E-mail" />
                    <div class="flex flex-col form-control w-52">
                        <label class="cursor-pointer label">
                          <span class="text-zinc-500">Alterar Senha:</span>
                          <input type="checkbox" class="toggle toggle-primary" wire:click="$toggle('changePassword')" />
                        </label>
                      </div>
                    <hr>
                    @if ($changePassword)
                        <x-input wire:model="currentPassword" name="currentPassword" error label labelText="Senha:" type="password" placeholder="********" />
                        <x-input wire:model="newPassword" name="newPassword" error label labelText="Nova senha:" type="password" placeholder="********" />
                        <x-input wire:model="newPasswordConfirm" name="newPasswordConfirm" error label labelText="Confirme sua nova senha:" type="password" placeholder="********" />
                    @endif
                </div>
                <button class="w-full btn btn-primary" wire:click="submitChanges">Atualizar perfil</button>
            </div>
        </div>
    </div>
</div>
