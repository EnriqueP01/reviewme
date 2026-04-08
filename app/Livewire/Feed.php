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
    public function render(\App\Actions\Posts\SearchPostsAction $searchPosts)
    {
        $posts = $searchPosts->execute($this->search, $this->sort)->paginate(10);

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
