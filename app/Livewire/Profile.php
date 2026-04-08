<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $user;
    public $perPage = 3;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function loadMore()
    {
        $this->perPage += 3;
    }

    public function getStatsProperty()
    {
        $ups = \App\Models\Reaction::whereHasMorph('reactable', [\App\Models\Post::class], function($q) {
                $q->where('user_id', $this->user->id);
            })->where('type', 'mindblown')->count();
            
        $downs = \App\Models\Reaction::whereHasMorph('reactable', [\App\Models\Post::class], function($q) {
                $q->where('user_id', $this->user->id);
            })->where('type', 'optimisable')->count();

        return [
            'karma' => ($ups * 10) - ($downs * 2),
            'posts' => $this->user->posts()->count(),
            'joined' => $this->user->created_at->format('M Y'),
            'level' => 'Senior Curator',
        ];
    }

    public function getContributionsProperty()
    {
        // Récupère le nombre de posts par jour pour les 365 derniers jours
        return \App\Models\Post::where('user_id', $this->user->id)
            ->where('created_at', '>=', now()->subDays(365))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    public function getRecentActivityProperty()
    {
        return $this->user->posts()
            ->withCount('reactions')
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.profile', [
            'stats' => $this->stats,
            'posts' => $this->recentActivity,
            'contributions' => $this->contributions,
        ]);
    }
}
