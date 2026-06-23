<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use Illuminate\Http\Request;

class EventConfigController extends Controller
{
    public function edit()
    {
        $event = EventConfig::active();
        if (!$event) {
            return back()->with('error', 'Aucun événement actif configuré. Contactez l\'administrateur.');
        }
        return view('comite.submission-period', compact('event'));
    }

    public function update(Request $request)
    {
        $event = EventConfig::active();
        if (!$event) {
            return back()->with('error', 'Aucun événement actif configuré.');
        }

        $data = $request->validate([
            'submission_open_at'  => 'nullable|date',
            'submission_close_at' => 'nullable|date|after_or_equal:submission_open_at',
        ]);

        $event->update($data);

        return redirect()->route('comite.submission-period.edit')
            ->with('success', 'Période de soumission mise à jour.');
    }
}
