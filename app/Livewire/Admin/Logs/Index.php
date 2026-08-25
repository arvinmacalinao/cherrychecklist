<?php

namespace App\Livewire\Admin\Logs;

use App\Livewire\Concerns\ResetsPageOnUpdate;
use App\Models\ExitLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, ResetsPageOnUpdate;

    public $date;

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = ExitLog::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->date, function ($query) {
                $query->whereDate('scanned_at', $this->date);
            })
            ->latest('scanned_at')
            ->paginate($this->perPage);

        return view('livewire.admin.logs.index', compact('logs'))
            ->layout('layouts.admin');
    }
}
