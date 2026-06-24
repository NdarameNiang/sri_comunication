<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ImpersonateController extends Controller
{
    public function start(User $user)
    {
        // Le superadmin reste le propriétaire de la session — on stocke juste l'ID du porteur
        session(['impersonate_user_id' => $user->id]);

        return redirect()->route('porteur.dashboard');
    }

    public function stop()
    {
        session()->forget('impersonate_user_id');

        return redirect()->route('superadmin.projects.index')
            ->with('success', 'Retour à votre espace superadmin.');
    }
}
