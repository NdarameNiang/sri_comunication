<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Mail\ProjectSelected;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SelectionController extends Controller
{
    public function toggle(Project $project)
    {
        if (!$project->isSubmitted()) {
            return back()->with('error', 'Seuls les projets soumis peuvent être sélectionnés.');
        }

        if ($project->selected && $project->email_sent_at) {
            return back()->with('error', 'Ce projet ne peut plus être désélectionné : l\'email de confirmation a déjà été envoyé au porteur.');
        }

        $project->update([
            'selected'    => !$project->selected,
            'selected_at' => !$project->selected ? now() : null,
            'selected_by' => !$project->selected ? Auth::id() : null,
        ]);

        $msg = $project->selected ? 'Projet sélectionné.' : 'Sélection annulée.';
        return back()->with('success', $msg);
    }

    public function sendEmails(Request $request)
    {
        $selected = Project::where('selected', true)
            ->whereNull('email_sent_at')
            ->with(['porteur', 'structure', 'assignment'])
            ->get();

        if ($selected->isEmpty()) {
            return back()->with('error', 'Aucun projet sélectionné ou les emails ont déjà été envoyés.');
        }

        $sent = 0;
        foreach ($selected as $project) {
            try {
                Mail::to($project->porteur->email)->send(new ProjectSelected($project));
                $project->update(['email_sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                // Continue even if one email fails
            }
        }

        return back()->with('success', "{$sent} email(s) envoyé(s) avec succès aux porteurs de projets sélectionnés.");
    }
}
