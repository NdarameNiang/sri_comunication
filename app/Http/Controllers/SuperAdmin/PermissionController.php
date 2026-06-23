<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');
        return view('superadmin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $groups = Permission::distinct()->orderBy('group')->pluck('group')->filter()->values();
        return view('superadmin.permissions.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100|unique:permissions,name|regex:/^[a-z0-9.\-_]+$/',
            'label' => 'required|string|max:150',
            'group' => 'required|string|max:100',
        ], [
            'name.regex'  => 'Le nom technique doit être en minuscules, chiffres, points ou tirets.',
            'name.unique' => 'Cette permission existe déjà.',
        ]);

        Permission::create(['name' => $data['name'], 'label' => $data['label'], 'group' => $data['group'], 'guard_name' => 'web']);

        return redirect()->route('superadmin.permissions.index')
            ->with('success', "Permission \"{$data['label']}\" créée.");
    }

    public function edit(Permission $permission)
    {
        $groups = Permission::distinct()->orderBy('group')->pluck('group')->filter()->values();
        return view('superadmin.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'label' => 'required|string|max:150',
            'group' => 'required|string|max:100',
        ]);

        $permission->update($data);

        return redirect()->route('superadmin.permissions.index')
            ->with('success', 'Permission mise à jour.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        return back()->with('success', 'Permission supprimée.');
    }
}
