<?php

namespace App\Livewire\Admin\Devices;

use App\Models\Device;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Device $device;

    public $device_type;
    public $brand;
    public $model;
    public $serial_number;
    public $imei;
    public $asset_tag;
    public $status;
    public $assignment;
    public $user_id;

public $users = [];

    public function mount(Device $device)
    {
        $this->device = $device;

        $this->users = User::orderBy('name')->get();

        $this->device_type = $device->device_type;
        $this->brand = $device->brand;
        $this->model = $device->model;
        $this->serial_number = $device->serial_number;
        $this->imei = $device->imei;
        $this->asset_tag = $device->asset_tag;
        $this->status = $device->status;

        $this->user_id =
            $device->activeAssignment?->user_id;
    }

    public function save()
    {
        $this->validate([

            'device_type' => 'required',

            'brand' => 'required|string|max:255',

            'model' => 'required|string|max:255',

            'serial_number' => 'required|string|max:255',

            'imei' => 'nullable|string|max:255',

            'asset_tag' => 'nullable|string|max:255',

            'status' => 'required',

        ]);

        $this->device->update([

            'device_type' => $this->device_type,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'imei' => $this->imei,
            'asset_tag' => $this->asset_tag,
            'status' => $this->status,

        ]);

        // Assignment logic
        if ($this->user_id) {

            $assignment =
                $this->device->activeAssignment;

            if ($assignment) {

                $assignment->update([
                    'user_id' => $this->user_id,
                ]);

            } else {

                $this->device
                    ->assignments()
                    ->create([
                        'user_id' => $this->user_id,
                        'assigned_at' => now(),
                    ]);
            }

        }

        session()->flash(
            'message',
            'Device updated successfully.'
        );
    }

    public function render()
    {
        return view('livewire.admin.devices.edit')
            ->layout('layouts.admin');
    }
}
