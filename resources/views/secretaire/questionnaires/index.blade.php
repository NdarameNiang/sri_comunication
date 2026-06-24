@extends('layouts.app')
@section('title', 'Questionnaires d\'appréciation')
@section('page-title', 'Questionnaires d\'appréciation')
@section('page-subtitle', $event?->event_name ?? '')

@section('content')
<div class="space-y-4">

    {{-- Stats --}}
    @if($stats)
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500">Réponses reçues</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V2.75a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-700">{{ $stats['recommandent'] }}</p>
                <p class="text-xs text-gray-500">Recommanderaient</p>
            </div>
        </div>
        @foreach(['organisation' => 'Organisation', 'contenu' => 'Contenu'] as $k => $label)
        @php $mv = $moyennes[$k] ?? 0; @endphp
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg {{ $mv >= 4 ? 'bg-emerald-50' : ($mv >= 3 ? 'bg-amber-50' : 'bg-red-50') }} flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $mv >= 4 ? 'text-emerald-500' : ($mv >= 3 ? 'text-amber-400' : 'text-red-400') }}" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold {{ $mv >= 4 ? 'text-emerald-600' : ($mv >= 3 ? 'text-amber-600' : 'text-red-500') }}">{{ $mv }}<span class="text-sm font-normal text-gray-400">/5</span></p>
                <p class="text-xs text-gray-500">Moy. {{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Moyennes détaillées --}}
    @if($moyennes)
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Moyennes par critère</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach(['organisation' => 'Organisation', 'contenu' => 'Contenu scientifique', 'logistique' => 'Logistique', 'globale' => 'Note globale'] as $key => $label)
            @php $val = $moyennes[$key] ?? 0; @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600">{{ $label }}</span>
                    <span class="font-bold {{ $val >= 4 ? 'text-emerald-600' : ($val >= 3 ? 'text-amber-600' : 'text-red-500') }}">{{ $val }}/5</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $val >= 4 ? 'bg-emerald-500' : ($val >= 3 ? 'bg-amber-400' : 'bg-red-400') }}"
                         style="width: {{ ($val / 5) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('secretaire.questionnaires.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email, institution…" class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Note globale min.</label>
                <select name="note_min" class="input-field text-sm">
                    <option value="">Toutes</option>
                    @foreach([5,4,3,2,1] as $n)
                        <option value="{{ $n }}" {{ request('note_min') == $n ? 'selected' : '' }}>≥ {{ $n }}/5</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            <a href="{{ route('secretaire.questionnaires.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
        </form>
    </div>

    {{-- Actions export / import --}}
    <div class="flex flex-wrap gap-3 items-start">
        <a href="{{ route('secretaire.questionnaires.export') }}"
           class="btn-secondary text-sm flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Exporter Excel (CSV)
        </a>
        <button type="button" onclick="document.getElementById('import-q-form').classList.toggle('hidden')"
                class="btn-secondary text-sm flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
            Importer Excel (CSV)
        </button>
    </div>

    {{-- Formulaire import --}}
    <div id="import-q-form" class="hidden bg-purple-50 border border-purple-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-purple-800 mb-1">Importer des réponses depuis un fichier CSV</p>
        <p class="text-xs text-purple-600 mb-3">
            Colonnes requises (séparateur <code class="bg-purple-100 px-1 rounded">;</code>) :
            <strong>Nom ; Prénom ; Email ; Institution ; Note Org. ; Note Contenu ; Note Logistique ; Note Globale ; Recommanderait (Oui/Non) ; Points positifs ; Points à améliorer ; Suggestions</strong>
        </p>
        <form method="POST" action="{{ route('secretaire.questionnaires.import') }}" enctype="multipart/form-data"
              class="flex flex-wrap gap-3 items-center">
            @csrf
            <input type="file" name="fichier" accept=".csv,.txt" required
                   class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 border border-gray-300 rounded-lg p-1">
            <button type="submit" class="btn-primary text-sm">Importer</button>
            <button type="button" onclick="document.getElementById('import-q-form').classList.add('hidden')"
                    class="btn-secondary text-sm">Annuler</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">
                {{ $questionnaires->total() }} réponse(s)
                @if(request()->hasAny(['search','note_min']))
                    <span class="text-xs text-gray-400 ml-1">(filtrés)</span>
                @endif
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Nom complet</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Institution</th>
                        <th class="text-center py-3 px-3 text-xs text-gray-500 font-semibold">Org.</th>
                        <th class="text-center py-3 px-3 text-xs text-gray-500 font-semibold">Contenu</th>
                        <th class="text-center py-3 px-3 text-xs text-gray-500 font-semibold">Logistique</th>
                        <th class="text-center py-3 px-3 text-xs text-gray-500 font-semibold">Globale</th>
                        <th class="text-center py-3 px-3 text-xs text-gray-500 font-semibold">Recommande</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($questionnaires as $i => $q)
                    @php
                        $noteColor = fn($n) => $n >= 4 ? 'text-emerald-600 font-bold' : ($n >= 3 ? 'text-amber-600 font-semibold' : 'text-red-500 font-semibold');
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $questionnaires->firstItem() + $i }}</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-900">
                                {{ trim(($q->prenom ?? '') . ' ' . ($q->nom ?? '')) ?: 'Anonyme' }}
                            </span>
                            @if($q->email)
                                <p class="text-xs text-gray-400">{{ $q->email }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs">{{ $q->institution ?? '–' }}</td>
                        <td class="py-3 px-3 text-center text-sm {{ $noteColor($q->note_organisation) }}">{{ $q->note_organisation }}/5</td>
                        <td class="py-3 px-3 text-center text-sm {{ $noteColor($q->note_contenu) }}">{{ $q->note_contenu }}/5</td>
                        <td class="py-3 px-3 text-center text-sm {{ $noteColor($q->note_logistique) }}">{{ $q->note_logistique }}/5</td>
                        <td class="py-3 px-3 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-7 rounded-lg text-sm font-bold
                                {{ $q->note_globale >= 4 ? 'bg-emerald-100 text-emerald-700' : ($q->note_globale >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                                {{ $q->note_globale }}/5
                            </span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            @if($q->recommanderait)
                                <span class="inline-flex gap-0.5" title="Recommanderait">
                                    @for($s = 0; $s < 5; $s++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/></svg>
                                    @endfor
                                </span>
                            @else
                                <span class="inline-flex gap-0.5" title="Ne recommanderait pas">
                                    @for($s = 0; $s < 5; $s++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-200" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/></svg>
                                    @endfor
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-400 text-xs whitespace-nowrap">
                            {{ $q->created_at->format('d/m/Y') }}<br>{{ $q->created_at->format('H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2 items-center">
                                <a href="{{ route('secretaire.questionnaires.show', $q) }}"
                                   class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Voir
                                </a>
                                <form method="POST" action="{{ route('secretaire.questionnaires.destroy', $q) }}"
                                      data-confirm="Supprimer ce questionnaire ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 text-xs text-red-400 hover:text-red-600 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            <p class="text-gray-400 text-sm">Aucune réponse pour le moment.</p>
                            <p class="text-xs text-gray-300 mt-1">Partagez le lien du questionnaire avec les participants.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($questionnaires->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $questionnaires->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
