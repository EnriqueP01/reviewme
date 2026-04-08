<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Feed extends Component
{
    use WithPagination;

    public string $sort = 'recent';
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'recent'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $method): void
    {
        $this->sort = $method;
        $this->resetPage();
    }

    public function vote(int $postId, string $direction, \App\Actions\Reactions\ToggleReactionAction $toggleReaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $post = Post::findOrFail($postId);
        $type = $direction === 'up' ? 'mindblown' : 'optimisable';

        $toggleReaction->execute(Auth::user(), $post, $type);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $userId = Auth::id();
        $query = Post::with(['user', 'snippets'])
            ->withCount([
                'reactions as up_count' => function($q) { $q->where('type', 'mindblown'); },
                'reactions as down_count' => function($q) { $q->where('type', 'optimisable'); }
            ])
            ->with(['reactions' => function($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }])
            ->where(function ($query) use ($userId) {
                $query->where('visibility', 'public');
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            });

        // Application du filtre de recherche
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($qu) {
                      $qu->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->sort === 'recent') {
            $query->latest();
        } else {
            // Sort by score using the withCount results
            $query->orderByRaw('(up_count - down_count) DESC');
        }

        $posts = $query->paginate(10);

        $perspectives = collect($posts->items())->map(function ($post) {
            $latestSnippet = $post->snippets->first();
            $myReaction = $post->reactions->first()?->type;
            
            return [
                'id' => $post->id,
                'author' => $post->user->name ?? 'Curator anonyme',
                'points' => $post->up_count - $post->down_count,
                'time_ago' => $post->created_at->diffForHumans(),
                'title' => $post->title,
                'snippet' => $latestSnippet->code_content ?? '// No code available',
                'language' => $latestSnippet->language ?? 'javascript',
                'type' => $post->lens ?? 'elegant',
                'my_vote' => $myReaction === 'mindblown' ? 'up' : ($myReaction === 'optimisable' ? 'down' : null),
            ];
        });

        return view('livewire.feed', [
            'perspectives' => $perspectives,
            'posts' => $posts
        ]);
    }
}
