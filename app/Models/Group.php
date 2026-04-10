<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property-read \Illuminate\Database\Eloquent\Relations\Pivot $pivot
 * @property-read string $avatar
 */
final class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'owner_id',
    ];

    /**
     * Get the URL to the group's logo.
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo_path
            ? asset('storage/'.$this->logo_path)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=BE85FD&background=1E1B4B';
    }

    /**
     * Alias for logo_url to maintain consistency.
     */
    public function getAvatarAttribute(): string
    {
        return $this->getLogoUrlAttribute();
    }

    /**
     * Boot function to handle automatic slug generation.
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($group) {
            $group->slug = Str::slug($group->name);
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GroupMessage::class)->latest();
    }
}
