<?php

namespace App\Http\Controllers\PorteurProjet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user        = Auth::user();
        $tab         = $request->get('tab', 'tous');
        $assignments = $user->projectAssignments()->with('project')->get();

        $grouped = [
            'tous'      => $assignments,
            'pending'   => $assignments->filter(fn($a) => ! $a->project),
            'draft'     => $assignments->filter(fn($a) => $a->project?->status === 'draft'),
            'submitted' => $assignments->filter(fn($a) => $a->project?->status === 'submitted'),
        ];

        $tabCounts = [
            'tous'      => $grouped['tous']->count(),
            'pending'   => $grouped['pending']->count(),
            'draft'     => $grouped['draft']->count(),
            'submitted' => $grouped['submitted']->count(),
        ];

        $displayed = $grouped[$tab] ?? $grouped['tous'];

        return view('porteur.dashboard', compact('displayed', 'tab', 'tabCounts'));
    }
}
