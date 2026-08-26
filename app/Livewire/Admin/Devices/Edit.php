<?php

namespace App\Livewire\Admin\Devices;

use App\Models\Device;
use App\Models\User;
use Illuminate\Validation\Rule;
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

        $this->users = User::role('employee')->orderBy('name')->get();

        $this->device_type = $device->device_type;
        $this->brand = $device->brand;
        $this->model = $device->model;
        $this->serial_number = $device->serial_number;
        $this->imei = $device->imei;
        $this->asset_tag = $device->asset_tag;
        $this->status = $device->status;

        $this->user_id =
            $device->activeAssignment?->user_id;

        // The employee currently assigned might not hold the "employee" role
        // anymore (e.g. promoted to admin) — keep them selectable so the
        // form still shows who the device is actually assigned to.
        if ($this->user_id && ! $this->users->contains('id', $this->user_id)) {
            $assignedUser = User::find($this->user_id);

            if ($assignedUser) {
                $this->users->push($assignedUser);
            }
        }
    }

    public function save()
    {
        $this->validate([

            'device_type' => 'required',

            'brand' => 'required|string|max:255',

            'model' => 'required|string|max:255',

            'serial_number' => [
                'required', 'string', 'max:255',
                Rule::unique('devices', 'serial_number')->ignore($this->device->id),
            ],

            'imei' => [
                'nullable', 'string', 'max:255',
                Rule::unique('devices', 'imei')->ignore($this->device->id),
            ],

            'asset_tag' => [
                'nullable', 'string', 'max:255',
                Rule::unique('devices', 'asset_tag')->ignore($this->device->id),
            ],

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

        // Assignment logic. The active assignment (if any) is closed out with
        // status=returned/returned_at rather than mutated in place, so
        // assignment history survives reassignment — never more than one
        // active assignment per device at a time.
        $currentAssignment = $this->device->activeAssignment;

        if ($this->user_id) {

            if ($currentAssignment && $currentAssignment->user_id == $this->user_id) {
                // Same employee already assigned — nothing to change.
            } else {

                if ($currentAssignment) {
                    $currentAssignment->update([
                        'status' => 'returned',
                        'returned_at' => now(),
                    ]);
                }

                $this->device
                    ->assignments()
                    ->create([
                        'user_id' => $this->user_id,
                        'status' => 'active',
                        'assigned_at' => now(),
                    ]);
            }

        } elseif ($currentAssignment) {
            // Selector was cleared — unassign the device.
            $currentAssignment->update([
                'status' => 'returned',
                'returned_at' => now(),
            ]);
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
