@extends('layouts.public')
@section('title', 'Merci – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Questionnaire d\'appréciation')
@section('event-badge', 'Questionnaire reçu')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

    {{-- Header succès --}}
    <div class="px-6 py-8 text-center border-b border-gray-100">
        <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Merci pour votre retour !</h2>
        <p class="text-gray-500 text-sm max-w-sm mx-auto">
            Votre questionnaire pour <strong class="text-gray-700">{{ $event->event_name }}</strong> a bien été enregistré.
        </p>
    </div>

    {{-- QR code --}}
    <div class="px-6 py-8 flex flex-col items-center gap-4 bg-gray-50">
        <div class="text-center">
            <p class="text-sm font-semibold text-gray-700 mb-1">QR Code du questionnaire</p>
            <p class="text-xs text-gray-400">Partagez ce code pour inviter d'autres participants</p>
        </div>

        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-5 shadow-sm">
            {!! $qrSvg !!}
        </div>

        <a href="{{ route('public.questionnaire.show', $event->event_slug) }}"
           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>
            Répondre à nouveau
        </a>
    </div>

</div>
@endsection
