<?php

namespace App\Http\Controllers\DirectionRecherche;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PointFocalController extends Controller
{
    public function index()
    {
        $pointFocaux = User::where('role', 'point_focal')
            ->with('structures')
            ->latest()
            ->paginate(15);

        return view('direction.point-focaux.index', compact('pointFocaux'));
    }

    public function create()
    {
        $structures = Structure::orderBy('name')->get();
        return view('direction.point-focaux.create', compact('structures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'phone'         => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'password'      => 'required|min:8',
            'structure_ids' => 'required|array|min:1',
            'structure_ids.*'=> 'exists:structures,id',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => bcrypt($data['password']),
            'role'      => 'point_focal',
            'is_active' => true,
        ]);

        $user->structures()->sync($data['structure_ids']);

        return redirect()->route('direction.point-focaux.index')
            ->with('success', 'Observateur créé avec succès.');
    }

    public function edit(User $pointFocal)
    {
        $structures = Structure::orderBy('name')->get();
        $pointFocal->load('structures');
        return view('direction.point-focaux.edit', compact('pointFocal', 'structures'));
    }

    public function update(Request $request, User $pointFocal)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('users')->ignore($pointFocal->id)],
            'phone'         => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'password'      => 'nullable|min:8',
            'structure_ids' => 'required|array|min:1',
            'structure_ids.*'=> 'exists:structures,id',
            'is_active'     => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        $pointFocal->update($data);
        $pointFocal->structures()->sync($data['structure_ids']);

        return redirect()->route('direction.point-focaux.index')->with('success', 'Observateur mis à jour.');
    }

    public function destroy(User $pointFocal)
    {
        $pointFocal->structures()->detach();
        $pointFocal->delete();
        return back()->with('success', 'Observateur supprimé.');
    }
}
