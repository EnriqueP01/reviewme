<?php

declare(strict_types=1);

namespace App\Actions\Feedback;

use App\Models\InlineSuggestion;
use App\Models\User;
use Illuminate\Support\Facades\Log;

final class StoreQuickReviewAction
{
    /**
     * Crée une suggestion contextuelle (Quick Review).
     */
    public function execute(
        User $user,
        int $snippetId,
        int $line,
        int $endLine,
        string $original,
        string $suggested,
        string $description
    ): InlineSuggestion {
        Log::info("Executing StoreQuickReviewAction for user {$user->id} on snippet {$snippetId}");

        $suggestion = InlineSuggestion::create([
            'user_id' => $user->id,
            'snippet_id' => $snippetId,
            'line_number' => $line,
            'end_line_number' => $endLine,
            'original_content' => $original,
            'suggested_content' => $suggested,
            'description' => $description,
        ]);

        Log::info("QuickReview created with ID: {$suggestion->id}");

        return $suggestion;
    }
}
