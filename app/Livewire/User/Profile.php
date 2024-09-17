<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{

    public ?User $user;

    #[Validate('required|max:255', as: 'nome')]
    public ?string $name                = null;
    #[Validate('required|max:255|email', as: 'e-mail')]
    public ?string $email               = null;
    #[Validate('bool', as: 'Alterar senha')]
    public ?bool $changePassword        = false;
    #[Validate('nullable|required_if:changePassword,true|min:8', as: 'senha')]
    public ?string $currentPassword     = null;
    #[Validate('nullable|required_if:changePassword,true|min:8', as: 'nova senha')]
    public ?string $newPassword         = null;
    #[Validate('nullable|required_if:changePassword,true|min:8|same:newPassword', as: 'confirme sua nova senha')]
    public ?string $newPasswordConfirm  = null;

    protected function messages(): array
    {
        return [
            'required_if' => 'O campo :attribute é obrigatorio quando você sinaliza que voce deseja alterar a sua senha.',
        ];
    }

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function render(): View
    {
        return view('livewire.user.profile')
            ->layout('components.app-layout', ['title' => 'Perfil']);
    }

    public function submitChanges(): void
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->changePassword) {
            if (!Hash::check($this->currentPassword, $this->user->password)) {
                $this->addError('currentPassword', 'Senha atual não confere.');
                return;
            }

            $this->user->update([
                'password' => bcrypt($this->newPassword),
            ]);
        }

        $this->dispatch('alert::handle', [
            'type'    => 'success',
            'message' => 'Dados de Perfil alterados com sucesso',
        ]);
    }
}
