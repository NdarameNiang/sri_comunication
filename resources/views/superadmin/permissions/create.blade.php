@extends('layouts.app')
@section('title', 'Nouvelle permission')
@section('page-title', 'Créer une permission')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Nouvelle permission</h3>
            <a href="{{ route('superadmin.permissions.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body space-y-5">
            <form method="POST" action="{{ route('superadmin.permissions.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label">Libellé <span class="text-red-500">*</span></label>
                    <input type="text" name="label" value="{{ old('label') }}"
                           class="form-input @error('label') border-red-400 @enderror"
                           placeholder="Ex : Exporter les données" required>
                    @error('label') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Nom technique <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-input font-mono @error('name') border-red-400 @enderror"
                           placeholder="Ex : exports.data" required>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Minuscules, chiffres, points ou tirets</p>
                </div>

                <div>
                    <label class="form-label">Groupe / catégorie <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="group" value="{{ old('group') }}"
                               list="groups-list"
                               class="form-input @error('group') border-red-400 @enderror"
                               placeholder="Ex : Exports" required>
                        <datalist id="groups-list">
                            @foreach($groups as $g)
                            <option value="{{ $g }}">
                            @endforeach
                        </datalist>
                    </div>
                    @error('group') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Choisissez un groupe existant ou créez-en un</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('superadmin.permissions.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
