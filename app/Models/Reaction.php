<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reactable_id', 'reactable_type', 'type'];

    /**
     * Automatisation du Karma.
     */
    protected static function booted(): void
    {
        static::saved(function (Reaction $reaction) {
            $reaction->updateAuthorKarma();
        });

        static::deleted(function (Reaction $reaction) {
            $reaction->updateAuthorKarma();
        });
    }

    /**
     * Identifie l'auteur du contenu et recalcule son score.
     */
    protected function updateAuthorKarma(): void
    {
        $author = $this->reactable?->user;
        if ($author) {
            $author->recalculateReputationScore();
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}
