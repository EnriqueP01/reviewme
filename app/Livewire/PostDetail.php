<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Comments\StorePostCommentAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reviews\StoreFullReviewAction;
use App\Actions\Reviews\StoreReviewAction;
use App\Actions\Suggestions\StoreInlineSuggestionAction;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PostDetail extends Component
{
    use AuthorizesRequests;

    public Post $post;

    // Current selection tracking
    public $activeLine = null;

    public string $commentContent = ''; // For line-based comments

    // Global Comments
    public string $globalCommentContent = '';

    public ?int $replyToId = null;

    // Full Review System
    public bool $isReviewing = false;

    public string $reviewDescription = '';

    public array $reviewFilesData = []; // [snippet_id => ['content' => '', 'description' => '']]

    // Inline Suggestions
    public ?int $suggestingLine = null;

    public string $suggestedContent = '';

    public string $suggestionDescription = '';

    public string $originalContent = '';

    // Settings
    public string $inlineViewMode = 'diff'; // 'diff' or 'edit'

    public $selectedVersion = null;

    public function mount(int $postId): void
    {
        $this->post = Post::with([
            'user',
            'group',
            'snippets.reviews.user',
            'snippets.inlineSuggestions.user',
            'reactions',
            'comments.user',
            'comments.reactions',
            'comments.replies.user',
            'comments.replies.reactions',
            'fullReviews.user',
            'fullReviews.modifiedSnippets',
        ])->findOrFail($postId);

        $this->selectedVersion = (string) ($this->post->snippets->first()?->id);
        $this->inlineViewMode = session("post_{$postId}_view_mode", 'diff');
    }

    public function selectLine(int $line): void
    {
        $this->activeLine = $line;
    }

    public function toggleInlineViewMode(): void
    {
        $this->inlineViewMode = $this->inlineViewMode === 'diff' ? 'edit' : 'diff';
        session()->put("post_{$this->post->id}_view_mode", $this->inlineViewMode);
        $this->dispatch('post-action', type: 'success');
    }

    public function saveComment(StoreReviewAction $storeReview): void
    {
        $this->authorizeAction();

        $this->validate(['commentContent' => 'required|min:3']);

        $storeReview->execute(
            Auth::user(),
            (int) $this->selectedVersion,
            $this->activeLine !== null ? (int) $this->activeLine : null,
            $this->commentContent
        );

        $this->commentContent = '';
        $this->activeLine = null;
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    public function saveGlobalComment(StorePostCommentAction $storeComment): void
    {
        $this->authorizeAction();

        $this->validate(['globalCommentContent' => 'required|min:2']);

        $storeComment->execute(
            Auth::user(),
            $this->post->id,
            $this->globalCommentContent,
            $this->replyToId
        );

        $this->globalCommentContent = '';
        $this->replyToId = null;
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    public function toggleCommentLike(int $commentId, ToggleReactionAction $toggleReaction): void
    {
        $this->authorizeAction();

        $comment = PostComment::findOrFail($commentId);
        $toggleReaction->execute(Auth::user(), $comment, 'like');

        $this->refreshPost();
        $this->dispatch('post-action', type: 'success');
    }

    public function pinComment(int $commentId): void
    {
        $comment = PostComment::findOrFail($commentId);

        if ($this->post->group && Auth::id() !== $this->post->group->owner_id) {
            return;
        }

        $comment->update(['is_pinned' => ! $comment->is_pinned]);
        $this->refreshPost();
        $this->dispatch('post-action', type: 'success');
    }

    public function setInlineSuggestion(int $line, string $original): void
    {
        $this->suggestingLine = $line;
        $this->originalContent = $original;
        $this->suggestedContent = $original;
    }

    public function saveInlineSuggestion(StoreInlineSuggestionAction $storeSuggestion): void
    {
        $this->authorizeAction();

        $this->validate([
            'suggestedContent' => 'required',
            'suggestionDescription' => 'required|min:3',
        ]);

        $storeSuggestion->execute(
            Auth::user(),
            (int) $this->selectedVersion,
            (int) $this->suggestingLine,
            $this->originalContent,
            $this->suggestedContent,
            $this->suggestionDescription
        );

        $this->suggestingLine = null;
        $this->suggestionDescription = '';
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    public function toggleReviewMode(): void
    {
        $this->isReviewing = ! $this->isReviewing;

        if ($this->isReviewing) {
            foreach ($this->post->snippets as $snippet) {
                $this->reviewFilesData[$snippet->id] = [
                    'snippet_id' => $snippet->id,
                    'name' => $snippet->name,
                    'content' => $snippet->content,
                    'description' => '',
                    'modified' => false,
                ];
            }
        }
    }

    public function saveFullReview(StoreFullReviewAction $storeFullReview): void
    {
        $this->authorizeAction();
        $this->validate(['reviewDescription' => 'required|min:10']);

        $modifiedFiles = array_filter($this->reviewFilesData, fn ($f) => $f['modified']);

        if (empty($modifiedFiles)) {
            $this->addError('reviewFilesData', __('Please modify at least one file to create a review.'));
            return;
        }

        $storeFullReview->execute(Auth::user(), $this->post->id, $this->reviewDescription, $modifiedFiles);

        $this->isReviewing = false;
        $this->refreshPost();
        $this->dispatch('post-action', type: 'success');
    }

    public function deleteReview(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);
        $this->authorize('delete', $review);

        $review->delete();
        $this->refreshPost();
        $this->dispatch('post-action', type: 'down');
    }

    public function deletePost(): void
    {
        $this->authorize('delete', $this->post);
        $this->post->delete();

        $this->dispatch('post-action', type: 'down');
        session()->flash('success', __('Post deleted successfully.'));
        $this->redirect(route('dashboard'));
    }

    public function react(string $type, ToggleReactionAction $toggleReaction): void
    {
        $this->authorizeAction();
        $toggleReaction->execute(Auth::user(), $this->post, $type);
        $this->post->load('reactions');
        $this->dispatch('post-action', type: 'sound');
    }

    protected function authorizeAction(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            throw new \Exception('Unauthorized');
        }
    }

    protected function refreshPost(): void
    {
        $this->post->load([
            'snippets.reviews.user',
            'snippets.inlineSuggestions.user',
            'comments.user',
            'comments.reactions',
            'comments.replies.user',
            'comments.replies.reactions',
            'fullReviews.user',
            'fullReviews.modifiedSnippets',
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $snippet = $this->post->snippets()->where('id', $this->selectedVersion)->first();

        return view('livewire.post-detail', [
            'currentSnippet' => $snippet,
        ]);
    }
}
