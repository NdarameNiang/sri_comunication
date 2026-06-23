@extends('layouts.app')
@section('title', 'Questionnaire')
@section('page-title', 'Détail Questionnaire')

@section('content')
<div class="max-w-2xl space-y-4">
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500 mb-1">Nom</p><p class="font-medium">{{ trim(($questionnaire->prenom ?? '') . ' ' . ($questionnaire->nom ?? '')) ?: 'Anonyme' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Email</p><p>{{ $questionnaire->email ?? '–' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Institution</p><p>{{ $questionnaire->institution ?? '–' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Recommanderait ?</p>
                <p>{{ $questionnaire->recommanderait ? 'Oui' : 'Non' }}</p>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4 grid grid-cols-4 gap-3 text-center text-sm">
            @foreach(['note_organisation' => 'Organisation', 'note_contenu' => 'Contenu', 'note_logistique' => 'Logistique', 'note_globale' => 'Globale'] as $field => $label)
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
                <p class="text-xl font-bold text-gray-800">{{ $questionnaire->$field ?? '–' }}/5</p>
            </div>
            @endforeach
        </div>

        @if($questionnaire->points_positifs)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-500 mb-1">Points positifs</p>
            <p class="text-sm text-gray-700">{{ $questionnaire->points_positifs }}</p>
        </div>
        @endif
        @if($questionnaire->points_amelioration)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-500 mb-1">Points à améliorer</p>
            <p class="text-sm text-gray-700">{{ $questionnaire->points_amelioration }}</p>
        </div>
        @endif
        @if($questionnaire->suggestions)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-500 mb-1">Suggestions</p>
            <p class="text-sm text-gray-700">{{ $questionnaire->suggestions }}</p>
        </div>
        @endif
    </div>
    <a href="{{ route('secretaire.questionnaires.index') }}" class="btn-secondary text-sm">Retour</a>
</div>
@endsection
