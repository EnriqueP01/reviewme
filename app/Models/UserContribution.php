<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContribution extends Model
{
    protected $fillable = ['user_id', 'date', 'count'];

    /**
     * Enregistre ou incrémente une contribution pour un utilisateur à la date du jour.
     */
    public static function record(int $userId): void
    {
        self::updateOrCreate(
            ['user_id' => $userId, 'date' => now()->toDateString()],
            ['count' => \DB::raw('count + 1')]
        );

        // Invalide les caches pour forcer la mise à jour immédiate sur le profil
        \Illuminate\Support\Facades\Cache::forget("user_activity_heatmap_{$userId}_week");
        \Illuminate\Support\Facades\Cache::forget("user_activity_heatmap_{$userId}_month");
        \Illuminate\Support\Facades\Cache::forget("user_activity_heatmap_{$userId}_year");
        \Illuminate\Support\Facades\Cache::forget("user_stats_{$userId}");
    }
}
