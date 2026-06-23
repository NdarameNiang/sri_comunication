@extends('layouts.app')
@section('title', 'Nouveau porteur de projet')
@section('page-title', 'Créer un porteur de projet')
@section('page-subtitle', 'Assigner des projets à une structure')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Info règle --}}
    <div class="alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <strong>Règle de capacité :</strong> Chaque structure peut accueillir <strong>au maximum 5 projets</strong> au total.
            Le porteur peut se voir assigner 1 à 5 projets, dans la limite des places disponibles pour sa structure.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations du porteur</h3>
            <a href="{{ route('direction.porteurs.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('direction.porteurs.store') }}" class="space-y-6" id="porteurForm">
                @csrf

                {{-- Infos personnelles --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Informations personnelles</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" required placeholder="Prénom Nom">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Téléphone <span class="text-xs text-gray-400">(7X XXX XX XX)</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') border-red-400 @enderror" placeholder="77 000 00 00" maxlength="9">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Email institutionnel <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-400 font-normal">(utilisé pour la connexion)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" required placeholder="prenom.nom@ucad.edu.sn">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Email personnel
                                <span class="text-xs text-gray-400 font-normal">(optionnel – email de secours)</span>
                            </label>
                            <input type="email" name="email_personnel" value="{{ old('email_personnel') }}" class="form-input @error('email_personnel') border-red-400 @enderror" placeholder="prenom.nom@gmail.com">
                            @error('email_personnel') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                            <input type="password" name="password" class="form-input @error('password') border-red-400 @enderror" required placeholder="Min. 8 caractères">
                            @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input" required placeholder="Retapez le mot de passe">
                        </div>
                    </div>
                </div>

                {{-- Structure --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Structure de rattachement</h4>
                    <div>
                        <label class="form-label">Structure <span class="text-red-500">*</span></label>
                        <select name="structure_id" id="structure_select" class="form-select @error('structure_id') border-red-400 @enderror" required onchange="updateSlots(this)">
                            <option value="">-- Sélectionner une structure --</option>
                            @foreach($structures as $structure)
                            @php
                                $remaining = 5 - $structure->project_assignments_count;
                                $disabled = $remaining <= 0;
                            @endphp
                            <option value="{{ $structure->id }}"
                                    data-remaining="{{ $remaining }}"
                                    {{ old('structure_id') == $structure->id ? 'selected' : '' }}
                                    {{ $disabled ? 'disabled' : '' }}>
                                [{{ $structure->acronym }}] {{ $structure->name }}
                                ({{ $remaining > 0 ? $remaining . ' place(s) disponible(s)' : 'Complet' }})
                            </option>
                            @endforeach
                        </select>
                        @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror

                        <p id="slots-info" class="text-xs text-gray-500 mt-1.5 hidden">
                            <span id="slots-count"></span> place(s) disponible(s) pour cette structure.
                        </p>
                    </div>
                </div>

                {{-- Titres de projets --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 pb-2 border-b border-gray-100">Titres des projets assignés</h4>
                    <p class="text-xs text-gray-500 mb-4">Saisissez le libellé de chaque projet que ce porteur devra renseigner. Minimum 1, maximum selon les places disponibles.</p>

                    @error('titles')
                        <div class="alert-error mb-4">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    @error('titles.*')
                        <div class="alert-error mb-4">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <div id="titles-container" class="space-y-3">
                        @php $oldTitles = old('titles', ['']); @endphp
                        @foreach($oldTitles as $i => $title)
                        <div class="flex items-start gap-2 title-row">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center shrink-0 mt-2.5">
                                <span class="text-blue-700 text-xs font-semibold title-num">{{ $i + 1 }}</span>
                            </div>
                            <input type="text" name="titles[]" value="{{ $title }}" class="form-input flex-1" placeholder="Titre du projet #{{ $i + 1 }}" required>
                            @if($i > 0)
                            <button type="button" onclick="removeTitle(this)" class="mt-2.5 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-title-btn" onclick="addTitle()" class="mt-3 btn-secondary text-sm">
                        @include('components.icon', ['name' => 'plus'])
                        Ajouter un projet
                    </button>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" id="submit-btn" class="btn-primary">
                        <svg id="submit-icon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <svg id="submit-spinner" class="w-4 h-4 hidden animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span id="submit-label">Créer le porteur et assigner les projets</span>
                    </button>
                    <a href="{{ route('direction.porteurs.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let maxTitles = 5;

function updateSlots(select) {
    const opt = select.options[select.selectedIndex];
    const remaining = parseInt(opt.dataset.remaining || 0);
    maxTitles = remaining;

    const info = document.getElementById('slots-info');
    const count = document.getElementById('slots-count');

    if (select.value) {
        info.classList.remove('hidden');
        count.textContent = remaining;
        info.className = remaining > 0 ? 'text-xs text-emerald-600 mt-1.5' : 'text-xs text-red-500 mt-1.5';

        // Remove extra title rows if over new max
        const rows = document.querySelectorAll('.title-row');
        rows.forEach((row, i) => {
            if (i >= remaining) row.remove();
        });
        updateAddBtn();
    } else {
        info.classList.add('hidden');
    }
}

function addTitle() {
    const rows = document.querySelectorAll('.title-row');
    if (rows.length >= maxTitles) {
        alert(`Cette structure ne peut accueillir que ${maxTitles} projet(s).`);
        return;
    }
    const container = document.getElementById('titles-container');
    const idx = rows.length + 1;
    const div = document.createElement('div');
    div.className = 'flex items-start gap-2 title-row';
    div.innerHTML = `
        <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center shrink-0 mt-2.5">
            <span class="text-blue-700 text-xs font-semibold title-num">${idx}</span>
        </div>
        <input type="text" name="titles[]" class="form-input flex-1" placeholder="Titre du projet #${idx}" required>
        <button type="button" onclick="removeTitle(this)" class="mt-2.5 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(div);
    updateAddBtn();
}

function removeTitle(btn) {
    btn.closest('.title-row').remove();
    // Renumber
    document.querySelectorAll('.title-num').forEach((span, i) => {
        span.textContent = i + 1;
    });
    updateAddBtn();
}

function updateAddBtn() {
    const rows = document.querySelectorAll('.title-row');
    const btn = document.getElementById('add-title-btn');
    btn.disabled = rows.length >= maxTitles;
    btn.classList.toggle('opacity-50', rows.length >= maxTitles);
}

// Anti double-soumission
document.querySelector('form').addEventListener('submit', function () {
    const btn    = document.getElementById('submit-btn');
    const icon   = document.getElementById('submit-icon');
    const spinner = document.getElementById('submit-spinner');
    const label  = document.getElementById('submit-label');

    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    icon.classList.add('hidden');
    spinner.classList.remove('hidden');
    label.textContent = 'Création en cours…';
});
</script>
@endsection
