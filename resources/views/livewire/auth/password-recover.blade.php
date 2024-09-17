<div class="flex w-full">
    <div class="flex flex-col w-full min-h-screen p-12 text-center bg-white shadow-lg md:w-1/3 xl:w-1/2 shadow-primary">
        <div class="flex items-center gap-4 text-4xl font-medium text-primary">
            <span>Working | Recupere sua senha</span>
        </div>

        <hr class="my-4 border-2 rounded-full shadow-md shadow-white border-primary">
        <div class="w-full mt-24">
            <div class="flex flex-col gap-4">
                <x-input error label labelText="E-mail de recuperação:" wire:model="email" name="email" type="text" placeholder="exemplo@exemplo.com" />
                <div class="flex items-center justify-between w-full">
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Deseja realizar sua autenticação?</a>
                </div>

                @if($sended)
                    <livewire:auth.change-password :email="$email" />
                @endif

                @if(!$sended)
                    <div class="flex justify-end w-full mt-8">
                        <button wire:click="send" class="text-white btn btn-primary">
                            Enviar e-mail de recuperação
                            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="send"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="hidden md:flex md:w-2/3 xl:w-1/2">
        <img src="{{ asset('images/working-user.jpg') }}" alt="" srcset="">
    </div>
</div>
