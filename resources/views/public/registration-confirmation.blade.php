@extends('layouts.public')
@section('title', 'Confirmation – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Confirmation d\'inscription')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">

    <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-2">Inscription confirmée !</h2>
    <p class="text-gray-600 text-sm mb-6">
        Merci, <strong>{{ $registration->fullName() }}</strong>. Votre inscription à <strong>{{ $event->event_name }}</strong> est bien enregistrée.
    </p>

    {{-- QR Code --}}
    <div class="flex flex-col items-center gap-3 mb-6">
        <p class="text-sm text-gray-600 font-medium">Votre QR Code de participation</p>
        <div class="border border-gray-200 rounded-xl p-4 inline-block bg-gray-50">
            {!! $qrSvg !!}
        </div>
        <p class="text-xs text-gray-400">Présentez ce QR code à l'accueil pour confirmer votre présence</p>
    </div>

    {{-- Récapitulatif --}}
    <div class="text-left border border-gray-100 rounded-xl p-4 text-sm space-y-2 mb-6">
        @if($registration->email)
        <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="font-medium">{{ $registration->email }}</span></div>
        @endif
        @if($registration->institution)
        <div class="flex justify-between"><span class="text-gray-500">Institution</span><span class="font-medium">{{ $registration->institution }}</span></div>
        @endif
        @if($registration->type_participant)
        <div class="flex justify-between"><span class="text-gray-500">Profil</span><span class="font-medium">{{ $registration->type_participant }}</span></div>
        @endif
    </div>

    @if($event->event_start_date)
    <div class="bg-slate-50 rounded-xl p-4 text-sm text-gray-700">
        <p class="font-medium">{{ $event->event_name }}</p>
        <p class="text-gray-500 text-xs mt-1">{{ $event->event_start_date->format('d/m/Y') }} – {{ $event->event_end_date?->format('d/m/Y') }}</p>
    </div>
    @endif
</div>
@endsection
