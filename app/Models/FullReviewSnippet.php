<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FullReviewSnippet extends Model
{
    use HasFactory;

    protected $fillable = ['full_review_id', 'snippet_id', 'modified_content', 'description'];

    public function fullReview(): BelongsTo
    {
        return $this->belongsTo(FullReview::class);
    }

    public function snippet(): BelongsTo
    {
        return $this->belongsTo(Snippet::class);
    }
}
