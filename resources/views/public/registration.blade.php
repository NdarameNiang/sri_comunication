@extends('layouts.public')
@section('title', 'Inscription – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Inscription des participants')
@section('event-badge', 'Formulaire d\'inscription')

@section('content')

<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

    {{-- En-tête formulaire --}}
    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-base leading-tight">Vos informations</h2>
            <p class="text-xs text-gray-400 mt-0.5">Les champs marqués <span class="text-red-500 font-semibold">*</span> sont obligatoires</p>
        </div>
    </div>

    <form method="POST" action="{{ route('public.registration.store', $event->event_slug) }}" class="p-6 space-y-6">
        @csrf

        {{-- Section : Identité --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Identité</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                           class="form-input @error('prenom') border-red-400 bg-red-50 @enderror"
                           placeholder="Votre prénom">
                    @error('prenom') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           class="form-input @error('nom') border-red-400 bg-red-50 @enderror"
                           placeholder="Votre nom de famille">
                    @error('nom') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section : Contact --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Contact</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') border-red-400 bg-red-50 @enderror"
                           placeholder="votre@email.com" autocomplete="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Confirmer l'email</label>
                    <input type="email" name="email_confirmation" value="{{ old('email_confirmation') }}"
                           class="form-input @error('email_confirmation') border-red-400 bg-red-50 @enderror"
                           placeholder="votre@email.com" autocomplete="off">
                    @error('email_confirmation') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">
                        Téléphone
                        <span class="text-gray-400 font-normal ml-1">(7X XXX XX XX)</span>
                    </label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                           class="form-input @error('telephone') border-red-400 bg-red-50 @enderror"
                           placeholder="77 000 00 00" maxlength="9" inputmode="numeric">
                    @error('telephone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section : Professionnel --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Affiliation professionnelle</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Institution / Établissement</label>
                    <input type="text" name="institution" value="{{ old('institution') }}"
                           class="form-input" placeholder="UCAD, ISED, autre…">
                </div>
                <div>
                    <label class="form-label">Fonction / Titre</label>
                    <input type="text" name="fonction" value="{{ old('fonction') }}"
                           class="form-input" placeholder="Enseignant-chercheur, Étudiant…">
                </div>
            </div>
        </div>

        {{-- Section : Type participant --}}
        <div>
            <label class="form-label">Type de participant</label>
            <select name="type_participant" class="form-select @error('type_participant') border-red-400 bg-red-50 @enderror">
                <option value="">— Sélectionner votre profil —</option>
                @foreach($participantTypes as $opt)
                <option value="{{ $opt->value }}" {{ old('type_participant') === $opt->value ? 'selected' : '' }}>
                    {{ $opt->label }}
                </option>
                @endforeach
            </select>
            @error('type_participant') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Bouton --}}
        <div class="pt-2 border-t border-gray-100">
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95 active:scale-[.99]"
                    style="background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Confirmer mon inscription
            </button>
            <p class="text-xs text-gray-400 text-center mt-2.5">
                Un QR code de confirmation vous sera délivré immédiatement
            </p>
        </div>
    </form>
</div>

@endsection
