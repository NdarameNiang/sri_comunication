@extends('layouts.app')
@section('title', 'Nouveau rôle')
@section('page-title', 'Créer un rôle')
@section('page-subtitle', 'Définissez un nouveau rôle et ses droits d\'accès')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Nouveau rôle</h3>
            <a href="{{ route('superadmin.roles.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body space-y-6">
            <form method="POST" action="{{ route('superadmin.roles.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Libellé du rôle <span class="text-red-500">*</span></label>
                        <input type="text" name="label" value="{{ old('label') }}"
                               class="form-input @error('label') border-red-400 @enderror"
                               placeholder="Ex : Gestionnaire de contenu" required>
                        @error('label') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-1">Nom affiché dans l'interface</p>
                    </div>
                    <div>
                        <label class="form-label">Nom technique <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-input font-mono @error('name') border-red-400 @enderror"
                               placeholder="gestionnaire_contenu" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-1">Minuscules, chiffres, underscores uniquement</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    @include('superadmin.roles._permissions_grid', ['selectedPermissions' => old('permissions', [])])
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('superadmin.roles.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Créer le rôle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
