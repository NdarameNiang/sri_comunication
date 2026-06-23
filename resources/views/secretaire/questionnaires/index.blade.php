@extends('layouts.app')
@section('title', 'Questionnaires')
@section('page-title', 'Questionnaires')
@section('page-subtitle', $event?->event_name ?? '')

@section('content')
<div class="space-y-4">

    {{-- Moyennes --}}
    @if($moyennes)
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach(['organisation' => 'Organisation', 'contenu' => 'Contenu', 'logistique' => 'Logistique', 'globale' => 'Note globale'] as $key => $label)
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold {{ ($moyennes[$key] ?? 0) >= 4 ? 'text-green-600' : (($moyennes[$key] ?? 0) >= 3 ? 'text-yellow-600' : 'text-red-500') }}">
                {{ $moyennes[$key] ?? '–' }}/5
            </p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Filtre --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email…" class="input-field text-sm">
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            <a href="{{ route('secretaire.questionnaires.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-700">{{ $questionnaires->total() }} réponse(s)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Nom</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Institution</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Org.</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Contenu</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Logistique</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Globale</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questionnaires as $i => $q)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $questionnaires->firstItem() + $i }}</td>
                        <td class="py-3 px-4 font-medium">{{ trim(($q->prenom ?? '') . ' ' . ($q->nom ?? '')) ?: 'Anonyme' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $q->institution ?? '–' }}</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $q->note_organisation }}/5</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $q->note_contenu }}/5</td>
                        <td class="py-3 px-4 text-center font-semibold">{{ $q->note_logistique }}/5</td>
                        <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $q->note_globale }}/5</td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('secretaire.questionnaires.show', $q) }}" class="text-blue-600 text-xs">Voir</a>
                                <form method="POST" action="{{ route('secretaire.questionnaires.destroy', $q) }}"
                                      data-confirm="Supprimer ce questionnaire ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400 text-sm">Aucune réponse pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($questionnaires->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $questionnaires->links() }}</div>
        @endif
    </div>
</div>
@endsection
