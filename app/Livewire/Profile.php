<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
        return Cache::remember("user_stats_{$this->user->id}", 600, function () {
            $ups = Reaction::whereHasMorph('reactable', [Post::class], function ($q) {
                $q->where('user_id', $this->user->id);
            })->where('type', 'mindblown')->count();

            $downs = Reaction::whereHasMorph('reactable', [Post::class], function ($q) {
                $q->where('user_id', $this->user->id);
            })->where('type', 'optimisable')->count();

            return [
                'karma' => ($ups * 10) - ($downs * 2),
                'posts' => $this->user->posts()->count(),
                'joined' => $this->user->created_at->format('M Y'),
                'level' => 'Senior Curator',
            ];
        });
    }

    public function getContributionsProperty()
    {
        return Cache::remember("user_contributions_{$this->user->id}", 600, function () {
            return Post::where('user_id', $this->user->id)
                ->where('created_at', '>=', now()->subDays(365))
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        });
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
