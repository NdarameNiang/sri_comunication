@extends('layouts.app')
@section('title', 'Espace Secrétaire')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', $event?->event_name ?? 'Aucun événement actif')

@section('content')
<div class="space-y-6">

    @if(!$event)
        <div class="alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Aucun événement actif. Demandez à l'administrateur de configurer un événement.</span>
        </div>
    @else

    {{-- ── Bannière événement ─────────────────────────────────────── --}}
    <div class="dash-banner">
        <div class="absolute right-0 top-0 w-64 h-full opacity-10 overflow-hidden pointer-events-none">
            <svg viewBox="0 0 200 200" class="absolute -right-10 -top-10 w-72 h-72 text-white" fill="currentColor">
                <circle cx="150" cy="50" r="80"/><circle cx="50" cy="150" r="60"/>
            </svg>
        </div>
        <div class="relative">
            <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Événement actif</p>
            <h2 class="text-2xl font-extrabold text-white leading-tight">{{ $event->event_name }}</h2>
            @if($event->event_description)
                <p class="text-white/70 text-sm mt-1.5 max-w-xl">{{ $event->event_description }}</p>
            @endif
            @if($event->event_start_date)
                <p class="text-white/50 text-xs mt-2 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                    {{ $event->event_start_date->format('d/m/Y') }} – {{ $event->event_end_date?->format('d/m/Y') }}
                </p>
            @endif
        </div>
    </div>

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Inscriptions',          'val' => $stats['inscriptions'],  'bg' => 'bg-blue-100',   'fg' => 'text-blue-600',   'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['label' => 'Présences confirmées',  'val' => $stats['presences'],     'bg' => 'bg-emerald-100','fg' => 'text-emerald-600', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Questionnaires reçus',  'val' => $stats['questionnaires'],'bg' => 'bg-purple-100', 'fg' => 'text-purple-600',  'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z'],
        ] as $s)
        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon {{ $s['bg'] }} shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $s['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900">{{ $s['val'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Liens & QR Codes publics ───────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Liens & QR Codes publics</h3>
        </div>
        <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach([
                ['label' => "Formulaire d'inscription",    'key' => 'inscription',   'route' => 'public.registration.show', 'color' => 'bg-blue-600'],
                ['label' => "Questionnaire d'appréciation",'key' => 'questionnaire', 'route' => 'public.questionnaire.show','color' => 'bg-purple-600'],
            ] as $link)
            @php $url = route($link['route'], $event->event_slug); @endphp
            <div class="rounded-xl border border-gray-200 p-5">
                <p class="font-semibold text-sm text-gray-900 mb-3">{{ $link['label'] }}</p>
                <p class="text-xs text-gray-400 truncate mb-4">{{ $url }}</p>

                {{-- QR Code centré --}}
                <div class="flex justify-center mb-4">
                    @if(!empty($qrCodes[$link['key']]))
                        <img src="{{ $qrCodes[$link['key']] }}" alt="QR {{ $link['label'] }}"
                             class="w-36 h-36 border border-gray-200 rounded-xl bg-white p-1">
                    @else
                        <div class="w-36 h-36 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 flex items-center justify-center text-xs text-gray-400">
                            QR indisponible
                        </div>
                    @endif
                </div>

                {{-- Boutons --}}
                <div class="flex gap-2">
                    <a href="{{ $url }}" target="_blank"
                       class="flex-1 text-center text-sm font-medium py-2 px-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        Ouvrir
                    </a>
                    <a href="{{ route('secretaire.qr.download', $link['key']) }}"
                       class="flex-1 flex items-center justify-center gap-2 text-sm font-semibold py-2 px-3 rounded-lg {{ $link['color'] }} text-white hover:opacity-90 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger QR
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Dernières inscriptions ──────────────────────────────────── --}}
    @if($recentRegistrations->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Dernières inscriptions</h3>
            <a href="{{ route('secretaire.inscriptions.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tout →</a>
        </div>
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Institution</th>
                        <th>Type</th>
                        <th class="text-center">Présence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRegistrations as $reg)
                    <tr>
                        <td class="font-semibold text-gray-900">{{ $reg->fullName() }}</td>
                        <td class="text-gray-600">{{ $reg->institution ?? '–' }}</td>
                        <td class="text-gray-600">{{ $reg->type_participant ?? '–' }}</td>
                        <td class="text-center">
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
