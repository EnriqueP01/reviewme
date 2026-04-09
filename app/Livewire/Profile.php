<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Profile extends Component
{
    public $user;

    public ?string $handle = null;

    public $perPage = 3;

    public function mount(?string $handle = null)
    {
        if ($handle) {
            $this->user = User::where('handle', $handle)->firstOrFail();
            $this->handle = $handle;
        } else {
            $this->user = Auth::user();

            if (! $this->user) {
                return redirect()->route('login');
            }

            $this->handle = $this->user->handle;
        }
    }

    public function loadMore()
    {
        $this->perPage += 3;
    }

    public function getIsOwnProfileProperty(): bool
    {
        return Auth::check() && Auth::id() === $this->user->id;
    }

    public function getStatsProperty()
    {
        return [
            'karma' => $this->user->reputation_score,
            'posts' => $this->user->posts()->count(),
            'joined' => $this->user->created_at->format('M Y'),
            'level' => $this->user->karma_level, // Retourne l'array de config (label, color, etc.)
        ];
    }

    public function getSkillsProperty()
    {
        return $this->user->skills()->orderByDesc('score')->get();
    }

    public function getKarmaHistoryProperty()
    {
        return $this->user->karmaTransactions()
            ->with('source')
            ->take(5)
            ->get();
    }

    public function getContributionsProperty()
    {
        return Cache::remember("user_activity_heatmap_{$this->user->id}", 600, function () {
            $since = now()->subDays(365);
            
            $posts = Post::where('user_id', $this->user->id)
                ->where('created_at', '>=', $since)
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')->toArray();

            $reviews = \App\Models\FullReview::where('user_id', $this->user->id)
                ->where('created_at', '>=', $since)
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')->toArray();

            $comments = \App\Models\PostComment::where('user_id', $this->user->id)
                ->where('created_at', '>=', $since)
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')->toArray();

            $suggestions = \App\Models\InlineSuggestion::where('user_id', $this->user->id)
                ->where('created_at', '>=', $since)
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')->toArray();

            // Fusionner toutes les dates uniques
            $allDates = array_unique(array_merge(
                array_keys($posts),
                array_keys($reviews),
                array_keys($comments),
                array_keys($suggestions)
            ));

            $merged = [];
            foreach ($allDates as $date) {
                $merged[$date] = ($posts[$date] ?? 0) + ($reviews[$date] ?? 0) + ($comments[$date] ?? 0) + ($suggestions[$date] ?? 0);
            }

            return $merged;
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
