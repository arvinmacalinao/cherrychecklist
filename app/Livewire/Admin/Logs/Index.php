<?php

namespace App\Livewire\Admin\Logs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExitLog;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $date;

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
