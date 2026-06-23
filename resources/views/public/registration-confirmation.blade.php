@extends('layouts.public')
@section('title', 'Confirmation – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Confirmation d\'inscription')
@section('event-badge', 'Inscription confirmée')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

    {{-- Header succès --}}
    <div class="px-6 py-8 text-center border-b border-gray-100">
        <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Inscription confirmée !</h2>
        <p class="text-gray-500 text-sm max-w-sm mx-auto">
            Bienvenue, <strong class="text-gray-700">{{ $registration->fullName() }}</strong>.
            Votre inscription à <strong class="text-gray-700">{{ $event->event_name }}</strong> est enregistrée.
        </p>
    </div>

    {{-- Corps --}}
    <div class="p-6 space-y-5">

        {{-- QR Code --}}
        <div class="flex flex-col items-center gap-3">
            <p class="text-sm font-semibold text-gray-700">Votre QR Code de participation</p>

            <div id="qr-wrapper" class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-5 shadow-sm">
                {!! $qrSvg !!}
            </div>

            <p class="text-xs text-gray-400 text-center">Présentez ce QR code à l'accueil pour confirmer votre présence</p>

            {{-- Bouton télécharger --}}
            <button onclick="downloadQR()" type="button"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                       bg-amber-400 hover:bg-amber-300 active:bg-amber-500
                       text-slate-900 font-semibold text-sm tracking-wide
                       shadow-md hover:shadow-amber-300/40 active:scale-[.98]
                       transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Télécharger mon QR code
            </button>
        </div>

        {{-- Récapitulatif --}}
        @if($registration->email || $registration->institution || $registration->type_participant)
        <div class="rounded-xl border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-200">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Récapitulatif</p>
            </div>
            <div class="divide-y divide-gray-100">
                @if($registration->email)
                <div class="flex items-center justify-between px-4 py-3 text-sm">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-900">{{ $registration->email }}</span>
                </div>
                @endif
                @if($registration->institution)
                <div class="flex items-center justify-between px-4 py-3 text-sm">
                    <span class="text-gray-500">Institution</span>
                    <span class="font-medium text-gray-900">{{ $registration->institution }}</span>
                </div>
                @endif
                @if($registration->type_participant)
                <div class="flex items-center justify-between px-4 py-3 text-sm">
                    <span class="text-gray-500">Profil</span>
                    <span class="font-medium text-gray-900">{{ $registration->type_participant }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Détails événement --}}
        @if($event->event_start_date)
        <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-blue-900 text-sm">{{ $event->event_name }}</p>
                <p class="text-blue-600 text-xs mt-0.5">{{ $event->event_start_date->format('d/m/Y') }} – {{ $event->event_end_date?->format('d/m/Y') }}</p>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function downloadQR() {
    const wrapper = document.getElementById('qr-wrapper');
    const svg = wrapper.querySelector('svg');
    if (!svg) return;

    // Forcer une taille carrée lisible
    const SIZE = 400;
    const clone = svg.cloneNode(true);
    clone.setAttribute('width', SIZE);
    clone.setAttribute('height', SIZE);
    clone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');

    const svgData = new XMLSerializer().serializeToString(clone);
    const blob    = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const url     = URL.createObjectURL(blob);

    const img = new Image();
    img.onload = function () {
        const canvas  = document.createElement('canvas');
        canvas.width  = SIZE + 40;   // padding blanc autour
        canvas.height = SIZE + 40;
        const ctx = canvas.getContext('2d');

        // Fond blanc
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // QR centré avec marge
        ctx.drawImage(img, 20, 20, SIZE, SIZE);

        URL.revokeObjectURL(url);

        const a = document.createElement('a');
        a.href     = canvas.toDataURL('image/png');
        a.download = 'qr-inscription-{{ Str::slug($event->event_name) }}.png';
        a.click();
    };
    img.src = url;
}
</script>
@endpush
@endsection
