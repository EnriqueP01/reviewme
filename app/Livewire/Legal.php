<?php

namespace App\Livewire;
 
use Livewire\Attributes\Layout;
use Livewire\Component;
 
class Legal extends Component
{
    public string $type = 'privacy';
 
    public function mount(string $type = 'privacy')
    {
        $this->type = $type;
    }
 
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.legal');
    }
}
