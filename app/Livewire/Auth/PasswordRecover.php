<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\SendPasswordRecoveryMailAction;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PasswordRecover extends Component
{
    public ?bool $sended = false;

    #[Rule(['required', 'email', 'max:255', 'exists:users,email'])]
    public ?string $email = null;

    public function render(): View
    {
        return view('livewire.auth.password-recover')
            ->layout('components.guest-layout');
    }

    public function send(): void
    {
        $this->validate();

        ((new SendPasswordRecoveryMailAction($this->email))->execute());

        $this->sended = true;
    }
}
