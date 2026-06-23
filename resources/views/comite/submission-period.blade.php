@extends('layouts.app')
@section('title', 'Période de soumission')
@section('page-title', 'Période de soumission')
@section('page-subtitle', $event->event_name)

@section('content')
<div class="max-w-xl space-y-6">

    {{-- Statut actuel --}}
    @php $status = $event->submissionStatus(); @endphp
    <div class="rounded-xl p-4 border
        {{ $status === 'open'     ? 'bg-green-50 border-green-200'  : '' }}
        {{ $status === 'not_open' ? 'bg-amber-50 border-amber-200'  : '' }}
        {{ $status === 'closed'   ? 'bg-red-50  border-red-200'    : '' }}">
        <p class="font-medium text-sm
            {{ $status === 'open'     ? 'text-green-800'  : '' }}
            {{ $status === 'not_open' ? 'text-amber-800'  : '' }}
            {{ $status === 'closed'   ? 'text-red-800'    : '' }}">
            @if($status === 'open') Soumissions ouvertes
            @elseif($status === 'not_open') Soumissions pas encore ouvertes
            @else Soumissions clôturées
            @endif
        </p>
        @if($event->submission_open_at)
        <p class="text-xs mt-1 {{ $status === 'open' ? 'text-green-600' : ($status === 'not_open' ? 'text-amber-600' : 'text-red-600') }}">
            Ouverture : {{ $event->submission_open_at->format('d/m/Y à H:i') }}
            @if($event->submission_close_at) · Clôture : {{ $event->submission_close_at->format('d/m/Y à H:i') }}@endif
        </p>
        @endif
    </div>

    {{-- Formulaire --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Modifier la période</h2>

        <form method="POST" action="{{ route('comite.submission-period.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'ouverture des soumissions</label>
                <input type="datetime-local" name="submission_open_at"
                       value="{{ $event->submission_open_at?->format('Y-m-d\TH:i') }}"
                       class="input-field">
                @error('submission_open_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de clôture des soumissions</label>
                <input type="datetime-local" name="submission_close_at"
                       value="{{ $event->submission_close_at?->format('Y-m-d\TH:i') }}"
                       class="input-field">
                @error('submission_close_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary w-full">Enregistrer la période</button>
            </div>
        </form>
    </div>
</div>
@endsection
