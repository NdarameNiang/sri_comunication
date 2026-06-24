@extends('layouts.app')
@section('title', $registration->fullName())
@section('page-title', 'Fiche participant')
@section('page-subtitle', $registration->fullName())

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    {{-- En-tête participant --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
            <span class="text-xl font-bold text-blue-600">{{ strtoupper(substr($registration->prenom ?? '?', 0, 1)) }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-gray-900">{{ $registration->fullName() }}</h2>
            <p class="text-sm text-gray-500">{{ $registration->institution ?? 'Institution non renseignée' }}</p>
        </div>
        @if($registration->presence_confirmee)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Présent
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Non confirmé
            </span>
        @endif
    </div>

    {{-- Informations --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Informations personnelles</p>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach([
                ['Email',             $registration->email,            'mail'],
                ['Téléphone',         $registration->telephone,        'phone'],
                ['Fonction',          $registration->fonction,         'briefcase'],
                ['Type participant',  $registration->type_participant, 'tag'],
                ['Date inscription',  $registration->created_at->format('d/m/Y à H:i'), 'calendar'],
            ] as [$label, $value, $icon])
            <div class="flex items-center gap-4 px-5 py-3.5">
                <div class="w-8 shrink-0 flex justify-center">
                    @if($icon === 'mail')
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    @elseif($icon === 'phone')
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    @elseif($icon === 'briefcase')
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
                    @elseif($icon === 'tag')
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    @else
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-400">{{ $label }}</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $value ?? '–' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
        <form method="POST" action="{{ route('secretaire.inscriptions.presence', $registration) }}">
            @csrf @method('PATCH')
            <button class="{{ $registration->presence_confirmee ? 'btn-secondary' : 'btn-primary' }} text-sm">
                {{ $registration->presence_confirmee ? 'Marquer absent' : '✓ Confirmer présence' }}
            </button>
        </form>
        <a href="{{ route('secretaire.inscriptions.index') }}" class="btn-secondary text-sm">← Retour</a>
    </div>

</div>
@endsection
