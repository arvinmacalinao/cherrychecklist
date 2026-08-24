<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class SyncEmployeesButton extends Component
{
    public $loading = false;

    public function sync()
    {
        $this->loading = true;

        Artisan::call('sync:employees');

        $this->loading = false;

        session()->flash(
            'message',
            'Employees synced successfully.'
        );
    }

    public function render()
    {
        return view('livewire.admin.sync-employees-button')
         ->layout('layouts.admin');
    }
}
