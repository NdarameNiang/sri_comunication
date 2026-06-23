@extends('layouts.app')
@section('title', 'Modifier ' . ($role->label ?: $role->name))
@section('page-title', 'Modifier le rôle')
@section('page-subtitle', $role->label ?: $role->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="section-title text-base">{{ $role->label ?: $role->name }}</h3>
                <p class="text-xs text-gray-400 font-mono">{{ $role->name }}</p>
            </div>
            <a href="{{ route('superadmin.roles.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.roles.update', $role) }}" class="space-y-6">
                @csrf @method('PUT')

                <div>
                    <label class="form-label">Libellé du rôle <span class="text-red-500">*</span></label>
                    <input type="text" name="label" value="{{ old('label', $role->label) }}"
                           class="form-input @error('label') border-red-400 @enderror"
                           placeholder="Nom affiché dans l'interface" required>
                    @error('label') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-gray-400">Nom technique <span class="text-xs font-normal">(non modifiable)</span></label>
                    <input type="text" value="{{ $role->name }}" class="form-input bg-gray-50 text-gray-400 font-mono" disabled>
                </div>

                @php $selectedPermissions = $role->permissions->pluck('name')->toArray(); @endphp
                @include('superadmin.roles._permissions_grid', ['selectedPermissions' => old('permissions', $selectedPermissions)])

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('superadmin.roles.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
