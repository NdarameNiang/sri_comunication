@extends('layouts.app')
@section('title', 'Espace Secrétaire')
@section('page-title', 'Espace Secrétaire')
@section('page-subtitle', $event?->event_name ?? 'Aucun événement actif')

@section('content')
<div class="space-y-6">

    @if(!$event)
        <div class="alert-error">Aucun événement actif. Demandez à l'administrateur de configurer un événement.</div>
    @else
    {{-- Bannière --}}
    <div class="rounded-xl p-6 text-white" style="background: linear-gradient(135deg, #1e293b, #334155)">
        <h1 class="text-xl font-bold">{{ $event->event_name }}</h1>
        <p class="text-white/70 text-sm mt-1">{{ $event->event_description }}</p>
        @if($event->event_start_date)
            <p class="text-white/50 text-xs mt-2">{{ $event->event_start_date->format('d/m/Y') }} – {{ $event->event_end_date?->format('d/m/Y') }}</p>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Inscriptions</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['inscriptions'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Présences confirmées</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['presences'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Questionnaires reçus</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['questionnaires'] }}</p>
        </div>
    </div>

    {{-- QR codes publics --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Liens & QR Codes publics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="font-medium text-sm text-gray-700 mb-2">Formulaire d'inscription</p>
                <p class="text-xs text-gray-500 break-all mb-3">{{ route('public.registration.show', $event->event_slug) }}</p>
                <a href="{{ route('public.registration.show', $event->event_slug) }}" target="_blank"
                   class="btn-secondary text-xs">Ouvrir le formulaire</a>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="font-medium text-sm text-gray-700 mb-2">Questionnaire d'appréciation</p>
                <p class="text-xs text-gray-500 break-all mb-3">{{ route('public.questionnaire.show', $event->event_slug) }}</p>
                <a href="{{ route('public.questionnaire.show', $event->event_slug) }}" target="_blank"
                   class="btn-secondary text-xs">Ouvrir le questionnaire</a>
            </div>
        </div>
    </div>

    {{-- Dernières inscriptions --}}
    @if($recentRegistrations->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-900">Dernières inscriptions</h2>
            <a href="{{ route('secretaire.inscriptions.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 px-3 text-xs text-gray-500 font-medium">Nom</th>
                        <th class="text-left py-2 px-3 text-xs text-gray-500 font-medium">Institution</th>
                        <th class="text-left py-2 px-3 text-xs text-gray-500 font-medium">Type</th>
                        <th class="text-left py-2 px-3 text-xs text-gray-500 font-medium">Présence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRegistrations as $reg)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 px-3 font-medium">{{ $reg->fullName() }}</td>
                        <td class="py-2 px-3 text-gray-600">{{ $reg->institution ?? '–' }}</td>
                        <td class="py-2 px-3 text-gray-600">{{ $reg->type_participant ?? '–' }}</td>
                        <td class="py-2 px-3">
                            @if($reg->presence_confirmee)
                                <span class="badge-green">Présent</span>
                            @else
                                <span class="badge-gray">Non confirmé</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
