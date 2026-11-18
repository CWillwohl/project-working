<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\SendPasswordRecoveryMailAction;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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

        try {
            (new SendPasswordRecoveryMailAction($this->email))->execute();
        } catch (TransportExceptionInterface $exception) {
            report($exception);
            $this->addError('email', 'Não foi possível enviar o e-mail de recuperação. Tente novamente mais tarde.');

            return;
        }

        $this->sended = true;
    }
}
