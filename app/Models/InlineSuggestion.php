<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InlineSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'snippet_id',
        'line_number',
        'original_content',
        'suggested_content',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function snippet(): BelongsTo
    {
        return $this->belongsTo(Snippet::class);
    }
}
