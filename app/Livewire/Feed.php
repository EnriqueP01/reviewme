<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Reaction;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    public $sort = 'recent';

    public function sortBy($method)
    {
        $this->sort = $method;
        $this->resetPage();
    }

    public function vote($postId, $direction)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $post = Post::findOrFail($postId);
        $type = $direction === 'up' ? 'mindblown' : 'optimisable';

        $existing = Reaction::where([
            'user_id' => auth()->id(),
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ])->first();

        if ($existing && $existing->type === $type) {
            // Unvote if same button clicked
            $existing->delete();
        } else {
            Reaction::updateOrCreate([
                'user_id' => auth()->id(),
                'reactable_id' => $post->id,
                'reactable_type' => Post::class,
            ], [
                'type' => $type
            ]);
        }
    }

    public function render()
    {
        $userId = auth()->id();
        $query = Post::with(['user', 'snippets'])
            ->withCount('reactions')
            ->with(['reactions' => function($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereRaw('1 = 0'); // Don't load anything if guest
                }
            }])
            ->where(function ($query) use ($userId) {
                $query->where('visibility', 'public');
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            });

        if ($this->sort === 'recent') {
            $query->latest();
        } else {
            // Trending = most reactions (already added withCount)
            $query->orderBy('reactions_count', 'desc');
        }

        $posts = $query->paginate(10);

        $perspectives = collect($posts->items())->map(function ($post) {
            $latestSnippet = $post->snippets->first();
            $myReaction = $post->reactions->first()?->type;
            
            return [
                'id' => $post->id,
                'author' => $post->user->name ?? 'Curator anonyme',
                'points' => $post->reactions_count, // Optimized count
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
