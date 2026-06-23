@extends('layouts.public')
@section('title', 'Inscription – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Inscription des participants')
@section('event-badge', 'Formulaire d\'inscription')

@section('content')

{{-- Intro --}}
<div class="text-center mb-6">
    <p class="text-gray-500 text-sm max-w-lg mx-auto">
        Remplissez ce formulaire pour vous inscrire à <strong>{{ $event->event_name }}</strong>.
        Vous recevrez un QR code de confirmation à présenter lors de l'événement.
    </p>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden">

    {{-- En-tête formulaire --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
        <h2 class="text-white font-semibold text-base">Vos informations</h2>
        <p class="text-blue-200 text-xs mt-0.5">Tous les champs marqués * sont obligatoires</p>
    </div>

    <form method="POST" action="{{ route('public.registration.store', $event->event_slug) }}" class="p-6 space-y-5">
        @csrf

        {{-- Identité --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Prénom <span class="text-red-500">*</span></label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required
                       class="form-input @error('prenom') border-red-400 @enderror"
                       placeholder="Votre prénom">
                @error('prenom') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="form-input @error('nom') border-red-400 @enderror"
                       placeholder="Votre nom de famille">
                @error('nom') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Contact --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-input @error('email') border-red-400 @enderror"
                       placeholder="votre@email.com">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Téléphone <span class="text-xs text-gray-400">(7X XXX XX XX)</span></label>
                <input type="text" name="telephone" value="{{ old('telephone') }}"
                       class="form-input @error('telephone') border-red-400 @enderror"
                       placeholder="77 000 00 00" maxlength="9">
                @error('telephone') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Professionnel --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Institution / Établissement</label>
                <input type="text" name="institution" value="{{ old('institution') }}"
                       class="form-input" placeholder="UCAD, ISED, autre...">
            </div>
            <div>
                <label class="form-label">Fonction / Titre</label>
                <input type="text" name="fonction" value="{{ old('fonction') }}"
                       class="form-input" placeholder="Enseignant-chercheur, Étudiant...">
            </div>
        </div>

        {{-- Type participant --}}
        <div>
            <label class="form-label">Type de participant</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                @foreach($participantTypes as $opt)
                <label class="flex items-center gap-2 p-3 rounded-xl border {{ old('type_participant') === $opt->value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                    <input type="radio" name="type_participant" value="{{ $opt->value }}"
                           {{ old('type_participant') === $opt->value ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600">
                    <span class="text-sm font-medium text-gray-700">{{ $opt->label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                    style="background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);"
                    class="w-full text-white font-semibold py-3.5 px-6 rounded-xl hover:opacity-90 transition-opacity text-base shadow-md">
                Confirmer mon inscription →
            </button>
            <p class="text-xs text-gray-400 text-center mt-2">Vous recevrez un QR code de confirmation</p>
        </div>
    </form>
</div>
@endsection
