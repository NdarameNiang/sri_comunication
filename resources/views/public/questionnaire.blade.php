@extends('layouts.public')
@section('title', 'Questionnaire – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', 'Questionnaire d\'appréciation')
@section('event-badge', 'Questionnaire d\'appréciation')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-2">Questionnaire d'appréciation</h2>
    <p class="text-sm text-gray-500 mb-6">Votre avis nous aide à améliorer les prochaines éditions. Ce questionnaire est anonyme.</p>

    <form method="POST" action="{{ route('public.questionnaire.store', $event->event_slug) }}" class="space-y-6">
        @csrf

        {{-- Identité (optionnel) --}}
        <fieldset class="border border-gray-200 rounded-xl p-4 space-y-4">
            <legend class="text-sm font-medium text-gray-700 px-2">Informations (facultatif)</legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" class="input-field">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Institution</label>
                <input type="text" name="institution" value="{{ old('institution') }}" class="input-field">
            </div>
        </fieldset>

        {{-- Notes --}}
        @php
        $ratings = [
            1 => 'Très insatisfait',
            2 => 'Insatisfait',
            3 => 'Moyen',
            4 => 'Satisfait',
            5 => 'Très satisfait',
        ];
        @endphp
        <div class="space-y-5">
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-1">Évaluation <span class="text-red-500">*</span></p>
                <p class="text-xs text-gray-400">Cliquez sur la note qui correspond à votre ressenti pour chaque critère.</p>
            </div>

            @foreach(['note_organisation' => 'Organisation générale', 'note_contenu' => 'Qualité du contenu scientifique', 'note_logistique' => 'Logistique & accueil', 'note_globale' => 'Note globale'] as $field => $label)
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4">
                <p class="text-sm font-semibold text-gray-800 mb-4">{{ $label }} <span class="text-red-500">*</span></p>

                <div class="flex gap-2" data-rating-group="{{ $field }}">
                    @foreach($ratings as $n => $rLabel)
                    @php $checked = old($field) == $n; @endphp
                    <label class="flex-1 flex flex-col items-center gap-1.5 cursor-pointer group" title="{{ $rLabel }}">
                        <input type="radio" name="{{ $field }}" value="{{ $n }}"
                               {{ $checked ? 'checked' : '' }} required class="sr-only">
                        {{-- Cercle numéroté --}}
                        <span class="num-circle w-10 h-10 rounded-full border-2 flex items-center justify-center text-sm font-bold transition-all duration-150 select-none
                            {{ $checked ? 'border-blue-600 bg-blue-600 text-white shadow-md' : 'border-gray-300 text-gray-500' }}">
                            {{ $n }}
                        </span>
                        {{-- Label --}}
                        <span class="rating-lbl text-[10px] text-center leading-tight hidden sm:block {{ $checked ? 'text-blue-600 font-semibold' : 'text-gray-400' }}">
                            {{ $rLabel }}
                        </span>
                    </label>
                    @endforeach
                </div>

                @error($field)<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
            </div>
            @endforeach
        </div>

        {{-- Questions ouvertes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Points positifs</label>
            <textarea name="points_positifs" rows="3" class="input-field resize-none" placeholder="Ce que vous avez apprécié…">{{ old('points_positifs') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Points à améliorer</label>
            <textarea name="points_amelioration" rows="3" class="input-field resize-none" placeholder="Ce qui pourrait être amélioré…">{{ old('points_amelioration') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Suggestions</label>
            <textarea name="suggestions" rows="2" class="input-field resize-none" placeholder="Suggestions pour les prochaines éditions…">{{ old('suggestions') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recommanderiez-vous cet événement ?</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recommanderait" value="1" {{ old('recommanderait') === '1' ? 'checked' : '' }}>
                    <span class="text-sm">Oui</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recommanderait" value="0" {{ old('recommanderait') === '0' ? 'checked' : '' }}>
                    <span class="text-sm">Non</span>
                </label>
            </div>
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white font-bold text-base shadow-md hover:shadow-lg transition-all duration-200 hover:opacity-95"
                style="background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
            </svg>
            Soumettre mon questionnaire
        </button>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-rating-group]').forEach(function(group) {
    group.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            group.querySelectorAll('label').forEach(function(lbl) {
                var circle = lbl.querySelector('.num-circle');
                var lbl2   = lbl.querySelector('.rating-lbl');
                circle.classList.remove('border-blue-600','bg-blue-600','text-white','shadow-md');
                circle.classList.add('border-gray-300','text-gray-500');
                if (lbl2) { lbl2.classList.remove('text-blue-600','font-semibold'); lbl2.classList.add('text-gray-400'); }
            });
            var sel    = radio.closest('label');
            var circle = sel.querySelector('.num-circle');
            var lbl2   = sel.querySelector('.rating-lbl');
            circle.classList.remove('border-gray-300','text-gray-500');
            circle.classList.add('border-blue-600','bg-blue-600','text-white','shadow-md');
            if (lbl2) { lbl2.classList.remove('text-gray-400'); lbl2.classList.add('text-blue-600','font-semibold'); }
        });
    });
});
</script>
@endpush
@endsection
