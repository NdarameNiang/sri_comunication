@extends('layouts.public')
@section('title', 'Déposer un projet – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Dépôt de projet')
@section('event-badge', 'Étape 1 · Identification')

@section('content')

@if(isset($closedMessage))
<div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
    </svg>
    <h3 class="font-bold text-slate-800 text-lg mb-1">Dépôt fermé</h3>
    <p class="text-slate-600 text-sm">{{ $closedMessage }}</p>
</div>
@else
<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-base leading-tight">Qui êtes-vous ?</h2>
            <p class="text-xs text-gray-400 mt-0.5">Aucun compte n'est nécessaire — nous vérifions simplement votre identité</p>
        </div>
    </div>

    <form method="POST" action="{{ route('public.project-submission.verify', $event->event_slug) }}" class="p-6 space-y-6">
        @csrf

        @unless($requireVerification)
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
            <p class="text-xs text-gray-400 mt-2">Le numéro de carte / matricule ci-dessous reste optionnel.</p>
        </div>
        @endunless

        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Vous êtes</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex flex-col p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="profile_type" value="etudiant" class="absolute top-4 right-4" onchange="toggleProfile()" {{ old('profile_type', 'etudiant') === 'etudiant' ? 'checked' : '' }}>
                    <span class="text-sm font-bold text-gray-900 mb-1">Étudiant</span>
                    <span class="text-xs text-gray-500 leading-relaxed pr-6">Vérification via votre numéro de carte d'étudiant (StudentCenter)</span>
                </label>
                <label class="relative flex flex-col p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-indigo-300 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="radio" name="profile_type" value="personnel" class="absolute top-4 right-4" onchange="toggleProfile()" {{ old('profile_type') === 'personnel' ? 'checked' : '' }}>
                    <span class="text-sm font-bold text-gray-900 mb-1">Personnel (PER / PATS)</span>
                    <span class="text-xs text-gray-500 leading-relaxed pr-6">Vérification via votre matricule</span>
                </label>
            </div>
        </div>

        {{-- Bloc étudiant --}}
        <div id="bloc-etudiant" class="space-y-4">
            <div>
                <label class="form-label">Cycle <span class="text-red-500">*</span></label>
                <select name="cycle" class="form-select @error('cycle') border-red-400 bg-red-50 @enderror">
                    <option value="">— Sélectionner votre cycle —</option>
                    @foreach($cycleOptions as $opt)
                    <option value="{{ $opt->value }}" {{ old('cycle') === $opt->value ? 'selected' : '' }}>{{ $opt->label }}</option>
                    @endforeach
                </select>
                @error('cycle') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">CIN <span class="text-gray-400 font-normal">(optionnel)</span></label>
                <input type="text" name="cin" value="{{ old('cin') }}" class="form-input" placeholder="Numéro de la carte d'identité nationale">
            </div>
            <div>
                <label class="form-label">
                    Numéro de carte étudiant
                    @if($requireVerification)<span class="text-red-500">*</span>@else <span class="text-gray-400 font-normal">(optionnel)</span>@endif
                </label>
                <input type="text" name="numero_carte" value="{{ old('numero_carte') }}"
                       class="form-input @error('numero_carte') border-red-400 bg-red-50 @enderror"
                       placeholder="Ex : 1995000VG">
                @error('numero_carte') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Bloc personnel --}}
        <div id="bloc-personnel" class="space-y-4 hidden">
            <div>
                <label class="form-label">Catégorie @if($requireVerification)<span class="text-red-500">*</span>@endif</label>
                <select name="categorie" class="form-select @error('categorie') border-red-400 bg-red-50 @enderror">
                    <option value="">— Sélectionner —</option>
                    <option value="per" {{ old('categorie') === 'per' ? 'selected' : '' }}>PER (Personnel Enseignant et de Recherche)</option>
                    <option value="pats" {{ old('categorie') === 'pats' ? 'selected' : '' }}>PATS (Personnel Administratif, Technique et de Service)</option>
                </select>
                @error('categorie') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">
                    Matricule
                    @if($requireVerification)<span class="text-red-500">*</span>@else <span class="text-gray-400 font-normal">(optionnel)</span>@endif
                </label>
                <input type="text" name="matricule" value="{{ old('matricule') }}"
                       class="form-input @error('matricule') border-red-400 bg-red-50 @enderror"
                       placeholder="Ex : PER-0001">
                @error('matricule') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-2 border-t border-gray-100">
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95 active:scale-[.99]"
                    style="background: linear-gradient(135deg, var(--brand-primary, #1d4ed8) 0%, color-mix(in srgb, var(--brand-primary, #1d4ed8), black 25%) 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
                Vérifier mon identité et continuer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleProfile() {
        const type = document.querySelector('input[name="profile_type"]:checked')?.value;
        document.getElementById('bloc-etudiant').classList.toggle('hidden', type !== 'etudiant');
        document.getElementById('bloc-personnel').classList.toggle('hidden', type !== 'personnel');
    }
    toggleProfile();
</script>
@endpush
@endif

@endsection
