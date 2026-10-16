<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    // Shown once right after a reset, never persisted or reloaded from the
    // database — same "shown once" rule as AdminUserSeeder's own output.
    public ?string $generatedPassword = null;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function toggleStatus(): void
    {
        $this->user->update([
            'status' => $this->user->status === 'active' ? 'inactive' : 'active',
        ]);

        session()->flash(
            'message',
            $this->user->status === 'active'
                ? 'User marked as active.'
                : 'User marked as inactive.'
        );
    }

    /**
     * For a user who's forgotten their password: generates a new random
     * one, forces them to set their own on next login, and shows it here
     * once so the admin can hand it over.
     */
    public function resetPassword(): void
    {
        $password = Str::password(12, symbols: false);

        $this->user->forceFill([
            'password' => $password,
            'must_change_password' => true,
        ])->save();

        $this->generatedPassword = $password;
    }

    public function render()
    {
        $this->user->load(['roles', 'company', 'designation', 'activeDevices.device']);

        return view('livewire.admin.users.show')
            ->layout('layouts.admin');
    }
}
