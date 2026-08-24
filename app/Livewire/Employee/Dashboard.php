<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\URL;

class Dashboard extends Component
{
    public $qrUrl;

    public function mount()
    {
        // only generate if not existing
        if (!session()->has('qr_url')) {
            $this->generateQr();
        } else {
            $this->qrUrl = session('qr_url');
        }
    }

    public function generateQr()
    {
        $this->qrUrl = URL::temporarySignedRoute(
            'qr.verify',
            now()->addDays(90),
            ['user' => auth()->id()]
        );

        // store it
        session(['qr_url' => $this->qrUrl]);

        return redirect()->route('employee.dashboard');
    }

    public function render()
    {
        $user = auth()->user()->load('activeDevices.device');

        return view('livewire.employee.dashboard', [
            'user' => $user
        ])->layout('layouts.app');
    }
}
