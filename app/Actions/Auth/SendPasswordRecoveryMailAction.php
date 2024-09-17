<?php

namespace App\Actions\Auth;

use App\Jobs\SendPasswordRecoveryEmailToken;
use App\Models\{PasswordResetToken, User};

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

        SendPasswordRecoveryEmailToken::dispatch($this->email, $user, $token);
    }
}
