<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\UserContribution;

/**
 * @property int $reputation_score
 * @property bool $is_admin
 * @property-read array $karma_level
 * @property-read string $avatar
 * @property-read Pivot $pivot
 */
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
        'handle',
        'password',
        'email_verified_at',
        'github_id',
        'avatar',
        'profile_photo_path',
        'reputation_score',
        'is_admin',
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
            'is_admin' => 'boolean',
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

    public function contributions(): HasMany
    {
        return $this->hasMany(UserContribution::class);
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
     * Les permissions sont cumulatives par palier.
     */
    public function hasKarmaPermission(string $permission): bool
    {
        $config = config('karma.levels');
        $allPermissions = [];

        foreach ($config as $level) {
            if ($this->reputation_score >= $level['min_score']) {
                $allPermissions = array_unique(array_merge($allPermissions, $level['permissions']));
            }
        }

        return in_array($permission, $allPermissions);
    }

    /**
     * Recalcule intégralement le score de réputation de l'utilisateur.
     * Basé sur les interactions reçues sur ses contenus.
     */
    public function recalculateReputationScore(): void
    {
        $rewards = config('karma.rewards');
        $score = 0;

        // 1. Karma des Posts (Upvotes/Downvotes)
        $this->posts()->withCount(['reactions as upvotes' => function ($q) {
            $q->where('type', 'mindblown');
        }, 'reactions as downvotes' => function ($q) {
            $q->where('type', 'optimisable');
        }])->get()->each(function ($post) use (&$score, $rewards) {
            $score += $post->upvotes * ($rewards['post_upvote'] ?? 10);
            $score += $post->downvotes * ($rewards['post_downvote'] ?? -2);
        });

        // 2. Karma des Full Reviews
        $this->fullReviews()->withCount(['reactions as upvotes' => function ($q) {
            $q->where('type', 'up');
        }, 'reactions as downvotes' => function ($q) {
            $q->where('type', 'down');
        }])->get()->each(function ($review) use (&$score, $rewards) {
            $score += $review->upvotes * ($rewards['review_upvote'] ?? 15);
            $score += $review->downvotes * ($rewards['post_downvote'] ?? -2);
            $score += $rewards['review_bonus'] ?? 5; // Bonus fixe par review publiée
        });

        $this->update(['reputation_score' => max(0, $score)]);
    }
    public function getKarmaGainedTodayAttribute(): int
    {
        return (int) $this->karmaTransactions()
            ->whereDate('created_at', now()->toDateString())
            ->where('points', '>', 0)
            ->sum('points');
    }

    public function recordContribution(): void
    {
        UserContribution::record($this->id);
    }
}
