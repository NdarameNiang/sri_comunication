@extends('layouts.app')
@section('title', 'Nouvelle option')
@section('page-title', 'Ajouter une option')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.form-options.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Groupe <span class="text-red-500">*</span></label>
                <select name="group" required class="input-field">
                    @foreach($groups as $key => $label)
                        <option value="{{ $key }}" {{ old('group') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('group')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Libellé affiché <span class="text-red-500">*</span></label>
                <input type="text" name="label" value="{{ old('label') }}" required class="input-field">
                @error('label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valeur (slug) <span class="text-red-500">*</span></label>
                <input type="text" name="value" value="{{ old('value') }}" required class="input-field font-mono"
                       placeholder="ex: sciences_sante" pattern="[a-z0-9_\-]+">
                <p class="text-xs text-gray-400 mt-1">Minuscules, tirets et underscores uniquement. Doit être unique dans le groupe.</p>
                @error('value')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 99) }}" min="0" class="input-field w-24">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Ajouter</button>
                <a href="{{ route('admin.form-options.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
