<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $user;
    public array $stats = [
        'karma' => 12450,
        'reviews' => 45,
        'level' => 'Senior Curator',
        'joined' => 'March 2026',
    ];

    public array $recent_activity = [
        [
            'type' => 'review',
            'title' => 'Optimizing SVG rendering',
            'date' => '2 days ago',
            'karma' => '+450',
        ],
        [
            'type' => 'comment',
            'title' => 'Clean API handling',
            'date' => '1 week ago',
            'karma' => '+120',
        ],
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.profile');
    }
}
