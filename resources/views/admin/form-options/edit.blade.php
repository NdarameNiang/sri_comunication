@extends('layouts.app')
@section('title', 'Modifier option')
@section('page-title', 'Modifier l\'option')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.form-options.update', $formOption) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Groupe</label>
                <input type="text" value="{{ \App\Models\FormOption::groups()[$formOption->group] ?? $formOption->group }}" disabled class="input-field bg-gray-50 text-gray-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valeur (slug)</label>
                <input type="text" value="{{ $formOption->value }}" disabled class="input-field bg-gray-50 font-mono text-gray-500">
                <p class="text-xs text-gray-400 mt-1">La valeur ne peut pas être modifiée (risque de perte de données existantes).</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Libellé affiché <span class="text-red-500">*</span></label>
                <input type="text" name="label" value="{{ old('label', $formOption->label) }}" required class="input-field">
                @error('label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $formOption->sort_order) }}" min="0" class="input-field w-24">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.form-options.index', ['group' => $formOption->group]) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
