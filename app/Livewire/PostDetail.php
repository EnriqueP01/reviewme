<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\Comments\StorePostCommentAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Livewire\Traits\HasVibeNotifications;
use App\Models\FullReview;
use App\Models\InlineSuggestion;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class PostDetail extends Component
{
    use HasVibeNotifications;

    public Post $post;

    public bool $readyToLoad = false;

    public int $postId;

    public $activeSnippetId = null;

    public $activeLine = null;

    // Global Comments
    public string $globalCommentContent = '';

    public string $replyContent = '';

    public ?int $replyToId = null;

    // Full Review
    public bool $isReviewing = false;

    public string $reviewDescription = '';

    public array $reviewFilesData = [];

    public string $reviewCommentContent = '';

    // Quick Review (Inline Suggestion)
    public ?int $suggestingLine = null;

    public ?int $suggestingEndLine = null;

    public string $suggestedContent = '';

    public string $suggestionDescription = '';

    public string $originalContent = '';

    // UI
    public string $inlineViewMode = 'diff';

    public $selectedVersion = null;

    public int $activeReviewIndex = 0;

    protected function rules(): array
    {
        return [
            'reviewDescription' => 'required_if:isReviewing,true|min:3',
            'suggestionDescription' => 'required_if:suggestingLine,!=,null|min:3',
        ];
    }

    public function mount(int $postId): void
    {
        Log::info("Mounting PostDetail for post {$postId}");
        $this->postId = $postId;
        $this->post = Post::findOrFail($postId); // On ne charge que le strict minimum ici

        if (app()->runningUnitTests()) {
            $this->readyToLoad = true;
        }
    }

    public function loadData(): void
    {
        $this->refreshPost();

        $this->selectedVersion = $this->post->snippets->max('version_number') ?: 1;
        $this->activeSnippetId = $this->post->snippets->where('version_number', $this->selectedVersion)->first()?->id;

        $this->readyToLoad = true;
    }

    public function updatedSelectedVersion($value): void
    {
        $this->activeSnippetId = $this->post->snippets->where('version_number', (int) $value)->first()?->id;
    }

    public function toggleInlineViewMode(): void
    {
        $this->inlineViewMode = $this->inlineViewMode === 'diff' ? 'edit' : 'diff';
    }

    public function saveGlobalComment(?int $parentId = null, ?int $fullReviewId = null): void
    {
        $this->vibeAction(function() use ($parentId, $fullReviewId) {
            $this->authorizeAction('post.comment');
            Log::info("Saving global comment. Parent: {$parentId}, Review: {$fullReviewId}");

            $content = $parentId ? $this->replyContent : ($fullReviewId ? $this->reviewCommentContent : $this->globalCommentContent);

            if (empty(trim($content))) {
                return;
            }

            app(StorePostCommentAction::class)->execute(
                user: Auth::user(),
                postId: $this->post->id,
                content: $content,
                parentId: $parentId,
                fullReviewId: $fullReviewId
            );

            $this->reset(['globalCommentContent', 'replyContent', 'reviewCommentContent', 'replyToId']);
            $this->refreshPost();
        }, __('Commentaire publié !'));
    }

    #[Renderless]
    public function vote(int $postId, string $direction, ToggleReactionAction $toggleReaction): void
    {
        $this->vibeAction(function() use ($postId, $direction, $toggleReaction) {
            $this->authorizeAction($direction === 'up' ? 'post.vote_up' : ($direction === 'down' ? 'post.vote_down' : null));
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
        });
    }

    #[Renderless]
    public function toggleCommentLike(int $commentId, ToggleReactionAction $toggleReaction): void
    {
        $this->vibeAction(function() use ($commentId, $toggleReaction) {
            $this->authorizeAction('comment.like');
            $comment = PostComment::findOrFail($commentId);
            $toggleReaction->execute(Auth::user(), $comment, 'clean');
            $this->refreshPost();
        });
    }

    #[Renderless]
    public function voteReview(int $reviewId, string $direction, ToggleReactionAction $toggleReaction): void
    {
        $this->vibeAction(function() use ($reviewId, $direction, $toggleReaction) {
            $this->authorizeAction($direction === 'up' ? 'review.vote_up' : ($direction === 'down' ? 'review.vote_down' : null));
            $review = FullReview::findOrFail($reviewId);

            if ($direction === 'none') {
                Reaction::where([
                    'user_id' => Auth::id(),
                    'reactable_id' => $review->id,
                    'reactable_type' => $review->getMorphClass(),
                ])->delete();

                return;
            }

            $type = $direction === 'up' ? 'up' : 'down';
            $toggleReaction->execute(Auth::user(), $review, $type);
        });
    }

    // --- QUICK REVIEW (INLINE SUGGESTION) ---

    public function setInlineSuggestion(int $snippetId, int $start, int $end, string $original): void
    {
        Log::info("Setting inline suggestion prompt. Snippet: {$snippetId}, Lines: {$start}-{$end}");
        $this->activeSnippetId = $snippetId;
        $this->suggestingLine = $start;
        $this->suggestingEndLine = $end;
        $this->originalContent = $original;
        $this->suggestedContent = $original;
    }

    public function saveInlineSuggestion(): void
    {
        $this->vibeAction(function() {
            $this->authorizeAction('suggestion.inline');

            if ($this->isAuthor()) {
                throw new \Exception(__('You cannot suggest changes on your own post.'));
            }

            Log::info('Attempting to save inline suggestion. User: '.Auth::id());

            $this->validate([
                'suggestionDescription' => 'required|min:3',
                'suggestedContent' => 'required',
            ], [
                'suggestionDescription.required' => __('L\'explication est indispensable.'),
                'suggestionDescription.min' => __('L\'explication est trop courte.'),
                'suggestedContent.required' => __('Le code suggéré ne peut pas être vide.'),
            ]);

            InlineSuggestion::create([
                'user_id' => Auth::id(),
                'snippet_id' => (int) $this->activeSnippetId,
                'line_number' => (int) $this->suggestingLine,
                'end_line_number' => (int) $this->suggestingEndLine,
                'original_content' => (string) $this->originalContent,
                'suggested_content' => (string) $this->suggestedContent,
                'description' => (string) $this->suggestionDescription,
            ]);

            Log::info('Inline suggestion saved successfully.');
        }, __('Suggestion de refactoring enregistrée !'));

        $this->reset(['suggestingLine', 'suggestingEndLine', 'suggestionDescription', 'suggestedContent', 'originalContent']);
        $this->refreshPost();
    }

    // --- FULL REVIEW ---

    public function toggleReviewMode(): void
    {
        $this->isReviewing = ! $this->isReviewing;

        if ($this->isReviewing) {
            $currentFiles = $this->post->snippets()->where('version_number', $this->selectedVersion)->get();
            foreach ($currentFiles as $snippet) {
                $this->reviewFilesData[$snippet->id] = [
                    'content' => $snippet->code_content,
                    'description' => '',
                ];
            }
        }
    }

    public function saveFullReview(): void
    {
        $this->vibeAction(function() {
            $this->authorizeAction('review.publish');

            if ($this->isAuthor()) {
                throw new \Exception(__('You cannot publish reviews on your own post.'));
            }

            Log::info('Attempting to save full review. User: '.Auth::id());

            $this->validate([
                'reviewDescription' => 'required|min:3',
            ], [
                'reviewDescription.required' => __('L\'évaluation globale est requise.'),
            ]);

            DB::transaction(function () {
                $fullReview = FullReview::create([
                    'user_id' => Auth::id(),
                    'post_id' => $this->post->id,
                    'description' => (string) $this->reviewDescription,
                ]);

                foreach ($this->reviewFilesData as $snippetId => $data) {
                    $snippet = $this->post->snippets()->find($snippetId);
                    if ($snippet && trim((string) $data['content']) !== trim((string) $snippet->code_content)) {
                        $fullReview->modifiedSnippets()->create([
                            'snippet_id' => (int) $snippetId,
                            'modified_content' => (string) $data['content'],
                            'description' => (string) ($data['description'] ?? null),
                        ]);
                    }
                }
            });

            Log::info('Full review saved successfully.');
            $this->reset(['isReviewing', 'reviewDescription', 'reviewFilesData']);
            $this->refreshPost();
        }, __('Revue complète publiée avec succès !'));
    }

    public function deleteReview(int $reviewId): void
    {
        $this->vibeAction(function() use ($reviewId) {
            $this->authorizeAction();
            $review = FullReview::findOrFail($reviewId);
            if (Auth::id() === $review->user_id) {
                $review->delete();
                Log::info("Review {$reviewId} deleted by owner.");
                $this->refreshPost();
            } else {
                throw new \Exception(__('Unauthorized action.'));
            }
        }, __('Review deleted successfully.'));
    }

    public function deletePost(): void
    {
        $this->vibeAction(function() {
            $this->authorizeAction();
            if (! $this->isAuthor() && ! Auth::user()->is_admin) {
                throw new \Exception(__('Unauthorized action.'));
            }

            $this->post->delete();
            session()->flash('success', __('Publication supprimée du système.'));
            $this->redirect(route('feed'));
        });
    }

    public function deleteComment(int $commentId): void
    {
        $this->vibeAction(function() use ($commentId) {
            $this->authorizeAction();
            $comment = PostComment::findOrFail($commentId);

            if (Auth::id() !== $comment->user_id && ! $this->isAuthor() && ! Auth::user()->is_admin) {
                throw new \Exception(__('Unauthorized action.'));
            }

            $comment->delete();
            $this->refreshPost();
        }, __('Comment removed.'));
    }

    public function refreshPost(): void
    {
        $query = Post::with([
            'user',
            'group',
            'snippets.inlineSuggestions.user',
            'fullReviews' => function ($query) {
                $query->withCount([
                    'reactions as up_count' => fn ($q) => $q->where('type', 'up'),
                    'reactions as down_count' => fn ($q) => $q->where('type', 'down'),
                ])
                    ->orderBy('score', 'desc')
                    ->with(['user', 'reactions', 'comments.user', 'modifiedSnippets.snippet', 'comments.reactions']);
            },
            'comments.user',
            'comments.reactions',
            'comments.replies.user',
            'comments.replies.reactions',
        ])
            ->withCount([
                'reactions as up_count' => fn ($q) => $q->where('type', 'mindblown'),
                'reactions as down_count' => fn ($q) => $q->where('type', 'optimisable'),
            ]);

        $this->post = $query->findOrFail($this->postId);

        // Rafraîchir les extraits pour la version sélectionnée
        $this->currentSnippets = $this->post->snippets->where('version_number', $this->selectedVersion);
    }

    public $currentSnippets;

    public function render()
    {
        if ($this->readyToLoad) {
            $this->refreshPost();
        }

        $snippets = $this->post->snippets->where('version_number', (int) $this->selectedVersion);

        return view('livewire.post-detail', [
            'currentSnippets' => $snippets,
        ]);
    }

    protected function authorizeAction(?string $permission = null): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            throw new \Exception(__('Vous devez être connecté.'));
        }

        if ($permission && ! Auth::user()->hasKarmaPermission($permission)) {
            throw new \Exception(__('Karma insuffisant pour cette action.'));
        }
    }

    public function isAuthor(): bool
    {
        return Auth::id() === $this->post->user_id;
    }
}
