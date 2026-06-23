@extends('layouts.app')
@section('title', 'Modifier la permission')
@section('page-title', 'Modifier la permission')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="section-title text-base">{{ $permission->label ?: $permission->name }}</h3>
                <p class="text-xs text-gray-400 font-mono">{{ $permission->name }}</p>
            </div>
            <a href="{{ route('superadmin.permissions.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.permissions.update', $permission) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="form-label text-gray-400">Nom technique <span class="text-xs font-normal">(non modifiable)</span></label>
                    <input type="text" value="{{ $permission->name }}" class="form-input bg-gray-50 text-gray-400 font-mono" disabled>
                </div>

                <div>
                    <label class="form-label">Libellé <span class="text-red-500">*</span></label>
                    <input type="text" name="label" value="{{ old('label', $permission->label) }}"
                           class="form-input @error('label') border-red-400 @enderror" required>
                    @error('label') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Groupe / catégorie <span class="text-red-500">*</span></label>
                    <input type="text" name="group" value="{{ old('group', $permission->group) }}"
                           list="groups-list"
                           class="form-input @error('group') border-red-400 @enderror" required>
                    <datalist id="groups-list">
                        @foreach($groups as $g)
                        <option value="{{ $g }}">
                        @endforeach
                    </datalist>
                    @error('group') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('superadmin.permissions.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
