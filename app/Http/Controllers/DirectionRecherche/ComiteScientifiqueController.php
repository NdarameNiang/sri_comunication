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
            'email'    => ['required', 'email', 'unique:users', 'regex:/@ucad\.edu\.sn$/i'],
            'phone'    => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'password' => 'required|min:8',
        ], [
            'email.regex' => 'L\'email institutionnel doit être une adresse @ucad.edu.sn.',
            'phone.regex' => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
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
            'email'     => ['required', 'email', Rule::unique('users')->ignore($membre->id), 'regex:/@ucad\.edu\.sn$/i'],
            'phone'     => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'password'  => 'nullable|min:8',
            'is_active' => 'boolean',
        ], [
            'email.regex' => 'L\'email institutionnel doit être une adresse @ucad.edu.sn.',
            'phone.regex' => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
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
