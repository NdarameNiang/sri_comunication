<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncPersonnelJob;
use App\Jobs\SyncStudentsJob;
use App\Models\Personnel;
use App\Models\Student;

class StudentSyncController extends Controller
{
    public function index()
    {
        $studentStats = [
            'total'     => Student::count(),
            'last_sync' => Student::max('synced_at'),
        ];

        $personnelStats = [
            'total'     => Personnel::count(),
            'last_sync' => Personnel::max('synced_at'),
        ];

        return view('admin.students.index', compact('studentStats', 'personnelStats'));
    }

    public function sync()
    {
        SyncStudentsJob::dispatch();

        return back()->with('success', 'Synchronisation StudentCenter lancée en tâche de fond — la base compte ~156 000 étudiants côté StudentCenter, cela peut prendre plusieurs dizaines de minutes selon la charge du serveur de file d\'attente.');
    }

    public function syncPersonnel()
    {
        SyncPersonnelJob::dispatch();

        return back()->with('success', 'Synchronisation Personnel (PER/PATS) lancée en tâche de fond.');
    }
}
