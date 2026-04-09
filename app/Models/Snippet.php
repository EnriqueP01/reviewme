<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Snippet extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'name', 'content', 'language', 'sort_order', 'filename'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function fullReviewSnippets(): HasMany
    {
        return $this->hasMany(FullReviewSnippet::class);
    }

    public function inlineSuggestions(): HasMany
    {
        return $this->hasMany(InlineSuggestion::class)->latest();
    }
}
