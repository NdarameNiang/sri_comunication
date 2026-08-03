@extends('layouts.app')
@section('title', 'Classement & sélection')
@section('page-title', 'Classement & sélection')
@section('page-subtitle', $event ? $event->event_name : 'Aucun événement actif')

@section('content')
@php
    $can = fn (string $perm) => auth()->user()->can($perm);
    $statusMeta = [
        'included'     => ['Inclus',              'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'excluded'     => ['Exclu',                'bg-gray-50 text-gray-500 border-gray-200'],
        'tied_pending' => ['Ex-æquo — en attente', 'bg-indigo-50 text-indigo-700 border-indigo-200'],
        'pending'      => ['Non classé',           'bg-gray-50 text-gray-400 border-gray-200'],
    ];
@endphp
<div class="space-y-5">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @foreach([
            ['Soumis', $stats['total'], 'text-gray-800'],
            ['Notés', $stats['scored'], 'text-blue-600'],
            ['Inclus', $stats['included'], 'text-emerald-600'],
            ['Exclus', $stats['excluded'], 'text-gray-500'],
            ['Ex-æquo en attente', $stats['tied_pending'], 'text-indigo-600'],
        ] as [$label, $value, $cls])
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $cls }} mt-1">{{ $value }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <form method="GET" action="{{ route('evaluation.ranking.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Structure</label>
                <select name="structure" class="form-select w-48">
                    <option value="">Toutes</option>
                    @foreach($structures as $s)
                    <option value="{{ $s->id }}" {{ ($filters['structure'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->acronym ?? $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Statut</label>
                <select name="status" class="form-select w-44">
                    <option value="">Tous</option>
                    @foreach($statusMeta as $value => [$label, $cls])
                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Note min</label>
                <input type="number" step="0.01" name="score_min" value="{{ $filters['score_min'] ?? '' }}" class="form-input w-24">
            </div>
            <div>
                <label class="form-label">Note max</label>
                <input type="number" step="0.01" name="score_max" value="{{ $filters['score_max'] ?? '' }}" class="form-input w-24">
            </div>
            <button type="submit" class="btn-secondary text-sm">Filtrer</button>
            @if(array_filter($filters))
            <a href="{{ route('evaluation.ranking.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Réinitialiser</a>
            @endif
            <a href="{{ route('evaluation.ranking.export', $filters) }}" class="btn-secondary text-sm inline-flex items-center gap-1.5 ml-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Exporter (CSV)
            </a>
        </form>
    </div>

    @if($stats['tied_pending'] > 0)
    <div class="flex items-center gap-2.5 p-3 bg-indigo-50 border border-indigo-200 rounded-xl text-sm text-indigo-800">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        <span><strong>{{ $stats['tied_pending'] }} projet(s)</strong> strictement à égalité à la limite du quota — décision manuelle requise (voir tableau ci-dessous).</span>
    </div>
    @endif

    @if($can('evaluation.finalize'))
    {{-- Quota + actions --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-wrap items-end gap-4">
        <form method="POST" action="{{ route('evaluation.ranking.quota') }}" class="flex items-end gap-3">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Quota de sélection</label>
                <input type="number" name="selection_quota" min="1" value="{{ $event?->selection_quota }}" class="form-input w-32" placeholder="Illimité">
            </div>
            <button type="submit" class="btn-secondary text-sm">Enregistrer</button>
        </form>

        <form method="POST" action="{{ route('evaluation.ranking.recalculate') }}">
            @csrf
            <button type="submit" class="btn-primary text-sm inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Recalculer le classement
            </button>
        </form>

        <form method="POST" action="{{ route('evaluation.ranking.apply-selection') }}"
              data-confirm="Appliquer le classement à la sélection ? Les projets inclus seront marqués sélectionnés, les exclus désélectionnés (sauf email déjà envoyé)."
              data-confirm-title="Appliquer à la sélection" data-confirm-type="warning">
            @csrf
            <button type="submit" class="btn-success text-sm inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Appliquer à la sélection
            </button>
        </form>
    </div>
    @endif

    {{-- Tableau --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50/50">
                <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3">Rang</th>
                    <th class="px-5 py-3">Projet</th>
                    <th class="px-5 py-3">Structure</th>
                    <th class="px-5 py-3">Score moyen</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($projects as $project)
                @php [$label, $cls] = $statusMeta[$project->evaluation_status] ?? $statusMeta['pending']; @endphp
                <tr class="{{ $project->isEvaluationTiedPending() ? 'bg-indigo-50/40' : '' }}">
                    <td class="px-5 py-3 font-mono text-gray-500">{{ $project->rank_position ?? '–' }}</td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $project->assignment?->title ?? 'Projet sans titre' }}</p>
                        <p class="text-xs text-gray-400">{{ $project->responsable_nom }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $project->structure?->name ?? '–' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-700">{{ $project->average_score ?? '–' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $cls }} border">{{ $label }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        @if($project->isEvaluationTiedPending() && $can('evaluation.resolveTies'))
                        <div class="flex items-center justify-end gap-1.5">
                            <form method="POST" action="{{ route('evaluation.ranking.resolve-tie', $project) }}">
                                @csrf
                                <input type="hidden" name="decision" value="included">
                                <button type="submit" class="btn-success text-xs py-1 px-2.5">Inclure</button>
                            </form>
                            <form method="POST" action="{{ route('evaluation.ranking.resolve-tie', $project) }}">
                                @csrf
                                <input type="hidden" name="decision" value="excluded">
                                <button type="submit" class="btn-danger text-xs py-1 px-2.5">Exclure</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">Aucun projet soumis.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="text-xs text-gray-400 leading-relaxed max-w-2xl">
        <strong>Critères de départage suggérés en cas d'ex-æquo :</strong> caractère innovant du projet,
        pertinence pour les axes prioritaires de la SRI, maturité/faisabilité, impact potentiel,
        qualité et complétude du dossier soumis.
    </div>
</div>
@endsection
