<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GenericDashboardController extends Controller
{
    /**
     * Dashboard d'accueil pour tout utilisateur dont le rôle n'est pas l'un des 6 rôles
     * historiques (route/dashboard/menu codés en dur) — la navigation est construite à
     * partir des capacités réellement accordées, via config/capabilities.php.
     */
    public function index()
    {
        $navItems = $this->navItems();
        return view('generic.dashboard', compact('navItems'));
    }

    public static function navItems(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        $capabilities = $user->getAllPermissions()->pluck('name');
        $map = config('capabilities', []);

        return collect($map)
            ->filter(fn ($item, $capability) => $capabilities->contains($capability))
            ->map(fn ($item) => $item)
            ->values()
            ->all();
    }
}
