<?php

namespace App\Livewire\Guard;

use Livewire\Component;

class Scanner extends Component
{
     public function render()
    {
        return view('livewire.guard.scanner')
            ->layout('layouts.app');
    }
}
