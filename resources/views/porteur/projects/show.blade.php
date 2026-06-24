@extends('layouts.app')
@section('title', $project->assignment?->title ?? 'Mon projet')
@section('page-title', $project->assignment?->title ?? 'Mon projet')
@section('page-subtitle', 'Appel à communication soumis')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- ── En-tête : responsable + statuts ──────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 pt-6 pb-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900 leading-snug">{{ $project->assignment?->title ?? 'Projet sans titre' }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $project->structure?->name ?? '–' }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Soumis
                    </span>
                    @if($project->selected)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Sélectionné
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
            @foreach([
                ['Responsable', $project->responsable_nom],
                ['Email', $project->contact_email],
                ['Téléphone', $project->contact_phone ?? '–'],
                ['Soumis le', $project->created_at->format('d/m/Y')],
            ] as [$label, $value])
            <div class="px-4 py-3">
                <p class="text-xs text-gray-400">{{ $label }}</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5 truncate" title="{{ $value }}">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Sections texte ─────────────────────────────────────────────── --}}
    @php
    $sections = [
        ['num' => '01', 'label' => 'Résumé',           'field' => 'summary',     'color' => 'border-indigo-300'],
        ['num' => '02', 'label' => 'Problématique',    'field' => 'problematic', 'color' => 'border-amber-300'],
        ['num' => '03', 'label' => 'Solution proposée','field' => 'solution',    'color' => 'border-emerald-300'],
        ['num' => '04', 'label' => 'Résultats attendus','field' => 'results',    'color' => 'border-blue-300'],
    ];
    @endphp

    @foreach($sections as $s)
    @if($project->{$s['field']})
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <span class="text-xs font-bold text-gray-300 font-mono tracking-widest">{{ $s['num'] }}</span>
            <h3 class="text-sm font-semibold text-gray-700">{{ $s['label'] }}</h3>
        </div>
        <div class="px-5 py-4 border-l-2 {{ $s['color'] }} mx-5 my-4 rounded-sm">
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->{$s['field']} }}</p>
        </div>
    </div>
    @endif
    @endforeach

    {{-- ── Collaborateurs ─────────────────────────────────────────────── --}}
    @if($project->collaborators->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Collaborateurs</h3>
            <span class="text-xs text-gray-400">{{ $project->collaborators->count() }} membre(s)</span>
        </div>
        <div class="divide-y divide-gray-50 px-2 py-2">
            @foreach($project->collaborators as $collab)
            <div class="flex items-center gap-3 px-3 py-2.5">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($collab->prenom ?? $collab->nom ?? '?', 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $collab->fullName() }}</p>
                    <p class="text-xs text-gray-400">{{ $collab->institution ?? $collab->role_collaborateur ?? '' }}</p>
                </div>
                @if($collab->email)
                <p class="text-xs text-gray-400 hidden sm:block">{{ $collab->email }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('porteur.dashboard') }}" class="btn-secondary text-sm inline-flex items-center gap-1.5">
        ← Retour au tableau de bord
    </a>

</div>
@endsection
