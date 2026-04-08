<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Reaction;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
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

    public function vote($postId, $direction, \App\Actions\Reactions\ToggleReactionAction $toggleReaction)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $post = Post::findOrFail($postId);
        $type = $direction === 'up' ? 'mindblown' : 'optimisable';

        $toggleReaction->execute(Auth::user(), $post, $type);
    }

    public function render()
    {
        $userId = auth()->id();
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

        if ($this->sort === 'recent') {
            $query->latest();
        } else {
            // Sort by score
            $query->selectRaw('*, (SELECT COUNT(*) FROM reactions WHERE reactable_id = posts.id AND type = "mindblown") - (SELECT COUNT(*) FROM reactions WHERE reactable_id = posts.id AND type = "optimisable") as score')
                  ->orderBy('score', 'desc');
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
