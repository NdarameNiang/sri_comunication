<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncStudentsJob;
use App\Models\Student;

class StudentSyncController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => Student::count(),
            'last_sync' => Student::max('synced_at'),
        ];

        return view('admin.students.index', compact('stats'));
    }

    public function sync()
    {
        SyncStudentsJob::dispatch();

        return back()->with('success', 'Synchronisation lancée en tâche de fond — la base compte ~156 000 étudiants côté StudentCenter, cela peut prendre plusieurs dizaines de minutes selon la charge du serveur de file d\'attente.');
    }
}
