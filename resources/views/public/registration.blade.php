@extends('layouts.public')
@section('title', 'Inscription – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Formulaire d\'inscription à l\'événement')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Formulaire d'inscription</h2>

    <form method="POST" action="{{ route('public.registration.store', $event->event_slug) }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required class="input-field">
                @error('prenom')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required class="input-field">
                @error('nom')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input-field">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input type="text" name="telephone" value="{{ old('telephone') }}" class="input-field">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Institution / Établissement</label>
            <input type="text" name="institution" value="{{ old('institution') }}" class="input-field">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fonction / Titre</label>
            <input type="text" name="fonction" value="{{ old('fonction') }}" class="input-field">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type de participant</label>
            <select name="type_participant" class="input-field">
                <option value="">– Sélectionner –</option>
                @foreach($participantTypes as $opt)
                    <option value="{{ $opt->value }}" {{ old('type_participant') === $opt->value ? 'selected' : '' }}>
                        {{ $opt->label }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full btn-primary py-3 text-base font-medium">
            Valider mon inscription
        </button>
    </form>
</div>
@endsection
