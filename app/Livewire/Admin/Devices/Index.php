<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Device;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortAsc = true;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

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
