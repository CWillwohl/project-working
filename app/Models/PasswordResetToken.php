<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'token'];

    public $timestamps = false;

    public static function createToken($email): self
    {
        self::query()
            ->where('email', $email)
            ->delete();

        return self::query()
            ->create([
                'email'      => $email,
                'token'      => Str::random(16),
                'created_at' => now(),
            ]);
    }

    public static function validateToken($email, $token): bool
    {
        return self::query()
            ->where('email', $email)
            ->where('token', $token)
            ->exists();
    }
}
