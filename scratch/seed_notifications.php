<?php

use App\Models\User;
use App\Models\KarmaTransaction;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

// On récupère le premier utilisateur (probablement vous)
$user = User::first();

if (!$user) {
    echo "Pas d'utilisateur trouvé.";
    exit;
}

echo "Génération pour l'utilisateur : " . $user->name . " (@" . $user->handle . ")\n";

// 1. Génération de Karma Transactions pour aujourd'hui
echo "Création de transactions de Karma...\n";
KarmaTransaction::create([
    'user_id' => $user->id,
    'points' => 15,
    'type' => 'reward',
    'description' => 'Bonus architecte du jour',
    'created_at' => now(),
]);

KarmaTransaction::create([
    'user_id' => $user->id,
    'points' => 10,
    'type' => 'vote',
    'description' => 'Review approuvée par un Mentor',
    'created_at' => now(),
]);

// 2. Génération de Notifications
echo "Envoi de notifications de test...\n";

// Notification Type: Reaction
$user->notify(new GeneralNotification(
    'NOUVELLE RÉACTION',
    '<strong>@elisa_vance</strong> a trouvé votre article <u>"Clean Code 2026"</u> <strong>Mindblown</strong> 🤯',
    'reaction',
    '/dashboard'
));

// Notification Type: Comment
$user->notify(new GeneralNotification(
    'NOUVEAU COMMENTAIRE',
    '<strong>@dev_master</strong> a commenté : <em>"Excellente analyse du pattern Action-Domain !"</em>',
    'comment',
    '/dashboard'
));

// Notification Type: Karma
$user->notify(new GeneralNotification(
    'KARMA ASCENSION',
    'Vous avez gagné <strong>+25 Karma</strong> ! Votre expertise en <u>Security</u> a été reconnue.',
    'karma',
    '/profile'
));

// Notification Type: Review Request
$user->notify(new GeneralNotification(
    'REVIEW DEMANDÉE',
    'Le groupe <strong>Alpha Core</strong> sollicite votre expertise sur un nouveau snippet.',
    'review',
    '/groups'
));

echo "Terminé ! Vérifiez votre cloche de notifications.\n";
