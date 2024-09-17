<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule(['required', 'email', 'max:255'])]
    public ?string $email = null;

    #[Rule(['required'])]
    public ?string $password = null;

    public function render(): View
    {
        return view('livewire.auth.login')
            ->layout('components.guest-layout');
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'email'    => $this->email,
            'password' => $this->password,
        ];

        if(auth()->attempt($data)) {
            return redirect()->route('welcome');
        }

        $this->addError('email', 'Dados não encontrados na base de registros!');
    }
}
