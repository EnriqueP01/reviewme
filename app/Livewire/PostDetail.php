<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Comments\StorePostCommentAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reviews\StoreFullReviewAction;
use App\Actions\Reviews\StoreReviewAction;
use App\Actions\Suggestions\StoreInlineSuggestionAction;
use App\Models\FullReview;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PostDetail extends Component
{
    use AuthorizesRequests;

    public Post $post;
    public $activeSnippetId = null;

    // Current selection tracking
    public $activeLine = null;

    public string $commentContent = ''; // For line-based comments

    // Global Comments
    public string $globalCommentContent = '';
    public string $replyContent = ''; // For replies

    public ?int $replyToId = null;

    // Full Review System
    public bool $isReviewing = false;

    public string $reviewDescription = '';

    public array $reviewFilesData = []; // [snippet_id => ['content' => '', 'description' => '']]

    // Inline Suggestions
    public ?int $suggestingLine = null;

    public ?int $suggestingEndLine = null;

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
            'fullReviews.modifiedSnippets.snippet',
            'fullReviews.reactions',
        ])->findOrFail($postId);

        $this->selectedVersion = $this->post->snippets->max('version_number') ?: 1;
        $this->activeSnippetId = $this->post->snippets->where('version_number', $this->selectedVersion)->first()?->id;
        $this->inlineViewMode = session("post_{$postId}_view_mode", 'diff');
    }

    public function updatedSelectedVersion($value): void
    {
        $this->activeSnippetId = $this->post->snippets->where('version_number', (int)$value)->first()?->id;
    }

    public function selectLine(int $snippetId, int $line): void
    {
        $this->activeLine = $snippetId.'-'.$line;
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

        [$snippetId, $line] = explode('-', $this->activeLine);

        $storeReview->execute(
            Auth::user(),
            (int) $snippetId,
            $line !== '' ? (int) $line : null,
            $this->commentContent
        );

        $this->commentContent = '';
        $this->activeLine = null;
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    public function saveGlobalComment(StorePostCommentAction $storeComment, ?int $parentId = null): void
    {
        $this->authorizeAction();

        if ($parentId) {
            $this->validate(['replyContent' => 'required|min:2']);
            $content = $this->replyContent;
        } else {
            $this->validate(['globalCommentContent' => 'required|min:2']);
            $content = $this->globalCommentContent;
        }

        $storeComment->execute(
            Auth::user(),
            $this->post->id,
            $content,
            $parentId ?? $this->replyToId
        );

        $this->globalCommentContent = '';
        $this->replyContent = '';
        $this->replyToId = null;
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    /**
     * Gère les likes sur les commentaires et réponses (YouTube style).
     */
    public function toggleCommentLike(int $commentId, ToggleReactionAction $toggleReaction): void
    {
        $this->authorizeAction();

        $comment = PostComment::findOrFail($commentId);
        $toggleReaction->execute(Auth::user(), $comment, 'like');

        $this->refreshPost();
        $this->dispatch('post-action', type: 'success');
    }

    /**
     * Gère les Up/Down votes sur les Reviewscomplètes.
     */
    public function voteReview(int $reviewId, string $type, ToggleReactionAction $toggleReaction): void
    {
        $this->authorizeAction();

        $review = FullReview::findOrFail($reviewId);
        $toggleReaction->execute(Auth::user(), $review, $type); // 'up' or 'down'

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

    public function setInlineSuggestion(int $snippetId, int $start, int $end, string $original): void
    {
        $this->activeSnippetId = $snippetId;
        $this->suggestingLine = $start;
        $this->suggestingEndLine = $end;
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

        $targetSnippet = $this->post->snippets()->find($this->activeSnippetId);

        if (!$targetSnippet) {
            $this->addError('suggestingLine', __('No target file found.'));
            return;
        }

        $storeSuggestion->execute(
            Auth::user(),
            $targetSnippet->id,
            (int) $this->suggestingLine,
            (int) $this->suggestingEndLine,
            $this->originalContent,
            $this->suggestedContent,
            $this->suggestionDescription
        );

        $this->suggestingLine = null;
        $this->suggestingEndLine = null;
        $this->suggestionDescription = '';
        $this->refreshPost();

        $this->dispatch('post-action', type: 'success');
    }

    public function toggleReviewMode(): void
    {
        $this->isReviewing = ! $this->isReviewing;

        if ($this->isReviewing) {
            $currentFiles = $this->post->snippets()
                ->where('version_number', $this->selectedVersion)
                ->get();

            foreach ($currentFiles as $snippet) {
                $this->reviewFilesData[$snippet->id] = [
                    'snippet_id' => $snippet->id,
                    'name' => $snippet->filename ?: 'file',
                    'content' => $snippet->code_content,
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

        $currentFiles = $this->post->snippets()->where('version_number', $this->selectedVersion)->get();
        $modifiedFiles = [];
        
        foreach ($currentFiles as $snippet) {
            $data = $this->reviewFilesData[$snippet->id] ?? null;
            if ($data && trim($data['content']) !== trim($snippet->code_content)) {
                $data['modified'] = true;
                $modifiedFiles[] = $data;
            }
        }

        if (empty($modifiedFiles)) {
            $this->addError('reviewDescription', __('Please modify at least one file to create a review.'));
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
            'fullReviews.reactions',
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $snippets = $this->post->snippets()
            ->where('version_number', $this->selectedVersion)
            ->orderBy('sort_order')
            ->get();

        $previewDiffs = [];
        if ($this->isReviewing) {
            foreach ($snippets as $snippet) {
                $newData = $this->reviewFilesData[$snippet->id] ?? null;
                if ($newData) {
                    $previewDiffs[$snippet->id] = \App\Helpers\TextDiffHelper::diffLines(
                        $snippet->code_content,
                        $newData['content']
                    );
                }
            }
        }

        return view('livewire.post-detail', [
            'currentSnippets' => $snippets,
            'previewDiffs' => $previewDiffs,
        ]);
    }
}
