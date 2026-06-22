<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('structure')->latest()->paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $structures = Structure::orderBy('name')->get();
        $roles = [
            'superadmin'          => 'Super Administrateur',
            'direction_recherche' => 'Direction de la Recherche',
            'point_focal'         => 'Point Focal',
            'porteur_projet'      => 'Porteur de Projet',
            'comite_scientifique' => 'Comité Scientifique',
        ];
        return view('superadmin.users.create', compact('structures', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'phone'        => 'nullable|string|max:20',
            'password'     => 'required|min:8|confirmed',
            'role'         => 'required|in:superadmin,direction_recherche,point_focal,porteur_projet,comite_scientifique',
            'structure_id' => 'nullable|exists:structures,id',
            'is_active'    => 'boolean',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        User::create($data);

        return redirect()->route('superadmin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $structures = Structure::orderBy('name')->get();
        $roles = [
            'superadmin'          => 'Super Administrateur',
            'direction_recherche' => 'Direction de la Recherche',
            'point_focal'         => 'Point Focal',
            'porteur_projet'      => 'Porteur de Projet',
            'comite_scientifique' => 'Comité Scientifique',
        ];
        return view('superadmin.users.edit', compact('user', 'structures', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'        => 'nullable|string|max:20',
            'password'     => 'nullable|min:8|confirmed',
            'role'         => 'required|in:superadmin,direction_recherche,point_focal,porteur_projet,comite_scientifique',
            'structure_id' => 'nullable|exists:structures,id',
            'is_active'    => 'boolean',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('superadmin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte {$status} avec succès.");
    }
}
