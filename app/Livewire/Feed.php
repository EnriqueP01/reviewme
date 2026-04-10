<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Posts\SearchPostsAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Livewire\Traits\HasVibeNotifications;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use HasVibeNotifications, WithPagination;

    public string $sort = 'recent';

    public string $search = '';

    public bool $readyToLoad = false;

    public function mount(): void
    {
        if (app()->runningUnitTests()) {
            $this->readyToLoad = true;
        }
    }

    public function loadData(): void
    {
        $this->readyToLoad = true;
    }

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

    #[Renderless]
    public function vote(int $postId, string $direction, ToggleReactionAction $toggleReaction)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Karma Check
        $permission = $direction === 'up' ? 'post.vote_up' : ($direction === 'down' ? 'post.vote_down' : null);
        if ($permission && ! Auth::user()->hasKarmaPermission($permission)) {
            $this->notifyError(__('Karma insuffisant pour cette action.'));

            return;
        }

        try {
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
        } catch (\Exception $e) {
            $this->notifyError($e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render(SearchPostsAction $searchPosts)
    {
        $posts = $this->readyToLoad
            ? $searchPosts->execute($this->search, $this->sort)->paginate(15)
            : new LengthAwarePaginator([], 0, 15);

        return view('livewire.feed', [
            'posts' => $posts,
        ]);
    }
}
