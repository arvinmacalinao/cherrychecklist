<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Throwable;

class SyncEmployeesButton extends Component
{
    public bool $loading = false;
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function sync(): void
    {
        $this->loading = true;
        $this->successMessage = null;
        $this->errorMessage = null;

        try {
            Artisan::call('sync:employees');
            $this->successMessage = 'Employees synced successfully.';
        } catch (Throwable $e) {
            report($e);
            $this->errorMessage = 'Sync failed — the HR system may be unreachable. Please try again.';
        }

        $this->loading = false;
    }

    public function render()
    {
        // Nested component (embedded via <livewire:admin.sync-employees-button />)
        // — no ->layout() here, the host page's layout already applies.
        return view('livewire.admin.sync-employees-button');
    }
}
