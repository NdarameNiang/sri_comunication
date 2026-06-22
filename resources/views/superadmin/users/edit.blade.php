@extends('layouts.app')
@section('title', 'Modifier ' . $user->name)
@section('page-title', 'Modifier l\'utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="section-title text-base">{{ $user->name }}</h3>
                <p class="text-xs text-gray-500">{{ $user->email }}</p>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input @error('name') border-red-400 @enderror" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input @error('email') border-red-400 @enderror" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nouveau mot de passe <span class="text-gray-400 text-xs">(laisser vide)</span></label>
                        <input type="password" name="password" class="form-input">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Rôle <span class="text-red-500">*</span></label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Structure</label>
                        <select name="structure_id" class="form-select">
                            <option value="">-- Aucune --</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}" {{ old('structure_id', $user->structure_id) == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->acronym }} – {{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="w-4 h-4 rounded">
                    <label for="is_active" class="text-sm text-gray-700">Compte actif</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
