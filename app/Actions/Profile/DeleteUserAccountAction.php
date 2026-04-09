<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DeleteUserAccountAction
{
    /**
     * Supprime le compte de l'utilisateur et gère la déconnexion et l'invalidation de session.
     */
    public function execute(User $user, Request $request): void
    {
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
