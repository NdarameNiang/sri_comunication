@extends('layouts.app')
@section('title', 'Modifier secrétaire')
@section('page-title', 'Modifier – ' . $secretaire->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations du secrétaire</h3>
            <a href="{{ route('direction.secretaires.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('direction.secretaires.update', $secretaire) }}" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $secretaire->name) }}" class="form-input" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $secretaire->phone) }}" class="form-input" placeholder="7X XXX XX XX">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email UCAD <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $secretaire->email) }}" class="form-input" required>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-input" placeholder="Laisser vide pour ne pas changer">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $secretaire->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700">Compte actif</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <a href="{{ route('direction.secretaires.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
