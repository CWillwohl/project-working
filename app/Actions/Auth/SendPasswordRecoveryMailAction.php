<?php

namespace App\Actions\Auth;

use App\Mail\PasswordRecoveryToken;
use App\Models\{PasswordResetToken, User};
use Illuminate\Support\Facades\Mail;

class SendPasswordRecoveryMailAction
{
    public function __construct(
        private string $email
    ) {
    }

    public function execute(): void
    {
        $user  = User::where('email', $this->email)->first()->name;
        $token = PasswordResetToken::createToken($this->email)->token;

        Mail::to($this->email)->send(new PasswordRecoveryToken($token, $user));
    }
}
