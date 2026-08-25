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

    public function mount()
    {
        $this->users = User::role('employee')->orderBy('name')->get();
    }

    public function save()
    {
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

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.devices.create')->layout('layouts.admin');
    }
}
