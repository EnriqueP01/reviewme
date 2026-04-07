<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'title', 'description', 'visibility'];

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
        return $this->hasMany(Snippet::class)->orderBy('version_number', 'desc');
    }

    public function latestSnippet(): BelongsTo
    {
        // On récupère le snippet avec le numéro de version le plus élevé
        return $this->belongsTo(Snippet::class, 'id', 'post_id')
                    ->latest('version_number');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
