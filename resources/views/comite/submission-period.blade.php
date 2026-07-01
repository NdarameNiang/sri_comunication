@extends('layouts.app')
@section('title', 'Période de soumission')
@section('page-title', 'Période de soumission')
@section('page-subtitle', $event->event_name)

@section('content')
<div class="max-w-lg mx-auto space-y-4">

    {{-- Statut actuel --}}
    @php
        $status = $event->submissionStatus();
        $statusConfig = [
            'open'     => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'sub' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'label' => 'Soumissions ouvertes'],
            'not_open' => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'sub' => 'text-amber-600',   'dot' => 'bg-amber-500',   'label' => 'Pas encore ouvertes'],
            'closed'   => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-800',     'sub' => 'text-red-600',     'dot' => 'bg-red-500',     'label' => 'Soumissions clôturées'],
        ][$status];
    @endphp
    <div class="rounded-xl p-4 border {{ $statusConfig['bg'] }} {{ $statusConfig['border'] }} flex items-start gap-3">
        <div class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }} mt-1.5 shrink-0"></div>
        <div>
            <p class="text-sm font-semibold {{ $statusConfig['text'] }}">{{ $statusConfig['label'] }}</p>
            @if($event->submission_open_at)
            <p class="text-xs mt-0.5 {{ $statusConfig['sub'] }}">
                Ouverture : {{ $event->submission_open_at->format('d/m/Y à H:i') }}
                @if($event->submission_close_at)
                 · Clôture : {{ $event->submission_close_at->format('d/m/Y à H:i') }}
                @endif
            </p>
            @endif
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Modifier la période de soumission</h2>
            <p class="text-xs text-gray-400 mt-0.5">Définissez les dates d'ouverture et de clôture des dépôts.</p>
        </div>
        <div class="px-5 py-5">
            <form method="POST" action="{{ route('comite.submission-period.update') }}" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="form-label">Date d'ouverture</label>
                    <input type="datetime-local" name="submission_open_at"
                           value="{{ $event->submission_open_at?->format('Y-m-d\TH:i') }}"
                           class="input-field @error('submission_open_at') border-red-400 bg-red-50 @enderror">
                    @error('submission_open_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Date de clôture</label>
                    <input type="datetime-local" name="submission_close_at"
                           value="{{ $event->submission_close_at?->format('Y-m-d\TH:i') }}"
                           class="input-field @error('submission_close_at') border-red-400 bg-red-50 @enderror">
                    @error('submission_close_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="submit" class="btn-primary flex-1">Enregistrer</button>
                    <a href="{{ route('comite.dashboard') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
