@extends('layouts.app')
@section('title', 'Modifier la grille d\'évaluation')
@section('page-title', 'Grille d\'évaluation — ' . ($template === 'approfondi' ? 'Format Approfondi' : 'Format Standard'))
@section('page-subtitle', $rubric->event->event_name)

@section('content')
@php $locked = $rubric->isLocked(); @endphp
<div class="max-w-5xl space-y-4">
    @if($locked)
    <div class="alert-info">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
        <span>Cette grille est verrouillée : des notes ont déjà été soumises avec cette configuration. Elle ne peut plus être modifiée structurellement.</span>
    </div>
    @endif

    <form method="POST" action="{{ route('evaluation.rubrics.update', $template) }}" id="rubric-form">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Critères</h3>
                <span class="text-xs text-gray-400">Total : <span id="total-points">0</span> pts</span>
            </div>
            <div id="criteria-list" class="divide-y divide-gray-50">
                @foreach($rubric->criteria as $i => $c)
                <div class="criterion-row flex items-center gap-3 px-5 py-3">
                    <input type="text" name="criteria[{{ $i }}][label]" value="{{ $c->label }}" placeholder="Libellé du critère"
                           class="form-input flex-1 text-sm" {{ $locked ? 'disabled' : '' }} required>
                    <input type="number" name="criteria[{{ $i }}][max_points]" value="{{ $c->max_points }}" min="1" max="100"
                           class="form-input w-24 text-sm points-input" {{ $locked ? 'disabled' : '' }} required>
                    @if(!$locked)
                    <button type="button" onclick="this.closest('.criterion-row').remove(); recomputeTotal();" class="text-xs text-red-500 hover:text-red-700 shrink-0">Supprimer</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$locked)
            <div class="px-5 py-3 border-t border-gray-100">
                <button type="button" onclick="addCriterion()" class="text-sm text-blue-600 hover:text-blue-800">+ Ajouter un critère</button>
            </div>
            @endif
        </div>

        @if(!$locked)
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-3 mt-4">
            <button type="submit" class="btn-primary">Enregistrer la grille</button>
            <a href="{{ route('evaluation.rubrics.index') }}" class="btn-secondary">Annuler</a>
        </div>
        @else
        <a href="{{ route('evaluation.rubrics.index') }}" class="btn-secondary text-sm inline-flex items-center gap-1.5 mt-4">← Retour</a>
        @endif
    </form>
</div>

@push('scripts')
<script>
let criterionCount = document.querySelectorAll('.criterion-row').length;

function addCriterion() {
    const idx = criterionCount++;
    const div = document.createElement('div');
    div.className = 'criterion-row flex items-center gap-3 px-5 py-3';
    div.innerHTML = `
        <input type="text" name="criteria[${idx}][label]" placeholder="Libellé du critère" class="form-input flex-1 text-sm" required>
        <input type="number" name="criteria[${idx}][max_points]" min="1" max="100" class="form-input w-24 text-sm points-input" required>
        <button type="button" onclick="this.closest('.criterion-row').remove(); recomputeTotal();" class="text-xs text-red-500 hover:text-red-700 shrink-0">Supprimer</button>`;
    document.getElementById('criteria-list').appendChild(div);
    div.querySelector('.points-input').addEventListener('input', recomputeTotal);
    recomputeTotal();
}

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
