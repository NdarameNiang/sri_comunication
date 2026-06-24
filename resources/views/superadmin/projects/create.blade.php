@extends('layouts.app')
@section('title', 'Affecter un projet')
@section('page-title', 'Affecter un projet')
@section('page-subtitle', 'Associer un titre de communication à un porteur')

@section('content')
<div class="max-w-2xl space-y-4">

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Nouvelle affectation</h2>
            <p class="text-xs text-gray-400 mt-0.5">Le porteur recevra une fiche projet à compléter dans son espace.</p>
        </div>
        <div class="px-5 py-5">
            <form method="POST" action="{{ route('superadmin.projects.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label">Porteur de projet <span class="text-red-500">*</span></label>
                    <select name="porteur_id" id="select-porteur" required
                            class="input-field @error('porteur_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Sélectionner un porteur —</option>
                        @foreach($porteurs as $porteur)
                        <option value="{{ $porteur->id }}" {{ old('porteur_id') == $porteur->id ? 'selected' : '' }}>
                            {{ $porteur->name }} — {{ $porteur->email }}
                        </option>
                        @endforeach
                    </select>
                    @error('porteur_id') <p class="form-error">{{ $message }}</p> @enderror
                    @if($porteurs->isEmpty())
                    <p class="text-amber-600 text-xs mt-1">Aucun porteur de projet n'existe encore.</p>
                    @endif
                </div>

                <div>
                    <label class="form-label">Structure de rattachement <span class="text-red-500">*</span></label>
                    <select name="structure_id" id="select-structure" required
                            class="input-field @error('structure_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Sélectionner une structure —</option>
                        @foreach($structures as $structure)
                        <option value="{{ $structure->id }}" {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                            {{ $structure->acronym ? $structure->acronym . ' – ' : '' }}{{ $structure->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Titre du projet / de la communication <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Ex : Modèles prédictifs pour la détection précoce du paludisme"
                           class="input-field @error('title') border-red-400 bg-red-50 @enderror">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" class="btn-primary">Affecter le projet</button>
                    <a href="{{ route('superadmin.projects.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#select-porteur').select2({ placeholder: '— Sélectionner un porteur —', allowClear: true, width: '100%' });
    $('#select-structure').select2({ placeholder: '— Sélectionner une structure —', allowClear: true, width: '100%' });
});
</script>
@endpush
