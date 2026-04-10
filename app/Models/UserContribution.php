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
    }
}
