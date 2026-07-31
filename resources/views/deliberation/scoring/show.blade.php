@extends('layouts.app')
@section('title', 'Noter le projet')
@section('page-title', $project->assignment?->title ?? 'Noter le projet')
@section('page-subtitle', $project->structure?->name ?? '')

@section('content')
@php
    $locked = $score->isSubmitted();
    $totalMax = $rubric->totalPoints();
@endphp
<div class="max-w-3xl space-y-4">

    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div><p class="text-xs text-gray-400 mb-0.5">Responsable</p><p class="font-medium text-gray-800">{{ $project->responsable_nom }}</p></div>
            <div><p class="text-xs text-gray-400 mb-0.5">Structure</p><p class="font-medium text-gray-800">{{ $project->structure?->name ?? '–' }}</p></div>
            <div><p class="text-xs text-gray-400 mb-0.5">Format</p><p class="font-medium text-gray-800">{{ $project->isApprofondi() ? 'Approfondi' : 'Standard' }}</p></div>
            <div><p class="text-xs text-gray-400 mb-0.5">Domaine</p><p class="font-medium text-gray-800">{{ $project->scientific_domain ?? '–' }}</p></div>
        </div>
        @if($project->summary)
        <p class="text-sm text-gray-600 mt-4 whitespace-pre-line leading-relaxed">{{ $project->summary }}</p>
        @endif
    </div>

    @if($locked)
    <div class="alert-info">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Votre notation a déjà été soumise. Vous pouvez encore la modifier ci-dessous si nécessaire.</span>
    </div>
    @endif

    <form method="POST" action="{{ route('deliberation.scoring.store', $project) }}" id="scoring-form">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Grille de notation</h3>
                <span class="text-xs text-gray-400">Total : <span id="total-points" class="font-semibold text-gray-700">0</span> / {{ $totalMax }} pts</span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($rubric->criteria as $criterion)
                <div class="px-5 py-4 flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $criterion->label }}</p>
                        <p class="text-xs text-gray-400">Barème : {{ $criterion->max_points }} pts</p>
                    </div>
                    <input type="number" name="points[{{ $criterion->id }}]" min="0" max="{{ $criterion->max_points }}" step="0.5"
                           value="{{ old("points.{$criterion->id}", $existingPoints->get($criterion->id, '')) }}"
                           class="form-input w-24 text-sm text-center points-input" data-max="{{ $criterion->max_points }}" required>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-wrap items-center gap-3 mt-4">
            <button type="submit" name="action" value="submit" class="btn-primary">Soumettre la notation</button>
            <button type="submit" name="action" value="draft" class="btn-secondary">Enregistrer le brouillon</button>
            <a href="{{ route('deliberation.scoring.index') }}" class="btn-secondary">← Retour</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function recomputeTotal() {
    let total = 0;
    document.querySelectorAll('.points-input').forEach(el => total += parseFloat(el.value) || 0);
    document.getElementById('total-points').textContent = total;
}
document.querySelectorAll('.points-input').forEach(el => el.addEventListener('input', recomputeTotal));
recomputeTotal();
</script>
@endpush
@endsection
