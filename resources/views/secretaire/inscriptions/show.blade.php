@extends('layouts.app')
@section('title', 'Inscription – ' . $registration->fullName())
@section('page-title', $registration->fullName())
@section('page-subtitle', 'Détail inscription')

@section('content')
<div class="max-w-2xl space-y-4">
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Nom complet</p>
                <p class="font-medium">{{ $registration->fullName() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p>{{ $registration->email ?? '–' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                <p>{{ $registration->telephone ?? '–' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Institution</p>
                <p>{{ $registration->institution ?? '–' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Fonction</p>
                <p>{{ $registration->fonction ?? '–' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Type de participant</p>
                <p>{{ $registration->type_participant ?? '–' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Présence</p>
                <p>
                    @if($registration->presence_confirmee)
                        <span class="badge-green">Confirmée</span>
                    @else
                        <span class="badge-gray">Non confirmée</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">QR Code (token)</p>
                <p class="text-xs font-mono text-gray-600 break-all">{{ $registration->qr_code }}</p>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <form method="POST" action="{{ route('secretaire.inscriptions.presence', $registration) }}">
            @csrf @method('PATCH')
            <button class="btn-primary text-sm">
                {{ $registration->presence_confirmee ? 'Marquer absent' : 'Confirmer présence' }}
            </button>
        </form>
        <a href="{{ route('secretaire.inscriptions.index') }}" class="btn-secondary text-sm">Retour</a>
    </div>
</div>
@endsection
