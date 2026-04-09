<?php

declare(strict_types=1);

namespace App\Actions\Suggestions;

use App\Models\InlineSuggestion;
use App\Models\User;

final class StoreInlineSuggestionAction
{
    /**
     * Crée une suggestion de modification sur un bloc de lignes.
     */
    public function execute(
        User $user,
        int $snippetId,
        int $startLine,
        int $endLine,
        string $originalContent,
        string $suggestedContent,
        string $description
    ): InlineSuggestion {
        return InlineSuggestion::create([
            'user_id' => $user->id,
            'snippet_id' => $snippetId,
            'line_number' => $startLine,
            'end_line_number' => $endLine,
            'original_content' => $originalContent,
            'suggested_content' => $suggestedContent,
            'description' => $description,
        ]);
    }
}
