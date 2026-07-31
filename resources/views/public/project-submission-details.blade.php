@extends('layouts.public')
@section('title', 'Déposer un projet – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Dépôt de projet')
@section('event-badge', 'Étape 2 · Informations')

@section('content')

<div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-base leading-tight">Identité vérifiée</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $identity['type'] === 'etudiant' ? 'Étudiant confirmé via StudentCenter' : 'Personnel confirmé via matricule' }}</p>
        </div>
    </div>

    <div class="px-6 pt-5">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Nom complet</p>
                <p class="font-semibold text-gray-900">{{ trim($identity['prenom'] . ' ' . $identity['nom']) }}</p>
            </div>
            @if($identity['structure_label'])
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Structure (déclarée)</p>
                <p class="font-medium text-gray-800">{{ $identity['structure_label'] }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs text-gray-400 mb-0.5">{{ $identity['type'] === 'etudiant' ? 'N° carte étudiant' : 'Matricule' }}</p>
                <p class="font-medium text-gray-800">{{ $identity['numero_carte'] ?? $identity['matricule'] }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('public.project-submission.details.store', $event->event_slug) }}" class="p-6 space-y-6">
        @csrf

        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Contact</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-input @error('email') border-red-400 bg-red-50 @enderror"
                           placeholder="votre@email.com" autocomplete="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">La confirmation de dépôt sera envoyée à cette adresse</p>
                </div>
                <div>
                    <label class="form-label">Téléphone <span class="text-gray-400 font-normal">(7X XXX XX XX)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-input @error('phone') border-red-400 bg-red-50 @enderror"
                           placeholder="77 000 00 00" maxlength="9" inputmode="numeric">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Structure du projet <span class="text-red-500">*</span></label>
                    <select name="structure_id" required class="form-select @error('structure_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Sélectionner la structure —</option>
                        @foreach($structures as $s)
                        <option value="{{ $s->id }}" {{ old('structure_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}{{ $s->acronym ? ' ('.$s->acronym.')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

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

        <div class="pt-2 border-t border-gray-100">
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95 active:scale-[.99]"
                    style="background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
                Continuer vers le formulaire de soumission
            </button>
        </div>
    </form>
</div>

@endsection
