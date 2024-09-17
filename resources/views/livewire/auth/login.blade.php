<div class="flex w-full">
    <div class="flex flex-col w-full min-h-screen p-12 text-center bg-white shadow-lg md:w-1/3 xl:w-1/2 shadow-primary">
        <div class="flex items-center gap-4 text-4xl font-medium text-primary">
            <span>Working | Autentique-se</span>
        </div>

        <hr class="my-4 border-2 rounded-full shadow-md shadow-white border-primary">
        <form id="authenticate-form" wire:submit='submit'>
            <div class="w-full mt-24">
                <div class="flex flex-col gap-4">
                    <x-input error label labelText="E-mail:" wire:model="email" name="email" type="text" placeholder="exemplo@exemplo.com" />
                    <x-input error label labelText="Senha:" wire:model="password" name="password" type="password" placeholder="********" />
                    <div class="flex items-center justify-between w-full">
                        <a href="{{ route('register') }}" class="text-primary hover:underline">Não possuí cadastro?</a>
                        <a href="{{ route('password-recover') }}" class="text-primary hover:underline">Esqueceu sua senha?</a>
                    </div>
                    <div class="flex justify-end w-full mt-8">
                        <button class="text-white btn btn-primary" form="authenticate-form">
                            Entrar em sua conta
                            <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="submit"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="hidden md:flex md:w-2/3 xl:w-1/2">
        <img src="{{ asset('images/working-user.jpg') }}" alt="" srcset="">
    </div>
</div>
