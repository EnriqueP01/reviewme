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
        $posts = $searchPosts->execute($this->search, $this->sort)
            ->paginate(20);

        return view('livewire.feed', [
            'posts' => $posts
        ]);
    }
}
