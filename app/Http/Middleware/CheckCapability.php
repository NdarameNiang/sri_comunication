<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCapability
{
    /**
     * Gate générique par capacité Spatie, indépendant du rôle légataire de l'utilisateur.
     * Contrairement à CheckRole (liste de noms de rôles en dur), n'importe quel rôle —
     * historique ou créé via le générateur de rôles — passe s'il détient la capacité.
     */
    public function handle(Request $request, Closure $next, string ...$capabilities): Response
    {
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé.');
        }

        if (!Auth::user()->hasAnyPermission($capabilities)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
