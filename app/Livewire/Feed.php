<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Posts\SearchPostsAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

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

    #[NoRender]
    public function vote(int $postId, string $direction, ToggleReactionAction $toggleReaction)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $post = Post::findOrFail($postId);

        if ($direction === 'none') {
            Reaction::where([
                'user_id' => Auth::id(),
                'reactable_id' => $post->id,
                'reactable_type' => $post->getMorphClass(),
            ])->delete();

            return;
        }

        $type = $direction === 'up' ? 'mindblown' : 'optimisable';
        $toggleReaction->execute(Auth::user(), $post, $type);
    }

    #[Layout('layouts.app')]
    public function render(SearchPostsAction $searchPosts)
    {
        $posts = $searchPosts->execute($this->search, $this->sort)
            ->paginate(30);

        return view('livewire.feed', [
            'posts' => $posts,
        ]);
    }
}
