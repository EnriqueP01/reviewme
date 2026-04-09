<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'title',
        'short_description',
        'description',
        'review_goals',
        'improvement_goals',
        'visibility',
        'goal',
        'context',
        'lens',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function snippets(): HasMany
    {
        return $this->hasMany(Snippet::class)->orderBy('sort_order', 'asc')->orderBy('version_number', 'desc');
    }

    public function latestSnippet(): HasOne
    {
        return $this->hasOne(Snippet::class)->latestOfMany('version_number');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')->orderByDesc('is_pinned')->latest();
    }

    public function fullReviews(): HasMany
    {
        return $this->hasMany(FullReview::class)->withCount(['reactions as up_count' => function ($query) {
            $query->where('type', 'up');
        }])->orderByDesc('up_count')->latest();
    }

    public function inlineSuggestions(): HasManyThrough
    {
        return $this->hasManyThrough(InlineSuggestion::class, Snippet::class);
    }
}
