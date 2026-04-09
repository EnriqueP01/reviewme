<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'github_id',
        'avatar',
        'profile_photo_path',
        'reputation_score',
        'bio',
    ];

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
                    ? asset('storage/'.$this->profile_photo_path)
                    : (($this->attributes['avatar'] ?? null) ?: 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF');
    }

    /**
     * Alias for profile_photo_url to maintain consistency across the app.
     */
    public function getAvatarAttribute(): string
    {
        return $this->getProfilePhotoUrlAttribute();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function fullReviews(): HasMany
    {
        return $this->hasMany(FullReview::class);
    }

    public function inlineSuggestions(): HasMany
    {
        return $this->hasMany(InlineSuggestion::class);
    }

    // --- KARMA & REPUTATION ---

    public function karmaTransactions(): HasMany
    {
        return $this->hasMany(KarmaTransaction::class)->latest();
    }

    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Obtient les détails du niveau actuel de l'utilisateur.
     */
    public function getKarmaLevelAttribute(): array
    {
        $config = config('karma.levels');
        $currentLevel = $config['unranked'];

        foreach ($config as $level) {
            if ($this->reputation_score >= $level['min_score']) {
                $currentLevel = $level;
            }
        }

        return $currentLevel;
    }

    /**
     * Vérifie si l'utilisateur possède une permission spécifique basée sur son Karma.
     */
    public function hasKarmaPermission(string $permission): bool
    {
        return in_array($permission, $this->karma_level['permissions']);
    }
}
