<div class="flex flex-col items-start justify-start w-full p-4 rounded-lg shadow-lg shadow-sky-100 bg-sky-100/40">

    <div class="flex flex-col items-start justify-start w-full gap-4">
        <p class="text-sm font-medium text-slate-600">Você receberá em instantes, um código em seu e-mail que deverá ser usado para recuperar a senha da sua conta.</p>
        <p class="text-sm font-medium text-slate-600">Em caso de não recebimento, você pode fazer o uso do botão de reenvio do código, para que seja enviado o código novamente.</p>
    </div>

    <div class="flex flex-col w-full gap-4 mt-6">
        <x-input error label labelText="Código recebido por e-mail:" wire:model="token" name="token" type="text" placeholder="Exemplo: bnnl0NGaRQUSnjYV" />
        <x-input error label labelText="Nova senha:" wire:model="password" name="password" type="password" placeholder="********" />
        <x-input error label labelText="Confirme sua nova senha:" wire:model="password_confirmation" name="password_confirmation" type="password" placeholder="********" />
    </div>

    <div class="flex flex-col justify-between w-full gap-4 mt-8 md:flex-row">
        <button class="text-white btn btn-secondary" wire:click="resendMail" wire:loading.attr="disabled">
            Re-enviar código
            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="resendMail"></i>
        </button>
        <button class="text-white btn btn-primary" wire:click="changePassword" wire:loading.attr="disabled">
            Alterar senha
            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="changePassword"></i>
        </button>
    </div>
</div>
