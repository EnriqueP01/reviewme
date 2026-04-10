<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * @property-read array $stats
 * @property-read array $contributions
 * @property-read Collection $recentActivity
 */
class Profile extends Component
{
    public $user;

    public ?string $handle = null;

    public $perPage = 3;

    public $period = 'year'; // week, month, year

    public $readyToLoad = false;
    public $showKarmaGuide = false;

    public function loadData()
    {
        $this->readyToLoad = true;
    }

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

    public function setPeriod(string $period)
    {
        $this->period = $period;
        Cache::forget("user_activity_heatmap_{$this->user->id}");
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
        if (! $this->readyToLoad) {
            return [];
        }

        return Cache::remember("user_activity_heatmap_{$this->user->id}_{$this->period}", 600, function () {
            $daysMap = ['week' => 7, 'month' => 30, 'year' => 365];
            $days = $daysMap[$this->period] ?? 365;
            $since = now()->subDays($days);

            // Requête groupée performante
            $activity = \DB::table('posts')
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->where('user_id', $this->user->id)
                ->where('created_at', '>=', $since)
                ->groupBy('date')
                ->unionAll(
                    \DB::table('full_reviews')
                        ->selectRaw('DATE(created_at) as date, count(*) as count')
                        ->where('user_id', $this->user->id)
                        ->where('created_at', '>=', $since)
                        ->groupBy('date')
                )
                ->unionAll(
                    \DB::table('post_comments')
                        ->selectRaw('DATE(created_at) as date, count(*) as count')
                        ->where('user_id', $this->user->id)
                        ->where('created_at', '>=', $since)
                        ->groupBy('date')
                )
                ->get();

            return $activity->groupBy('date')->map->sum('count')->toArray();
        });
    }

    public function getActivityGridProperty()
    {
        if (! $this->readyToLoad) {
            return [];
        }

        $daysMap = [
            'week' => 7,
            'month' => 30,
            'year' => 140, // Affichage compact pour l'année
        ];

        $count = $daysMap[$this->period] ?? 140;
        $contributions = $this->contributions;
        $grid = [];

        for ($i = 0; $i < $count; $i++) {
            $date = now()->subDays($count - 1 - $i)->format('Y-m-d');
            $grid[] = [
                'date' => $date,
                'count' => $contributions[$date] ?? 0,
            ];
        }

        return $grid;
    }

    public function getRecentActivityProperty()
    {
        if (! $this->readyToLoad) {
            return collect();
        }

        return $this->user->posts()
            ->with(['user', 'latestSnippet', 'snippets'])
            ->withCount([
                'reactions as up_count' => fn ($q) => $q->where('type', 'mindblown'),
                'reactions as down_count' => fn ($q) => $q->where('type', 'optimisable'),
            ])
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $posts = collect();
        $stats = [
            'karma' => 0,
            'posts' => 0,
            'joined' => '...',
            'level' => ['label' => '...', 'color' => 'gray'],
        ];

        if ($this->readyToLoad) {
            $stats = Cache::remember("user_stats_{$this->user->id}", 3600, fn () => $this->stats);
            $posts = $this->recentActivity;
        }

        return view('livewire.profile', [
            'stats' => $stats,
            'posts' => $posts,
            'contributions' => $this->readyToLoad ? $this->contributions : [],
        ]);
    }
}
