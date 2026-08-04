@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Évaluation et sélection des projets soumis')

@section('content')
<div class="space-y-6">

    {{-- ── Bannière bienvenue ──────────────────────────────────────── --}}
    <div class="dash-banner">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Comité scientifique</p>
                <h2 class="text-white text-2xl font-bold leading-tight">Bonjour, {{ auth()->user()->name }}</h2>
                <p class="text-blue-200 text-sm mt-1">{{ now()->isoFormat('dddd D MMMM YYYY') }}</p>
            </div>
            @if($evaluationEnabled)
            <div class="flex gap-3 shrink-0">
                @can('evaluation.score')
                <a href="{{ route('deliberation.scoring.index') }}"
                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    Notation
                </a>
                @endcan
                @can('evaluation.viewRanking')
                <a href="{{ route('evaluation.ranking.index') }}"
                   class="inline-flex items-center gap-2 bg-white text-blue-700 hover:bg-blue-50 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    Délibération
                </a>
                @endcan
            </div>
            @endif
        </div>
        {{-- Déco --}}
        <div class="absolute right-0 top-0 h-full w-1/3 opacity-10 pointer-events-none"
             style="background:radial-gradient(circle at 80% 50%, white 0%, transparent 70%)"></div>
    </div>

    {{-- ── KPIs ────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon bg-blue-100 shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Projets soumis</p>
            </div>
        </div>

        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon bg-emerald-100 shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $stats['selected'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Sélectionnés</p>
            </div>
        </div>

        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon bg-indigo-100 shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $stats['sent'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Emails envoyés</p>
            </div>
        </div>

        @if($evaluationEnabled)
        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon bg-violet-100 shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">
                    {{ $evaluationStats['scored'] }}<span class="text-lg text-gray-400 font-medium">/{{ $evaluationStats['total'] }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">Projets notés{{ $evaluationStats['isGlobal'] ? '' : ' par vous' }}</p>
            </div>
        </div>
        @else
        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon bg-gray-100 shrink-0">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $structures->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Établissements</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Barre d'actions : envoi email + export ─────────────────── --}}
    <div class="flex flex-wrap gap-3 items-center">
        @if($stats['selected'] > 0)
        <div class="flex-1 min-w-0 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
            <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
            <p class="text-sm text-emerald-800 flex-1 min-w-0">
                <span class="font-semibold">{{ $stats['selected'] }} projet(s) sélectionné(s)</span>
                @if($stats['sent'] > 0) · <span class="text-emerald-600">{{ $stats['sent'] }} email(s) déjà envoyé(s)</span>@endif
            </p>
            <form method="POST" action="{{ route('comite.send-emails') }}"
                  data-confirm="Envoyer les emails officiels aux porteurs sélectionnés ?"
                  data-confirm-title="Envoi des notifications"
                  data-confirm-type="info"
                  class="shrink-0">
                @csrf
                <button type="submit" class="btn-success text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Envoyer les notifications
                </button>
            </form>
        </div>
        @endif

        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs text-gray-400 font-medium">Export CSV :</span>
            <a href="{{ route('comite.projects.export') }}"
               class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Tous
            </a>
            @foreach($structures->where('submitted_count', '>', 0) as $s)
            <a href="{{ route('comite.projects.export', ['structure' => $s->id]) }}"
               class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium transition-colors">
                {{ $s->acronym ?? $s->name }}
                <span class="text-gray-400">({{ $s->submitted_count }})</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── Résumé par structure ────────────────────────────────────── --}}
    @if($structures->where('submitted_count', '>', 0)->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        @foreach($structures as $structure)
        @if($structure->submitted_count > 0)
        @php $pct = $structure->submitted_count > 0 ? round($structure->selected_count / $structure->submitted_count * 100) : 0; @endphp
        <div class="bg-white rounded-xl border border-gray-100 p-4 hover:border-blue-200 transition-colors">
            <div class="flex items-start justify-between mb-3">
                <span class="badge-blue font-semibold">{{ $structure->acronym }}</span>
                @if($structure->selected_count > 0)
                <span class="badge-green">{{ $structure->selected_count }} sel.</span>
                @endif
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $structure->submitted_count }}</p>
            <p class="text-xs text-gray-400 mt-0.5">soumis</p>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-400 rounded-full transition-all" style="width:{{ $pct }}%"></div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ── Projets : filtres + liste ───────────────────────────────── --}}
    <div class="card">
        {{-- En-tête avec filtres --}}
        <div class="card-header flex-wrap gap-3">
            <div>
                <h3 class="section-title text-base">Projets soumis</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $projects->total() }} résultat(s)@if(request()->hasAny(['search','structure_id','decision'])) <span class="text-blue-500">· filtrés</span>@endif</p>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('comite.dashboard') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-44">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Porteur, email…"
                           class="form-input text-sm">
                </div>
                <div class="w-44">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Établissement</label>
                    <select name="structure_id" class="form-input text-sm">
                        <option value="">Tous</option>
                        @foreach($structures as $s)
                        <option value="{{ $s->id }}" {{ request('structure_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->acronym ?? $s->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Décision</label>
                    <select name="decision" class="form-input text-sm">
                        <option value="">Toutes</option>
                        <option value="selected" {{ request('decision') === 'selected' ? 'selected' : '' }}>Sélectionnés</option>
                        <option value="pending"  {{ request('decision') === 'pending'  ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary text-sm">Filtrer</button>
                    @if(request()->hasAny(['search','structure_id','decision']))
                    <a href="{{ route('comite.dashboard') }}" class="btn-secondary text-sm">Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Liste --}}
        <div class="divide-y divide-gray-50">
            @forelse($projects as $project)
            @php
                $score     = $myScores[$project->id] ?? null;
                $isLocked  = $project->selected && $project->email_sent_at;
            @endphp
            <div class="px-6 py-5 hover:bg-gray-50/60 transition-colors {{ $project->selected ? 'border-l-4 border-l-emerald-400' : 'border-l-4 border-l-transparent' }}">
                <div class="flex items-start gap-4">

                    {{-- Checkbox sélection --}}
                    <div class="shrink-0 pt-1">
                        @if($isLocked)
                        <div class="w-6 h-6 rounded-lg bg-emerald-500 border-2 border-emerald-500 flex items-center justify-center cursor-not-allowed"
                             title="Email envoyé – impossible de désélectionner">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        @else
                        <form method="POST" action="{{ route('comite.projects.toggle', $project) }}">
                            @csrf
                            <button type="submit"
                                class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all
                                    {{ $project->selected ? 'bg-emerald-500 border-emerald-500 hover:bg-emerald-600' : 'border-gray-300 hover:border-emerald-400 hover:bg-emerald-50' }}"
                                title="{{ $project->selected ? 'Désélectionner' : 'Sélectionner' }}">
                                @if($project->selected)
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                @endif
                            </button>
                        </form>
                        @endif
                    </div>

                    {{-- Contenu principal --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 flex-wrap">

                            {{-- Titre + meta --}}
                            <div class="min-w-0">
                                <a href="{{ route('comite.projects.show', $project) }}"
                                   class="font-semibold text-gray-900 hover:text-blue-600 text-sm leading-snug line-clamp-1 transition-colors">
                                    {{ $project->assignment?->title ?? 'Projet sans titre' }}
                                </a>
                                <div class="flex items-center flex-wrap gap-2 mt-1.5">
                                    <span class="badge-blue">{{ $project->structure?->acronym }}</span>
                                    @if($project->scientific_domain)
                                    <span class="text-xs text-gray-400">{{ $project->scientific_domain }}</span>
                                    @endif
                                    <span class="text-xs text-gray-400">·</span>
                                    <span class="text-xs text-gray-500">{{ $project->porteur->name }}</span>
                                </div>
                            </div>

                            {{-- Badges statut + action --}}
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                @if($project->selected)
                                    <span class="badge-green font-semibold">Sélectionné</span>
                                    @if($project->email_sent_at)
                                    <span class="badge-blue">Email envoyé</span>
                                    @endif
                                @else
                                    <span class="badge-gray">En attente</span>
                                @endif

                                @if($evaluationEnabled)
                                    @if(!isset($myScores[$project->id]))
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-400 border border-gray-200">Non notée</span>
                                    @elseif($myScores[$project->id]->isSubmitted())
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-violet-50 text-violet-700 border border-violet-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Notée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Brouillon</span>
                                    @endif
                                @endif

                                <a href="{{ route('comite.projects.show', $project) }}"
                                   class="btn-secondary text-xs px-3 py-1.5 gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Voir
                                </a>
                            </div>
                        </div>

                        {{-- Détails secondaires --}}
                        <div class="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-400">
                            @if($project->maturity_level)
                            <span>Maturité : <span class="text-gray-600 font-medium">{{ \App\Models\Project::maturityLabels()[$project->maturity_level] ?? '—' }}</span></span>
                            @endif
                            @if($project->project_types)
                            <span>Type : <span class="text-gray-600 font-medium">{{ implode(', ', array_map(fn($t) => \App\Models\Project::projectTypeLabels()[$t] ?? $t, $project->project_types)) }}</span></span>
                            @endif
                            @if($project->presentation_formats)
                            <span>Format souhaité : <span class="text-gray-600 font-medium">{{ implode(', ', array_map(fn($f) => \App\Models\Project::presentationLabels()[$f] ?? $f, $project->presentation_formats)) }}</span></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">Aucun projet trouvé</p>
                <p class="text-gray-400 text-xs mt-1">
                    @if(request()->hasAny(['search','structure_id','decision']))
                        Essayez d'ajuster vos filtres.
                    @else
                        Les projets apparaîtront ici une fois soumis par les porteurs.
                    @endif
                </p>
            </div>
            @endforelse
        </div>

        @if($projects->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
        @endif
    </div>

</div>

@php
    // Passe les scores de notation au template (pour le badge "Notée/Brouillon")
    $myScores = isset($myScores) ? $myScores : collect();
@endphp
@endsection
