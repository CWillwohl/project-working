<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\SendPasswordRecoveryMailAction;
use App\Models\{PasswordResetToken, User};
use App\Rules\ValidateRecoveryToken;
use Illuminate\View\View;
use Livewire\Component;

class ChangePassword extends Component
{
    public ?string $email = null;

    public ?string $token = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'max:32',
                'confirmed',
            ],
            'token' => [
                'required',
                new ValidateRecoveryToken($this->email),
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.auth.change-password');
    }

    public function changePassword()
    {
        $this->validate();

        if(PasswordResetToken::validateToken($this->email, $this->token)) {
            $user = User::where('email', $this->email)->first();

            $user->update(['password' => bcrypt($this->password)]);

            auth()->login($user);

            return redirect()->route('welcome');
        }

        session()->flash('token', 'Esse código é invalido ou expirado!');
    }

    public function resendMail(): void
    {
        ((new SendPasswordRecoveryMailAction($this->email))->execute());
    }
}
