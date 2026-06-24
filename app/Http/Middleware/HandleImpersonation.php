<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($porteurId = session('impersonate_user_id')) {
            $porteur = User::find($porteurId);
            if ($porteur) {
                // Override l'user en mémoire pour cette requête UNIQUEMENT.
                // Le superadmin reste le propriétaire de la session — aucune migration.
                Auth::guard()->setUser($porteur);
            }
        }

        return $next($request);
    }
}
