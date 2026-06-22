@extends('layouts.app')
@section('title', 'Modifier ' . $membre->name)
@section('page-title', 'Modifier le membre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">{{ $membre->name }}</h3>
            <a href="{{ route('direction.comite.index') }}" class="btn-secondary text-xs">@include('components.icon', ['name' => 'arrow-left']) Retour</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('direction.comite.update', $membre) }}" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $membre->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $membre->phone) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $membre->email) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Nouveau mot de passe <span class="text-gray-400 text-xs">(optionnel)</span></label>
                        <input type="password" name="password" class="form-input">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $membre->is_active ? 'checked' : '' }} class="w-4 h-4 rounded">
                    <label class="text-sm text-gray-700">Compte actif</label>
                </div>
                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                    <a href="{{ route('direction.comite.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
