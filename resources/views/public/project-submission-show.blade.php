@extends('layouts.public')
@section('title', 'Mon dossier – ' . ($project->assignment?->title ?? 'Projet'))
@section('event-name', $project->assignment?->title ?? 'Mon dossier')
@section('event-subtitle', $project->structure?->name ?? '')
@section('event-badge', $project->isSubmitted() ? 'Dossier soumis' : 'Brouillon')

@section('content')
<div class="space-y-5">

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 pt-6 pb-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900 leading-snug">{{ $project->assignment?->title ?? 'Projet sans titre' }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $project->structure?->name ?? '–' }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    @if($project->isSubmitted())
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Soumis
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Brouillon</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!$project->isSubmitted())
    <a href="{{ route('public.project-submission.fill', [$eventSlug, $assignment, $token]) }}" class="btn-primary w-full flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        Continuer / modifier mon dossier
    </a>
    @else
    <div class="alert-info">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Votre dossier a été soumis avec succès. Un email de confirmation avec le récapitulatif PDF vous a été envoyé.</span>
    </div>
    @endif

    @include('partials.project-approfondi-detail', ['project' => $project])

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
                    <p class="text-xs text-gray-400">{{ $collab->institution ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
