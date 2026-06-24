@extends('layouts.app')
@section('title', 'Sélection des projets')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Évaluation et sélection des projets soumis')

@section('content')
<div class="space-y-6">

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Projets soumis',      'val' => $stats['total'],    'bg' => 'bg-blue-100',   'fg' => 'text-blue-600',   'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
            ['label' => 'Sélectionnés',        'val' => $stats['selected'], 'bg' => 'bg-emerald-100','fg' => 'text-emerald-600', 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
            ['label' => 'Emails envoyés',      'val' => $stats['sent'],     'bg' => 'bg-amber-100',  'fg' => 'text-amber-600',  'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
        ] as $s)
        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon {{ $s['bg'] }} shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $s['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900">{{ $s['val'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Action envoi d'emails --}}
    @if($stats['selected'] > 0)
    <div class="card bg-emerald-50 border-emerald-200">
        <div class="card-body flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-emerald-900">
                    {{ $stats['selected'] }} projet(s) sélectionné(s)
                    @if($stats['sent'] > 0)
                        · {{ $stats['sent'] }} email(s) déjà envoyé(s)
                    @endif
                </p>
                <p class="text-sm text-emerald-700 mt-0.5">
                    Envoyez les notifications officielles à tous les porteurs des projets sélectionnés qui n'ont pas encore reçu leur email.
                </p>
            </div>
            <form method="POST" action="{{ route('comite.send-emails') }}"
                  data-confirm="Envoyer les emails officiels aux porteurs sélectionnés ? Cette action ne peut pas être annulée."
                  data-confirm-title="Envoi des notifications"
                  data-confirm-type="info">
                @csrf
                <button type="submit" class="btn-success whitespace-nowrap">
                    @include('components.icon', ['name' => 'mail'])
                    Envoyer les notifications
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Export CSV --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        <span class="text-sm font-medium text-gray-700">Exporter les appels à communication :</span>
        <a href="{{ route('comite.projects.export') }}" class="btn-secondary text-sm flex items-center gap-1.5">
            Tous les établissements
        </a>
        @foreach($structures->where('submitted_count', '>', 0) as $s)
            <a href="{{ route('comite.projects.export', ['structure' => $s->id]) }}"
               class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium transition-colors">
                {{ $s->acronym ?? $s->name }}
                <span class="text-gray-400">({{ $s->submitted_count }})</span>
            </a>
        @endforeach
    </div>

    {{-- Résumé par structure --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Résumé par structure</h3>
        </div>
        <div class="card-body grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($structures as $structure)
            @if($structure->submitted_count > 0)
            <div class="p-3 rounded-xl border border-gray-200 bg-gray-50">
                <span class="badge-blue">{{ $structure->acronym }}</span>
                <div class="mt-2 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Soumis</span>
                        <span class="font-semibold text-gray-900">{{ $structure->submitted_count }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Sélectionnés</span>
                        <span class="font-semibold text-emerald-700">{{ $structure->selected_count }}</span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Filtres projets --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('comite.dashboard') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs text-gray-500 mb-1">Recherche porteur</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, prénom, email…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Structure</label>
                <select name="structure_id" class="input-field text-sm">
                    <option value="">Toutes les structures</option>
                    @foreach($structures as $s)
                    <option value="{{ $s->id }}" {{ request('structure_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->acronym ?? $s->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Décision</label>
                <select name="decision" class="input-field text-sm">
                    <option value="">Toutes</option>
                    <option value="selected" {{ request('decision') === 'selected' ? 'selected' : '' }}>Sélectionnés</option>
                    <option value="pending"  {{ request('decision') === 'pending'  ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['search','structure_id','decision']))
            <a href="{{ route('comite.dashboard') }}" class="btn-secondary text-sm">Réinitialiser</a>
            @endif
        </form>
    </div>

    {{-- Liste des projets --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Projets soumis</h3>
            <span class="text-xs text-gray-400">{{ $projects->total() }} résultat(s)</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($projects as $project)
            <div class="px-6 py-5 {{ $project->selected ? 'bg-emerald-50/50' : '' }}">
                <div class="flex items-start gap-4">
                    {{-- Checkbox sélection --}}
                    <div class="shrink-0 pt-0.5">
                        @if($project->selected && $project->email_sent_at)
                            {{-- Verrouillé : email déjà envoyé --}}
                            <div class="w-6 h-6 rounded-md border-2 bg-emerald-500 border-emerald-500 flex items-center justify-center cursor-not-allowed"
                                 title="Email envoyé – impossible de désélectionner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                        @else
                            <form method="POST" action="{{ route('comite.projects.toggle', $project) }}">
                                @csrf
                                <button type="submit" class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition-all
                                    {{ $project->selected
                                        ? 'bg-emerald-500 border-emerald-500 hover:bg-emerald-600'
                                        : 'border-gray-300 hover:border-emerald-400' }}"
                                    title="{{ $project->selected ? 'Désélectionner' : 'Sélectionner' }}">
                                    @if($project->selected)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <a href="{{ route('comite.projects.show', $project) }}" class="font-semibold text-gray-900 hover:text-blue-700 text-sm leading-snug">
                                    {{ $project->assignment?->title }}
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="badge-blue text-xs">{{ $project->structure->acronym }}</span>
                                    <span class="text-xs text-gray-500">{{ $project->scientific_domain }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($project->selected)
                                    <span class="badge-green">Sélectionné</span>
                                    @if($project->email_sent_at)
                                        <span class="badge-blue text-xs">Email envoyé</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">En attente</span>
                                @endif
                                <a href="{{ route('comite.projects.show', $project) }}" class="btn-secondary text-xs px-3 py-1.5">
                                    @include('components.icon', ['name' => 'eye'])
                                    Voir
                                </a>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                            <div>
                                <p class="text-gray-400">Porteur</p>
                                <p class="font-medium text-gray-700">{{ $project->porteur->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Type(s)</p>
                                <p class="font-medium text-gray-700">
                                    {{ implode(', ', array_map(fn($t) => \App\Models\Project::projectTypeLabels()[$t] ?? $t, $project->project_types ?? [])) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400">Maturité</p>
                                <p class="font-medium text-gray-700">{{ \App\Models\Project::maturityLabels()[$project->maturity_level] ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Format souhaité</p>
                                <p class="font-medium text-gray-700">
                                    {{ $project->presentation_formats ? implode(', ', array_map(fn($f) => \App\Models\Project::presentationLabels()[$f] ?? $f, $project->presentation_formats)) : '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <p class="text-gray-400 text-sm">Aucun projet soumis pour le moment.</p>
                <p class="text-gray-300 text-xs mt-1">Les projets apparaîtront ici une fois que les porteurs les auront soumis.</p>
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
@endsection
