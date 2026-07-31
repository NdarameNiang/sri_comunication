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
        return redirect()->route('superadmin.permissions.index')
            ->with('error', 'Les capacités forment un catalogue fini (voir database/seeders/RolePermissionSeeder.php) — elles ne se créent plus depuis cet écran, seuls leur libellé et leur regroupement restent modifiables.');
    }

    public function store(Request $request)
    {
        return redirect()->route('superadmin.permissions.index');
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
