<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reviews\StoreReviewAction;
use App\Models\Post;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class VibeDetail extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public $activeLine = null;

    public string $commentContent = '';

    public $selectedVersion = null;

    public function mount(int $postId): void
    {
        $this->post = Post::with(['user', 'snippets.reviews.user', 'reactions'])->findOrFail($postId);
        $this->selectedVersion = $this->post->snippets->first()?->id;
    }

    public function selectLine(int $line): void
    {
        $this->activeLine = $line;
    }

    public function saveComment(StoreReviewAction $storeReview): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $this->validate([
            'commentContent' => 'required|min:3',
        ]);

        $storeReview->execute(
            Auth::user(),
            (int) $this->selectedVersion,
            $this->activeLine !== null ? (int) $this->activeLine : null,
            $this->commentContent
        );

        $this->commentContent = '';
        $this->activeLine = null;
        $this->post->load('snippets.reviews.user');

        $this->dispatch('vibe-action', type: 'success');
        session()->flash('message', __('Review added successfully!'));
    }

    public function deleteReview(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);

        $this->authorize('delete', $review);

        $review->delete();
        $this->post->load('snippets.reviews.user');
        $this->dispatch('vibe-action', type: 'down');
    }

    public function react(string $type, ToggleReactionAction $toggleReaction): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $toggleReaction->execute(Auth::user(), $this->post, $type);
        $this->post->load('reactions');

        $sound = ($type === 'mindblown') ? 'up' : 'down';
        $this->dispatch('vibe-action', type: $sound);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $snippet = $this->post->snippets()->where('id', $this->selectedVersion)->first();

        return view('livewire.vibe-detail', [
            'currentSnippet' => $snippet,
        ]);
    }
}
