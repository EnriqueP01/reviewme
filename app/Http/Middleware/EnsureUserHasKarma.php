<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasKarma
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  $permission  La permission requise (ex: 'group.create')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasKarmaPermission($permission)) {
            $required = config('karma.levels');
            $targetLevel = 'unranked';

            // Trouver quel niveau est requis pour cette permission
            foreach ($required as $key => $level) {
                if (in_array($permission, $level['permissions'])) {
                    $targetLevel = $level;
                    break;
                }
            }

            if ($request->expectsJson() || $request->header('X-Livewire')) {
                abort(403, __("Vous devez être au moins ':level' (:points Karma) pour effectuer cette action.", [
                    'level' => $targetLevel['label'],
                    'points' => $targetLevel['min_score'],
                ]));
            }

            return redirect()->back()->with('error', __('Niveau insuffisant. Score requis : :points', [
                'points' => $targetLevel['min_score'],
            ]));
        }

        return $next($request);
    }
}
