<?php

namespace App\Http\Controllers\DirectionRecherche;

use App\Http\Controllers\Controller;
use App\Mail\PorteurCredentialsMail;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PorteurProjetController extends Controller
{
    public function index()
    {
        $porteurs = User::where('role', 'porteur_projet')
            ->with(['structure', 'projectAssignments'])
            ->latest()
            ->paginate(15);

        return view('direction.porteurs.index', compact('porteurs'));
    }

    public function create()
    {
        $structures = Structure::withCount('projectAssignments')->orderBy('name')->get();
        return view('direction.porteurs.create', compact('structures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'phone'        => 'nullable|string|max:20',
            'password'     => 'required|min:8|confirmed',
            'structure_id' => 'required|exists:structures,id',
            'titles'       => 'required|array|min:1|max:5',
            'titles.*'     => 'required|string|max:500',
        ]);

        $structure = Structure::findOrFail($data['structure_id']);
        $titles = array_filter($data['titles'], fn($t) => !empty(trim($t)));

        if (count($titles) === 0) {
            return back()->withErrors(['titles' => 'Vous devez saisir au moins un titre de projet.'])->withInput();
        }

        if (!$structure->canAddProjects(count($titles))) {
            $remaining = $structure->getRemainingSlots();
            return back()
                ->withErrors(['titles' => "Cette structure ne peut accueillir que {$remaining} projet(s) supplémentaire(s) (max 5)."])
                ->withInput();
        }

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'] ?? null,
            'password'     => bcrypt($data['password']),
            'role'         => 'porteur_projet',
            'structure_id' => $data['structure_id'],
            'is_active'    => true,
        ]);

        foreach ($titles as $title) {
            ProjectAssignment::create([
                'porteur_id'   => $user->id,
                'structure_id' => $data['structure_id'],
                'title'        => trim($title),
                'status'       => 'pending',
            ]);
        }

        return redirect()->route('direction.porteurs.index')
            ->with('success', "Porteur de projet créé avec succès. Il peut se connecter avec : {$data['email']}");
    }

    public function show(User $porteur)
    {
        $porteur->load(['structure', 'projectAssignments.project']);
        return view('direction.porteurs.show', compact('porteur'));
    }

    public function edit(User $porteur)
    {
        $structures = Structure::withCount('projectAssignments')->orderBy('name')->get();
        $porteur->load(['structure', 'projectAssignments']);
        return view('direction.porteurs.edit', compact('porteur', 'structures'));
    }

    public function update(Request $request, User $porteur)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($porteur->id)],
            'phone'        => 'nullable|string|max:20',
            'password'     => 'nullable|min:8|confirmed',
            'structure_id' => 'required|exists:structures,id',
            'is_active'    => 'boolean',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        $oldStructureId = $porteur->structure_id;
        $newStructureId = (int) $data['structure_id'];

        $porteur->update($data);

        // Si la structure a changé, on migre tous les projets assignés
        if ($oldStructureId !== $newStructureId) {
            $porteur->projectAssignments()->update(['structure_id' => $newStructureId]);
        }

        return redirect()->route('direction.porteurs.index')->with('success', 'Porteur mis à jour.');
    }

    public function destroy(User $porteur)
    {
        $porteur->delete();
        return back()->with('success', 'Porteur de projet supprimé.');
    }

    public function sendCredentials(User $porteur)
    {
        // Génère un nouveau mot de passe temporaire
        $plain = Str::password(10, symbols: false);
        $porteur->update(['password' => bcrypt($plain)]);

        try {
            Mail::to($porteur->email)->send(new PorteurCredentialsMail($porteur, $plain));
            return back()->with('success', "Identifiants envoyés à {$porteur->email}.");
        } catch (\Exception $e) {
            // En cas d'erreur mail, on restitue le mot de passe pour que l'admin puisse le noter
            return back()->with('error', "Échec de l'envoi. Mot de passe temporaire généré : {$plain}");
        }
    }
}
