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
        $search      = $request->get('search', '');
        $assignments = $user->projectAssignments()->with('project', 'structure')->get();

        $grouped = [
            'tous'      => $assignments,
            'pending'   => $assignments->filter(fn($a) => ! $a->project),
            'draft'     => $assignments->filter(fn($a) => $a->project?->status === 'draft'),
            'submitted' => $assignments->filter(fn($a) => $a->project?->status === 'submitted'),
        ];

        $tabCounts = collect($grouped)->map->count()->all();

        $displayed = $grouped[$tab] ?? $grouped['tous'];

        if ($search) {
            $displayed = $displayed->filter(fn($a) =>
                str_contains(mb_strtolower($a->title), mb_strtolower($search))
            );
        }

        return view('porteur.dashboard', compact('displayed', 'tab', 'tabCounts', 'search'));
    }
}
