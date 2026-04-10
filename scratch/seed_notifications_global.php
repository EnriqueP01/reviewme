<?php

use App\Models\User;
use App\Models\KarmaTransaction;
use App\Notifications\GeneralNotification;

echo "Génération de données de démo pour TOUS les utilisateurs...\n";

User::all()->each(function ($user) {
    echo "Processing: " . $user->name . "\n";
    
    // Karma
    KarmaTransaction::create([
        'user_id' => $user->id,
        'points' => 25,
        'type' => 'reward',
        'description' => 'Demo Karma Bonus',
        'created_at' => now(),
    ]);

    // Notifications
    $user->notify(new GeneralNotification(
        'SYSTÈME ACTIF',
        'Le système de notification est maintenant <strong>100% réel</strong>.',
        'info',
        '/dashboard'
    ));

    $user->notify(new GeneralNotification(
        'KARMA RÉEL',
        'Le compteur en bas du tiroir affiche maintenant votre gain du jour : <strong>+' . 25 . ' XP</strong>',
        'karma',
        '#'
    ));
});

echo "Demo terminée.\n";
