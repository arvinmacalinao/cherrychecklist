<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Concerns\ResetsPageOnUpdate;
use App\Livewire\Concerns\Sortable;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, ResetsPageOnUpdate, Sortable;

    protected string $paginationTheme = 'tailwind';

    public function mount(): void
    {
        // Set here rather than as a redeclared property: PHP 8.3 treats a
        // class property overriding a trait property with a different
        // default value as a fatal "incompatible" composition error.
        $this->sortField = 'name';
    }

    public function render()
    {
        $users = User::query()
            ->with('roles', 'designation', 'company')
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', compact('users'))
            ->layout('layouts.admin');
    }
}
