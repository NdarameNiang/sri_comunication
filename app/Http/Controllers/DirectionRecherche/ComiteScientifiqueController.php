<?php

namespace App\Http\Controllers\DirectionRecherche;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComiteScientifiqueController extends Controller
{
    public function index()
    {
        $membres = User::where('role', 'comite_scientifique')->latest()->paginate(15);
        return view('direction.comite.index', compact('membres'));
    }

    public function create()
    {
        return view('direction.comite.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => bcrypt($data['password']),
            'role'      => 'comite_scientifique',
            'is_active' => true,
        ]);

        return redirect()->route('direction.comite.index')
            ->with('success', 'Membre du comité créé avec succès.');
    }

    public function edit(User $membre)
    {
        return view('direction.comite.edit', compact('membre'));
    }

    public function update(Request $request, User $membre)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($membre->id)],
            'phone'     => 'nullable|string|max:20',
            'password'  => 'nullable|min:8',
            'is_active' => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        $membre->update($data);

        return redirect()->route('direction.comite.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(User $membre)
    {
        $membre->delete();
        return back()->with('success', 'Membre du comité supprimé.');
    }
}
