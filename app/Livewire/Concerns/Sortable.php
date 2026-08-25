<?php

namespace App\Livewire\Concerns;

trait Sortable
{
    public $sortField = 'id';
    public $sortAsc = true;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
}
