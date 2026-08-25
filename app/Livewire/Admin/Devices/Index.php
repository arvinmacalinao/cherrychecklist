<?php

namespace App\Livewire\Admin\Devices;

use App\Livewire\Concerns\ResetsPageOnUpdate;
use App\Livewire\Concerns\Sortable;
use App\Models\Device;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, ResetsPageOnUpdate, Sortable;

    public function render()
    {
        $devices = Device::with('activeAssignment.user')
            ->when($this->search, function ($query) {
                $query->where('device_type', 'like', '%' . $this->search . '%')
                      ->orWhere('brand', 'like', '%' . $this->search . '%')
                      ->orWhere('model', 'like', '%' . $this->search . '%')
                      ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                      ->orWhere('imei', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.devices.index', compact('devices'))
            ->layout('layouts.admin');
    }
}
