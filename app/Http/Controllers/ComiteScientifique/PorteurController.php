<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Mail\PorteurCredentialsMail;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PorteurController extends Controller
{
    public function index()
    {
        $porteurs = User::where('role', 'porteur_projet')
            ->with(['structure', 'projectAssignments'])
            ->latest()
            ->paginate(15);
        $structures = Structure::orderBy('name')->get();
        return view('comite.porteurs.index', compact('porteurs', 'structures'));
    }

    public function create()
    {
        $structures = Structure::withCount('projectAssignments')->orderBy('name')->get();
        return view('comite.porteurs.create', compact('structures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'phone'        => 'nullable|string|max:20',
            'structure_id' => 'required|exists:structures,id',
            'titles'       => 'required|array|min:1|max:5',
            'titles.*'     => 'required|string|max:500',
        ]);

        $structure = Structure::findOrFail($data['structure_id']);
        $titles    = array_values(array_filter($data['titles'], fn($t) => !empty(trim($t))));

        if (!$structure->canAddProjects(count($titles))) {
            $remaining = $structure->getRemainingSlots();
            return back()->withErrors(['titles' => "Cette structure ne peut accueillir que {$remaining} projet(s) supplémentaire(s)."])->withInput();
        }

        $plainPassword = Str::random(10);
        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'] ?? null,
            'role'         => 'porteur_projet',
            'structure_id' => $data['structure_id'],
            'password'     => Hash::make($plainPassword),
        ]);

        foreach ($titles as $title) {
            ProjectAssignment::create([
                'porteur_id'   => $user->id,
                'structure_id' => $data['structure_id'],
                'title'        => $title,
                'status'       => 'pending',
            ]);
        }

        Mail::to($user->email)->send(new PorteurCredentialsMail($user, $plainPassword));

        return redirect()->route('comite.porteurs.index')->with('success', 'Porteur créé et accès envoyé par mail.');
    }

    public function edit(User $porteur)
    {
        $structures = Structure::orderBy('name')->get();
        return view('comite.porteurs.edit', compact('porteur', 'structures'));
    }

    public function update(Request $request, User $porteur)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $porteur->id,
            'phone'        => 'nullable|string|max:20',
            'structure_id' => 'required|exists:structures,id',
        ]);

        $oldStructureId = $porteur->structure_id;
        $porteur->update($data);

        if ((int)$data['structure_id'] !== (int)$oldStructureId) {
            $porteur->projectAssignments()->update(['structure_id' => $data['structure_id']]);
        }

        return redirect()->route('comite.porteurs.index')->with('success', 'Porteur mis à jour.');
    }

    public function destroy(User $porteur)
    {
        $porteur->projectAssignments()->delete();
        $porteur->delete();
        return redirect()->route('comite.porteurs.index')->with('success', 'Porteur supprimé.');
    }

    public function sendCredentials(User $porteur)
    {
        $plainPassword = Str::random(10);
        $porteur->update(['password' => Hash::make($plainPassword)]);
        Mail::to($porteur->email)->send(new PorteurCredentialsMail($porteur, $plainPassword));
        return back()->with('success', "Accès envoyé à {$porteur->email}.");
    }
}
