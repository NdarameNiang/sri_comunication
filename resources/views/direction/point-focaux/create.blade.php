@extends('layouts.app')
@section('title', 'Nouvel Observateur')
@section('page-title', 'Créer un Observateur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations du Observateur</h3>
            <a href="{{ route('direction.point-focaux.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('direction.point-focaux.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Structures à observer <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-2">Sélectionnez une ou plusieurs structures que cet Observateur pourra surveiller.</p>
                    @error('structure_ids') <p class="form-error mb-2">{{ $message }}</p> @enderror
                    <select name="structure_ids[]" multiple required>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}"
                                {{ in_array($structure->id, old('structure_ids', [])) ? 'selected' : '' }}>
                                {{ $structure->acronym }} – {{ $structure->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary">Créer le Observateur</button>
                    <a href="{{ route('direction.point-focaux.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
