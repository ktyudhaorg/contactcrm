<?php

namespace App\Livewire\Ai;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Ai Assistant')]

class Ai extends Component
{
    public function render()
    {
        return view('livewire.ai.index');
    }
}
