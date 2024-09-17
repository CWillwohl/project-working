<div class="flex w-full">
    <div class="flex flex-col w-full min-h-screen p-12 text-center bg-white shadow-lg md:w-1/3 xl:w-1/2 shadow-primary">
        <div class="flex items-center gap-4 text-4xl font-medium text-primary">
            <span>Working | Cadastre-se</span>
        </div>

        <hr class="my-4 border-2 rounded-full shadow-md shadow-white border-primary">

        <div class="w-full mt-24">
            <form id="register-form" wire:submit='submit'>
                <div class="flex flex-col gap-4">
                    <x-input error label labelText="Nome:" wire:model="name" name="name" type="text" placeholder="Nome Sobrenome" />
                    <x-input error label labelText="E-mail:" wire:model="email" name="email" type="text" placeholder="exemplo@exemplo.com" />
                    <x-input error label labelText="Senha:" wire:model="password" name="password" type="password" placeholder="********" />
                    <x-input error label labelText="Confirme sua senha:" wire:model="password_confirmation" name="password_confirmation" type="password" placeholder="********" />

                    <div class="flex items-center justify-between w-full">
                        <a href="{{ route('login') }}" class="text-primary hover:underline">Já possuí cadastro?</a>
                    </div>

                    <div class="flex justify-end w-full mt-8">
                        <button class="text-white btn btn-primary" form="register-form">
                            Realizar Cadastro
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
