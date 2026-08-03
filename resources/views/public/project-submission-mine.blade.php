@extends('layouts.public')
@section('title', 'Mes dossiers – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Dépôt de projet')
@section('event-badge', 'Mes dossiers')

@section('content')
<div class="space-y-5">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-base leading-tight">Bonjour {{ trim($identity['prenom'] . ' ' . $identity['nom']) }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Vous avez déjà déposé {{ $assignments->count() > 1 ? 'des dossiers' : 'un dossier' }} pour {{ $event->event_name }}</p>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($assignments as $assignment)
            <div class="px-6 py-4 flex items-center gap-4">
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->title ?: 'Dossier sans titre' }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($assignment->project?->isSubmitted())
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Soumis
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Brouillon</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('public.project-submission.fill', [$event->event_slug, $assignment, $assignment->public_token]) }}"
                   class="shrink-0 text-xs font-semibold px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                    {{ $assignment->project?->isSubmitted() ? 'Voir' : 'Continuer' }}
                </a>
            </div>
            @endforeach
        </div>

        @if($canSubmitNew)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <a href="{{ route('public.project-submission.details', $event->event_slug) }}"
               class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95"
               style="background: linear-gradient(135deg, var(--brand-primary, #1d4ed8) 0%, color-mix(in srgb, var(--brand-primary, #1d4ed8), black 25%) 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Déposer un nouveau projet
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
