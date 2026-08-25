<?php

namespace App\Livewire\Concerns;

trait ResetsPageOnUpdate
{
    public $search = '';
    public $perPage = 10;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }
}
