<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class Dashboard extends Component
{
    public function regenerateQr(): void
    {
        auth()->user()->regenerateQrToken();

        session()->flash('message', 'A new QR code has been generated. Your previous QR code no longer works.');
    }

    public function render()
    {
        $user = auth()->user()->load(['activeDevices.device', 'company', 'designation']);

        return view('livewire.employee.dashboard', [
            'user' => $user,
            'qrUrl' => $user->qrCodeUrl(),
        ])->layout('layouts.employee');
    }
}
