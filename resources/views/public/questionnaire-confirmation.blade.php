@extends('layouts.public')
@section('title', 'Merci – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Questionnaire d\'appréciation')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">

    <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-2">Merci pour votre retour !</h2>
    <p class="text-gray-600 text-sm mb-6">
        Votre questionnaire pour <strong>{{ $event->event_name }}</strong> a bien été enregistré. Votre avis nous aide à améliorer les prochaines éditions.
    </p>

    <div class="flex flex-col items-center gap-3 mb-6">
        <p class="text-sm text-gray-600 font-medium">QR Code du questionnaire (à partager)</p>
        <div class="border border-gray-200 rounded-xl p-4 inline-block bg-gray-50">
            {!! $qrSvg !!}
        </div>
        <p class="text-xs text-gray-400">Partagez ce QR code pour inviter d'autres participants à répondre</p>
    </div>

    <a href="{{ route('public.questionnaire.show', $event->event_slug) }}"
       class="inline-block mt-2 text-sm text-blue-600 hover:underline">
        Répondre à nouveau
    </a>
</div>
@endsection
