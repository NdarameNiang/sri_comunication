<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        // Compte réel par colonne users.role (source de vérité)
        $counts = User::selectRaw('`role`, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $roles->each(fn($r) => $r->real_user_count = $counts[$r->name] ?? 0);

        return view('superadmin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');
        return view('superadmin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name|regex:/^[a-z0-9_]+$/',
            'label'       => 'required|string|max:100',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.regex'  => 'Le nom technique doit être en minuscules, chiffres et underscores uniquement.',
            'name.unique' => 'Ce nom de rôle existe déjà.',
        ]);

        $role = Role::create(['name' => $data['name'], 'label' => $data['label'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('superadmin.roles.index')
            ->with('success', "Rôle \"{$data['label']}\" créé avec succès.");
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');
        $role->load('permissions');
        return view('superadmin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['label' => $data['label']]);
        $role->syncPermissions($data['permissions'] ?? []);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('superadmin.roles.index')
            ->with('success', "Rôle \"{$role->label}\" mis à jour.");
    }

    public function destroy(Role $role)
    {
        $systemRoles = ['superadmin', 'direction_recherche', 'comite_scientifique', 'secretaire', 'point_focal', 'porteur_projet'];

        if (in_array($role->name, $systemRoles)) {
            return back()->with('error', 'Les rôles système ne peuvent pas être supprimés.');
        }

        $realCount = User::where('role', $role->name)->count();
        if ($realCount > 0) {
            return back()->with('error', "Impossible de supprimer : {$realCount} utilisateur(s) ont ce rôle.");
        }

        $role->delete();
        return back()->with('success', 'Rôle supprimé.');
    }
}
