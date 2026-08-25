<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

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

    public function render()
    {
        $this->user->load(['roles', 'company', 'designation', 'activeDevices.device']);

        return view('livewire.admin.users.show')
            ->layout('layouts.admin');
    }
}
