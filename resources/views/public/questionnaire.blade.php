@extends('layouts.public')
@section('title', 'Questionnaire – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Questionnaire d\'appréciation')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-2">Questionnaire d'appréciation</h2>
    <p class="text-sm text-gray-500 mb-6">Votre avis nous aide à améliorer les prochaines éditions. Ce questionnaire est anonyme.</p>

    <form method="POST" action="{{ route('public.questionnaire.store', $event->event_slug) }}" class="space-y-6">
        @csrf

        {{-- Identité (optionnel) --}}
        <fieldset class="border border-gray-200 rounded-xl p-4 space-y-4">
            <legend class="text-sm font-medium text-gray-700 px-2">Informations (facultatif)</legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" class="input-field">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Institution</label>
                <input type="text" name="institution" value="{{ old('institution') }}" class="input-field">
            </div>
        </fieldset>

        {{-- Notes --}}
        <fieldset class="border border-gray-200 rounded-xl p-4 space-y-4">
            <legend class="text-sm font-medium text-gray-700 px-2">Évaluation (1 = Très insatisfait · 5 = Très satisfait)</legend>
            @foreach(['note_organisation' => 'Organisation générale', 'note_contenu' => 'Qualité du contenu scientifique', 'note_logistique' => 'Logistique & accueil', 'note_globale' => 'Note globale'] as $field => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }} <span class="text-red-500">*</span></label>
                <div class="flex gap-3">
                    @for($n = 1; $n <= 5; $n++)
                    <label class="flex-1 text-center cursor-pointer">
                        <input type="radio" name="{{ $field }}" value="{{ $n }}" {{ old($field) == $n ? 'checked' : '' }} required class="sr-only peer">
                        <span class="block py-2 rounded-lg border border-gray-200 text-sm font-medium transition peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white hover:bg-gray-50">{{ $n }}</span>
                    </label>
                    @endfor
                </div>
                @error($field)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
        </fieldset>

        {{-- Questions ouvertes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Points positifs</label>
            <textarea name="points_positifs" rows="3" class="input-field resize-none" placeholder="Ce que vous avez apprécié…">{{ old('points_positifs') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Points à améliorer</label>
            <textarea name="points_amelioration" rows="3" class="input-field resize-none" placeholder="Ce qui pourrait être amélioré…">{{ old('points_amelioration') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Suggestions</label>
            <textarea name="suggestions" rows="2" class="input-field resize-none" placeholder="Suggestions pour les prochaines éditions…">{{ old('suggestions') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recommanderiez-vous cet événement ?</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recommanderait" value="1" {{ old('recommanderait') === '1' ? 'checked' : '' }}>
                    <span class="text-sm">Oui</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recommanderait" value="0" {{ old('recommanderait') === '0' ? 'checked' : '' }}>
                    <span class="text-sm">Non</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full btn-primary py-3 text-base font-medium">
            Soumettre mon questionnaire
        </button>
    </form>
</div>
@endsection
