<?php

namespace App\Livewire;
 
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
 
class Leaderboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $topUsers = User::orderBy('reputation_score', 'desc')
            ->limit(10)
            ->get();
 
        return view('livewire.leaderboard', [
            'topUsers' => $topUsers,
        ]);
    }
}
