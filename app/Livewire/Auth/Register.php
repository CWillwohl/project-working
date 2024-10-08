<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'email', 'max:255', 'unique:users,email'])]
    public ?string $email = null;

    #[Rule(['required', 'max:32', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function render(): View
    {
        return view('livewire.auth.register')
            ->layout('components.guest-layout');
    }

    public function submit(): Redirector|RedirectResponse
    {
        $this->validate();

        /** @var User $user */
        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);

        return redirect()->route('welcome');
    }
}
