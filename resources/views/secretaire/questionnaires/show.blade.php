@extends('layouts.app')
@section('title', 'Questionnaire – ' . trim(($questionnaire->prenom ?? '') . ' ' . ($questionnaire->nom ?? '')))
@section('page-title', 'Détail questionnaire')
@section('page-subtitle', trim(($questionnaire->prenom ?? '') . ' ' . ($questionnaire->nom ?? '')) ?: 'Anonyme')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    {{-- En-tête participant --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center shrink-0">
            <span class="text-xl font-bold text-purple-600">
                {{ strtoupper(substr($questionnaire->prenom ?? $questionnaire->nom ?? '?', 0, 1)) }}
            </span>
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-gray-900">
                {{ trim(($questionnaire->prenom ?? '') . ' ' . ($questionnaire->nom ?? '')) ?: 'Anonyme' }}
            </h2>
            <p class="text-sm text-gray-400">{{ $questionnaire->institution ?? 'Institution non renseignée' }}</p>
            @if($questionnaire->email)
                <p class="text-xs text-gray-400 mt-0.5">{{ $questionnaire->email }}</p>
            @endif
        </div>
        @if($questionnaire->recommanderait)
            <div class="text-center">
                <div class="flex gap-0.5 justify-center">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/></svg>
                    @endfor
                </div>
                <p class="text-xs text-gray-400 mt-1">Recommande</p>
            </div>
        @endif
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Évaluation</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
            @foreach(['note_organisation' => 'Organisation', 'note_contenu' => 'Contenu', 'note_logistique' => 'Logistique', 'note_globale' => 'Note globale'] as $field => $label)
            @php $n = $questionnaire->$field ?? 0; @endphp
            <div class="py-5 px-4 text-center">
                <p class="text-xs text-gray-400 mb-2">{{ $label }}</p>
                <p class="text-3xl font-extrabold {{ $n >= 4 ? 'text-emerald-600' : ($n >= 3 ? 'text-amber-500' : 'text-red-500') }}">
                    {{ $n }}<span class="text-base font-normal text-gray-300">/5</span>
                </p>
                <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden mx-2">
                    <div class="h-full rounded-full {{ $n >= 4 ? 'bg-emerald-400' : ($n >= 3 ? 'bg-amber-400' : 'bg-red-400') }}"
                         style="width: {{ ($n / 5) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Commentaires qualitatifs --}}
    @if($questionnaire->points_positifs || $questionnaire->points_amelioration || $questionnaire->suggestions)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Commentaires</p>
        </div>
        <div class="divide-y divide-gray-50">
            @if($questionnaire->points_positifs)
            <div class="px-5 py-4 flex gap-3">
                <span class="mt-0.5 w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-1">Points positifs</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $questionnaire->points_positifs }}</p>
                </div>
            </div>
            @endif
            @if($questionnaire->points_amelioration)
            <div class="px-5 py-4 flex gap-3">
                <span class="mt-0.5 w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-1">Points à améliorer</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $questionnaire->points_amelioration }}</p>
                </div>
            </div>
            @endif
            @if($questionnaire->suggestions)
            <div class="px-5 py-4 flex gap-3">
                <span class="mt-0.5 w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>
                <div>
                    <p class="text-xs font-semibold text-gray-400 mb-1">Suggestions</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $questionnaire->suggestions }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Méta --}}
    <div class="text-xs text-gray-400 text-right px-1">
        Soumis le {{ $questionnaire->created_at->format('d/m/Y à H:i') }}
    </div>

    {{-- Actions --}}
    <a href="{{ route('secretaire.questionnaires.index') }}" class="btn-secondary text-sm inline-flex items-center gap-1.5">
        ← Retour à la liste
    </a>

</div>
@endsection
