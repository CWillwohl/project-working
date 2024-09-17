<?php

namespace App\Rules;

use App\Models\PasswordResetToken;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateRecoveryToken implements ValidationRule
{
    public function __construct(
        protected string $email
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!PasswordResetToken::validateToken($this->email, $value) || !$value) {
            $fail('Esse código é invalido ou expirado!');
        }
    }
}
