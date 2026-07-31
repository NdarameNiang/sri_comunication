@extends('layouts.app')
@section('title', 'Grille d\'évaluation')
@section('page-title', 'Grille d\'évaluation')
@section('page-subtitle', $event ? $event->event_name : 'Aucun événement actif')

@section('content')
<div class="space-y-5 max-w-4xl">
    @if(!$event)
    <div class="alert-info">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Aucun événement actif — activez un événement pour configurer sa grille d'évaluation.</span>
    </div>
    @else
    <p class="text-sm text-gray-500">Une grille distincte pour chaque format de soumission (Standard / Approfondi). Une grille est verrouillée dès qu'au moins une note a été soumise dessus.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @foreach(['standard' => 'Format Standard', 'approfondi' => 'Format Approfondi'] as $tpl => $label)
        @php $rubric = $rubrics->get($tpl); @endphp
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $label }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $rubric?->criteria->count() ?? 0 }} critère(s) · {{ $rubric?->totalPoints() ?? 0 }} pts</p>
                </div>
                @if($rubric?->isLocked())
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Verrouillée
                </span>
                @endif
            </div>
            <div class="p-5">
                @if($rubric && $rubric->criteria->count())
                <ul class="space-y-2 mb-4">
                    @foreach($rubric->criteria as $c)
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-700">{{ $c->label }}</span>
                        <span class="text-gray-400 font-mono text-xs">{{ $c->max_points }} pts</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-400 italic mb-4">Aucun critère configuré.</p>
                @endif
                <a href="{{ route('evaluation.rubrics.edit', $tpl) }}" class="btn-secondary text-sm inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    {{ $rubric?->isLocked() ? 'Consulter' : 'Modifier' }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
