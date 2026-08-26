<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use App\Models\Device;
use App\Models\User;
use App\Models\EmployeeDeviceAssignment;

class Create extends Component
{
    public $device_type, $brand, $model, $serial_number, $imei, $asset_tag;
    public $user_id;

    public $users = [];

    // Bumped after every save and used as the form's wire:key. Livewire's
    // morph preserves a "dirty" text input's DOM value rather than
    // overwriting it from the reset server state, so resetting the
    // properties alone leaves the fields visually unchanged — changing the
    // key forces Livewire to discard and remount the form instead of
    // morphing it, which actually clears it.
    public $formKey = 0;

    public function mount()
    {
        $this->users = User::role('employee')->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'device_type' => 'required',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:devices,serial_number',
            'imei' => 'nullable|string|max:255|unique:devices,imei',
            'asset_tag' => 'nullable|string|max:255|unique:devices,asset_tag',
        ]);

        $device = Device::create([
            'device_type' => $this->device_type,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'imei' => $this->imei,
            'asset_tag' => $this->asset_tag,
            'status' => 'active',
        ]);

        // 🔥 Assign if selected
        if ($this->user_id) {
            EmployeeDeviceAssignment::create([
                'user_id' => $this->user_id,
                'device_id' => $device->id,
                'status' => 'active',
                'assigned_at' => now(),
            ]);
        }

        session()->flash('message', 'Device created successfully');

        $this->reset(['device_type', 'brand', 'model', 'serial_number', 'imei', 'asset_tag', 'user_id']);
        $this->formKey++;

        // The searchable-select's wire:ignore means Livewire's own reset
        // above never reaches TomSelect's DOM — it has to be cleared
        // through its own JS API instead.
        $this->dispatch('reset-searchable-select', id: 'user-select');
    }

    public function render()
    {
        return view('livewire.admin.devices.create')->layout('layouts.admin');
    }
}
