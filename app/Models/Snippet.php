<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Snippet extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'version_number', 'code_content', 'language'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
