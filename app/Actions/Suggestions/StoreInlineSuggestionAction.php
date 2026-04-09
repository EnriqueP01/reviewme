<?php

declare(strict_types=1);

namespace App\Actions\Suggestions;

use App\Models\InlineSuggestion;
use App\Models\User;

final class StoreInlineSuggestionAction
{
    /**
     * Crée une suggestion de modification sur une ligne spécifique.
     */
    public function execute(
        User $user,
        int $snippetId,
        int $lineNumber,
        string $originalContent,
        string $suggestedContent,
        string $description
    ): InlineSuggestion {
        return InlineSuggestion::create([
            'user_id' => $user->id,
            'snippet_id' => $snippetId,
            'line_number' => $lineNumber,
            'original_content' => $originalContent,
            'suggested_content' => $suggestedContent,
            'description' => $description,
        ]);
    }
}
