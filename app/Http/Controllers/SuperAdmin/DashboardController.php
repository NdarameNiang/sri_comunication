<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Structure;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'porteurs'    => User::where('role', 'porteur_projet')->count(),
            'structures'  => Structure::count(),
            'projects'    => Project::count(),
            'submitted'   => Project::where('status', 'submitted')->count(),
            'selected'    => Project::where('selected', true)->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers'));
    }
}
