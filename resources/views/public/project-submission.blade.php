@extends('layouts.public')
@section('title', 'Soumettre un projet – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Soumission de projet')
@section('event-badge', 'Nouveau dossier')

@section('content')

<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

    {{-- En-tête formulaire --}}
    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-base leading-tight">Créer mon dossier de soumission</h2>
            <p class="text-xs text-gray-400 mt-0.5">Un espace porteur vous est créé automatiquement — les champs marqués <span class="text-red-500 font-semibold">*</span> sont obligatoires</p>
        </div>
    </div>

    <form method="POST" action="{{ route('public.project-submission.store', $event->event_slug) }}" class="p-6 space-y-6">
        @csrf

        {{-- Section : Identité --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Identité</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-input @error('name') border-red-400 bg-red-50 @enderror"
                           placeholder="Prénom et nom">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-input @error('email') border-red-400 bg-red-50 @enderror"
                           placeholder="votre@email.com" autocomplete="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Vos identifiants de connexion seront envoyés à cette adresse</p>
                </div>
                <div>
                    <label class="form-label">
                        Téléphone
                        <span class="text-gray-400 font-normal ml-1">(7X XXX XX XX)</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-input @error('phone') border-red-400 bg-red-50 @enderror"
                           placeholder="77 000 00 00" maxlength="9" inputmode="numeric">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Structure / Établissement <span class="text-red-500">*</span></label>
                    <select name="structure_id" required class="form-select @error('structure_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Sélectionner votre structure —</option>
                        @foreach($structures as $s)
                        <option value="{{ $s->id }}" {{ old('structure_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}{{ $s->acronym ? ' ('.$s->acronym.')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section : Catégorie --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Votre catégorie</p>
            <div>
                <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                <select name="population_category" id="population_category" required
                        class="form-select @error('population_category') border-red-400 bg-red-50 @enderror" onchange="toggleNumeroCarte()">
                    <option value="">— Sélectionner votre catégorie —</option>
                    @foreach($populationCategories as $opt)
                    <option value="{{ $opt->value }}" {{ old('population_category') === $opt->value ? 'selected' : '' }}>{{ $opt->label }}</option>
                    @endforeach
                </select>
                @error('population_category') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div id="numero-carte-field" class="hidden mt-4">
                <label class="form-label">Numéro de carte étudiant <span class="text-red-500">*</span></label>
                <input type="text" name="numero_carte" value="{{ old('numero_carte') }}"
                       class="form-input @error('numero_carte') border-red-400 bg-red-50 @enderror"
                       placeholder="Ex : 1995000VG">
                @error('numero_carte') <p class="form-error">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Vérifié automatiquement auprès de la base StudentCenter</p>
            </div>
        </div>

        {{-- Section : Projet --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Votre projet</p>
            <div>
                <label class="form-label">Titre du projet <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="form-input @error('title') border-red-400 bg-red-50 @enderror"
                       placeholder="Titre provisoire — modifiable ensuite">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section : Format de soumission --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Format de soumission <span class="text-red-500">*</span></p>
            @error('submission_template') <p class="form-error mb-2">{{ $message }}</p> @enderror
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex flex-col p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="submission_template" value="standard" class="absolute top-4 right-4" {{ old('submission_template', 'standard') === 'standard' ? 'checked' : '' }}>
                    <span class="text-sm font-bold text-gray-900 mb-1">Standard</span>
                    <span class="text-xs text-gray-500 leading-relaxed pr-6">Pour la majorité des projets : résumé, problématique, solution, résultats attendus.</span>
                </label>
                <label class="relative flex flex-col p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-indigo-300 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="radio" name="submission_template" value="approfondi" class="absolute top-4 right-4" {{ old('submission_template') === 'approfondi' ? 'checked' : '' }}>
                    <span class="text-sm font-bold text-gray-900 mb-1">Approfondi</span>
                    <span class="text-xs text-gray-500 leading-relaxed pr-6">Pour les projets à fort potentiel de valorisation (prototype testé, brevet, start-up…).</span>
                </label>
            </div>
        </div>

        {{-- Bouton --}}
        <div class="pt-2 border-t border-gray-100">
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95 active:scale-[.99]"
                    style="background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
                Créer mon espace et continuer
            </button>
            <p class="text-xs text-gray-400 text-center mt-2.5">
                Déjà un compte ? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium underline underline-offset-2">Connectez-vous</a> pour retrouver vos projets.
            </p>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleNumeroCarte() {
        const select = document.getElementById('population_category');
        const field  = document.getElementById('numero-carte-field');
        if (!select || !field) return;
        const isStudent = ['etudiant_licence', 'etudiant_master', 'etudiant_doctorat'].includes(select.value);
        field.classList.toggle('hidden', !isStudent);
    }
    toggleNumeroCarte();
</script>
@endpush

@endsection
