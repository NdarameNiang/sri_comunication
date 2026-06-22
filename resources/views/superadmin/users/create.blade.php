@extends('layouts.app')
@section('title', 'Créer un utilisateur')
@section('page-title', 'Créer un utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations du compte</h3>
            <a href="{{ route('superadmin.users.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') border-red-400 @enderror" placeholder="+221 7X XXX XX XX">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Rôle <span class="text-red-500">*</span></label>
                        <select name="role" id="role-select" class="form-select" required onchange="handleRoleChange(this.value)">
                            <option value="">-- Sélectionner --</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" id="structure-label">Structure</label>
                        <select name="structure_id" id="structure-select" class="form-select">
                            <option value="">-- Aucune --</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}"
                                    data-acronym="{{ $structure->acronym }}"
                                    {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->acronym }} – {{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <script>
                // L'Organisateur (direction_recherche) est rattaché au RECTORAT (sous-structure)
                const DR_OPTION = [...document.querySelectorAll('#structure-select option')]
                    .find(o => o.dataset.acronym === 'RECTORAT');

                // Rôles qui nécessitent une structure obligatoire
                const ROLES_WITH_REQUIRED_STRUCTURE = ['porteur_projet', 'point_focal', 'direction_recherche'];

                function handleRoleChange(role) {
                    const sel   = document.getElementById('structure-select');
                    const label = document.getElementById('structure-label');

                    if (role === 'direction_recherche' && DR_OPTION) {
                        sel.value    = DR_OPTION.value;
                        sel.disabled = true;
                        label.innerHTML = 'Structure <span class="text-gray-400 text-xs">(définie automatiquement)</span>';
                    } else {
                        sel.disabled = false;
                        const required = ROLES_WITH_REQUIRED_STRUCTURE.includes(role);
                        label.innerHTML = 'Structure' + (required ? ' <span class="text-red-500">*</span>' : '');
                        sel.required = required;
                        if (role !== 'direction_recherche') sel.value = sel.value || '';
                    }
                }

                // Appliquer au chargement si old('role') est présent
                document.addEventListener('DOMContentLoaded', () => {
                    const role = document.getElementById('role-select').value;
                    if (role) handleRoleChange(role);
                });
                </script>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 rounded">
                    <label for="is_active" class="text-sm text-gray-700">Compte actif</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">Créer l'utilisateur</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
