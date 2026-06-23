<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé.');
        }

        $user = Auth::user();

        // Vérifie via la colonne role (compatibilité existante) OU via Spatie
        if (!in_array($user->role, $roles) && !$user->hasRole($roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
