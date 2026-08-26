<?php

namespace App\Livewire\Admin\Devices;

use App\Models\Device;
use Livewire\Component;

class Show extends Component
{
    public Device $device;

    public function mount(Device $device): void
    {
        $this->device = $device;
    }

    public function render()
    {
        $this->device->load(['activeAssignment.user']);

        $history = $this->device->assignments()
            ->with('user')
            ->latest('assigned_at')
            ->get();

        return view('livewire.admin.devices.show', [
            'history' => $history,
        ])->layout('layouts.admin');
    }
}
