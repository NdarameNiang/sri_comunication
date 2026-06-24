@extends('layouts.app')
@section('title', 'Gestion des projets')
@section('page-title', 'Gestion des projets')
@section('page-subtitle', $assignments->total() . ' affectation(s)')

@section('content')
<div class="space-y-4">

    {{-- Stats --}}
    @php
        $nbSub   = \App\Models\Project::where('status','submitted')->count();
        $nbDraft = \App\Models\Project::where('status','draft')->count();
        $nbPend  = \App\Models\ProjectAssignment::whereDoesntHave('project')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $assignments->total() }}</p>
                <p class="text-xs text-gray-500">Total affectations</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-700">{{ $nbSub }}</p>
                <p class="text-xs text-gray-500">Soumis</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-amber-600">{{ $nbDraft }}</p>
                <p class="text-xs text-gray-500">Brouillons</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-blue-600">{{ $nbPend }}</p>
                <p class="text-xs text-gray-500">En attente</p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div>
        <a href="{{ route('superadmin.projects.create') }}" class="btn-primary text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Affecter un projet
        </a>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('superadmin.projects.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Titre, porteur…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Statut</label>
                <select name="status" class="input-field text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="submitted" {{ request('status')==='submitted' ? 'selected' : '' }}>Soumis</option>
                    <option value="draft"     {{ request('status')==='draft'     ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending"   {{ request('status')==='pending'   ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Porteur</label>
                <select name="porteur_id" class="input-field text-sm">
                    <option value="">Tous les porteurs</option>
                    @foreach($porteurs as $p)
                    <option value="{{ $p->id }}" {{ request('porteur_id')==$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Structure</label>
                <select name="structure_id" class="input-field text-sm">
                    <option value="">Toutes les structures</option>
                    @foreach($structures as $s)
                    <option value="{{ $s->id }}" {{ request('structure_id')==$s->id ? 'selected' : '' }}>{{ $s->acronym ?? $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['search','status','porteur_id','structure_id']))
            <a href="{{ route('superadmin.projects.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">{{ $assignments->total() }} projet(s)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Porteur</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Titre du projet</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Structure</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Statut</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $a)
                    @php
                        $pstatus = $a->project?->status ?? 'pending';
                        $badges  = [
                            'submitted' => ['label'=>'Soumis',     'class'=>'bg-emerald-100 text-emerald-700'],
                            'draft'     => ['label'=>'Brouillon',  'class'=>'bg-amber-100 text-amber-700'],
                            'pending'   => ['label'=>'En attente', 'class'=>'bg-gray-100 text-gray-500'],
                        ];
                        $badge = $badges[$pstatus] ?? $badges['pending'];
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($a->porteur?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs">{{ $a->porteur?->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $a->porteur?->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-800 max-w-xs truncate">{{ $a->title }}</p>
                            @if($a->project)
                            <p class="text-xs text-gray-400 mt-0.5">Modifié {{ $a->project->updated_at->diffForHumans() }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-xs text-gray-600">{{ $a->structure?->acronym ?? $a->structure?->name ?? '—' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($pstatus === 'pending')
                                    <a href="{{ route('superadmin.assignments.fill', $a) }}"
                                       class="btn-primary text-xs py-1 px-2.5 gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Remplir
                                    </a>

                                @elseif($pstatus === 'draft')
                                    <a href="{{ route('superadmin.projects.edit', $a->project) }}"
                                       class="btn-secondary text-xs py-1 px-2.5 gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.projects.submit', $a->project) }}"
                                          data-confirm="Soumettre ce projet au nom de {{ $a->porteur?->name }} ? Un email de confirmation lui sera envoyé."
                                          data-confirm-title="Soumettre le projet"
                                          data-confirm-type="warning"
                                          class="inline">
                                        @csrf
                                        <button type="submit" class="btn-success text-xs py-1 px-2.5 gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                            Soumettre
                                        </button>
                                    </form>

                                @elseif($pstatus === 'submitted')
                                    <a href="{{ route('superadmin.projects.edit', $a->project) }}"
                                       class="btn-secondary text-xs py-1 px-2.5 gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Modifier
                                    </a>
                                @endif

                                @if($a->porteur)
                                <a href="{{ route('superadmin.impersonate.start', $a->porteur) }}"
                                   class="btn-secondary text-xs py-1 px-2.5 gap-1"
                                   title="Voir l'interface du porteur">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Vue porteur
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm">Aucun résultat</p>
                            @if(request()->hasAny(['search','status','porteur_id','structure_id']))
                            <a href="{{ route('superadmin.projects.index') }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Réinitialiser les filtres</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($assignments->hasPages())
    <div>{{ $assignments->links() }}</div>
    @endif

</div>
@endsection
