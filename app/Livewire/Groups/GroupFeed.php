<?php

declare(strict_types=1);

namespace App\Livewire\Groups;

use App\Actions\Posts\SearchPostsAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Models\Group;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

final class GroupFeed extends Component
{
    use WithPagination;

    public Group $group;

    public string $sort = 'recent';

    public bool $readyToLoad = false;

    public function loadData(): void
    {
        $this->readyToLoad = true;
    }

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

    public function render(SearchPostsAction $searchPosts)
    {
        $posts = $this->readyToLoad
            ? $searchPosts->execute($this->search, $this->sort, $this->group->id)->paginate(10)
            : new LengthAwarePaginator([], 0, 10);

        return view('livewire.groups.group-feed', [
            'posts' => $posts,
        ]);
    }
}
