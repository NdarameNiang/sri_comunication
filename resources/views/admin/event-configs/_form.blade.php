{{-- Informations générales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="form-label">Nom de l'événement <span class="text-red-500">*</span></label>
        <input type="text" name="event_name" value="{{ old('event_name', $eventConfig->event_name ?? '') }}"
               class="form-input @error('event_name') border-red-400 @enderror"
               placeholder="Ex: SRI 2026, MMA 2026…" required>
        @error('event_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Slug (identifiant URL) <span class="text-red-500">*</span></label>
        <input type="text" name="event_slug" value="{{ old('event_slug', $eventConfig->event_slug ?? '') }}"
               class="form-input font-mono @error('event_slug') border-red-400 @enderror"
               placeholder="sri-2026" pattern="[a-z0-9\-]+" required>
        <p class="text-xs text-gray-400 mt-1">Minuscules et tirets · utilisé dans les URLs publiques</p>
        @error('event_slug') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Organisateur</label>
        <input type="text" name="organizer" value="{{ old('organizer', $eventConfig->organizer ?? '') }}"
               class="form-input" placeholder="Ex : Direction de la Recherche – UCAD">
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Description</label>
        <textarea name="event_description" rows="3"
                  class="form-input resize-none"
                  placeholder="Brève description de l'événement…">{{ old('event_description', $eventConfig->event_description ?? '') }}</textarea>
    </div>
</div>

{{-- Dates de l'événement --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Dates de l'événement</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Date de début</label>
            <input type="date" name="event_start_date"
                   value="{{ old('event_start_date', isset($eventConfig) ? $eventConfig->event_start_date?->format('Y-m-d') : '') }}"
                   class="form-input">
        </div>
        <div>
            <label class="form-label">Date de fin</label>
            <input type="date" name="event_end_date"
                   value="{{ old('event_end_date', isset($eventConfig) ? $eventConfig->event_end_date?->format('Y-m-d') : '') }}"
                   class="form-input">
        </div>
    </div>
</div>

{{-- Règles de dépôt --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Règles de dépôt des projets</p>
    <div class="sm:w-1/2 sm:pr-2">
        <label class="form-label">Nombre maximum de projets par structure <span class="text-red-500">*</span></label>
        <input type="number" min="1" name="max_projects_per_structure"
               value="{{ old('max_projects_per_structure', $eventConfig->max_projects_per_structure ?? 500) }}"
               class="form-input @error('max_projects_per_structure') border-red-400 @enderror" required>
        <p class="text-xs text-gray-400 mt-1">Nombre de projets qu'une même structure peut soumettre au total pour cet événement</p>
        @error('max_projects_per_structure') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Options publiques --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Options de la page publique</p>
    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
        <div class="relative inline-flex items-center">
            <input type="hidden" name="show_questionnaire" value="0">
            <input type="checkbox" name="show_questionnaire" value="1"
                   id="show_questionnaire"
                   {{ old('show_questionnaire', $eventConfig->show_questionnaire ?? false) ? 'checked' : '' }}
                   class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Afficher le bouton questionnaire</p>
            <p class="text-xs text-gray-400 mt-0.5">Rend le bouton questionnaire visible sur la page d'accueil publique</p>
        </div>
    </label>
</div>

{{-- Période de soumission --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Période de soumission des projets</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Ouverture</label>
            <input type="datetime-local" name="submission_open_at"
                   value="{{ old('submission_open_at', isset($eventConfig) ? $eventConfig->submission_open_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Les porteurs pourront soumettre à partir de cette date</p>
        </div>
        <div>
            <label class="form-label">Clôture</label>
            <input type="datetime-local" name="submission_close_at"
                   value="{{ old('submission_close_at', isset($eventConfig) ? $eventConfig->submission_close_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Plus aucune soumission après cette date</p>
        </div>
    </div>
</div>

{{-- Soumission publique --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Soumission de projet sans compte</p>
    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
        <div class="relative inline-flex items-center">
            <input type="hidden" name="allow_public_submission" value="0">
            <input type="checkbox" name="allow_public_submission" value="1"
                   id="allow_public_submission"
                   {{ old('allow_public_submission', $eventConfig->allow_public_submission ?? false) ? 'checked' : '' }}
                   class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Autoriser la soumission publique de projet</p>
            <p class="text-xs text-gray-400 mt-0.5">Un visiteur sans compte pourra créer son propre dossier depuis la page publique (un compte porteur lui est créé automatiquement, avec vérification de sa catégorie via l'API StudentCenter s'il se déclare étudiant).</p>
        </div>
    </label>
</div>

{{-- Vérification d'identité --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Vérification d'identité (inscription & soumission)</p>
    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
        <div class="relative inline-flex items-center">
            <input type="hidden" name="require_identity_verification" value="0">
            <input type="checkbox" name="require_identity_verification" value="1"
                   id="require_identity_verification"
                   {{ old('require_identity_verification', $eventConfig->require_identity_verification ?? true) ? 'checked' : '' }}
                   class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Exiger une vérification (numéro de carte / matricule)</p>
            <p class="text-xs text-gray-400 mt-0.5">Activé : le numéro de carte étudiant ou le matricule doit correspondre à la base StudentCenter/Personnel pour s'inscrire ou soumettre un projet. Désactivé : inscription et soumission libres, ouvertes à tous sans vérification (le numéro de carte reste demandable mais optionnel).</p>
        </div>
    </label>
</div>

{{-- Branding --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Image de marque de l'événement</p>
    <p class="text-xs text-gray-400 mb-3">Facultatif — sans rien renseigner, l'événement garde l'apparence UCAD par défaut (logo, photo, couleur bleue).</p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        @foreach([
            ['field' => 'logo', 'label' => 'Logo (couleur)', 'current' => $eventConfig->logo_path ?? null, 'url' => $eventConfig->logo_url ?? null],
            ['field' => 'logo_white', 'label' => 'Logo (blanc, fonds sombres)', 'current' => $eventConfig->logo_white_path ?? null, 'url' => $eventConfig->logo_white_url ?? null],
            ['field' => 'hero_image', 'label' => 'Photo de fond (pages publiques)', 'current' => $eventConfig->hero_image_path ?? null, 'url' => $eventConfig->hero_image_url ?? null],
        ] as $img)
        <div>
            <label class="form-label">{{ $img['label'] }}</label>
            @if($img['current'])
            <div class="mb-2 rounded-lg overflow-hidden border border-gray-200 {{ $img['field'] === 'logo_white' ? 'bg-slate-800' : '' }}">
                <img src="{{ $img['url'] }}" alt="{{ $img['label'] }}" class="w-full h-20 object-contain">
            </div>
            <label class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <input type="checkbox" name="remove_{{ $img['field'] }}" value="1" class="rounded">
                Supprimer l'image actuelle
            </label>
            @endif
            <input type="file" name="{{ $img['field'] }}" accept="image/*" class="form-input text-xs">
            @error($img['field']) <p class="form-error">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>

    <div class="sm:w-1/3">
        <label class="form-label">Couleur principale</label>
        <div class="flex items-center gap-2">
            <input type="color" name="primary_color_picker"
                   value="{{ old('primary_color', $eventConfig->primary_color ?? '#2563eb') }}"
                   class="h-10 w-14 rounded border border-gray-200 cursor-pointer"
                   onchange="document.getElementById('primary_color_hex').value = this.value">
            <input type="text" id="primary_color_hex" name="primary_color"
                   value="{{ old('primary_color', $eventConfig->primary_color ?? '#2563eb') }}"
                   class="form-input font-mono text-sm @error('primary_color') border-red-400 @enderror"
                   pattern="^#[0-9a-fA-F]{6}$" placeholder="#2563eb"
                   oninput="this.previousElementSibling.value = /^#[0-9a-fA-F]{6}$/.test(this.value) ? this.value : this.previousElementSibling.value">
        </div>
        <p class="text-xs text-gray-400 mt-1">Utilisée sur les pages publiques (boutons, onglets actifs) — pas dans les tableaux de bord internes.</p>
        @error('primary_color') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Période d'inscription --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Période d'inscription des participants</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Ouverture des inscriptions</label>
            <input type="datetime-local" name="inscription_open_at"
                   value="{{ old('inscription_open_at', isset($eventConfig) ? $eventConfig->inscription_open_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Les participants pourront s'inscrire à partir de cette date</p>
        </div>
        <div>
            <label class="form-label">Clôture des inscriptions</label>
            <input type="datetime-local" name="inscription_close_at"
                   value="{{ old('inscription_close_at', isset($eventConfig) ? $eventConfig->inscription_close_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Plus aucune inscription acceptée après cette date</p>
        </div>
    </div>
</div>

{{-- Public autorisé à l'inscription --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Public autorisé à s'inscrire</p>
    <p class="text-xs text-gray-400 mb-3">Ne cochez rien pour laisser l'inscription ouverte à tous (comportement par défaut).</p>
    @php
        $selectedAudienceIds = old('audience_category_ids', isset($eventConfig) ? $eventConfig->audienceCategories->pluck('id')->all() : []);
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @forelse($audienceCategories as $opt)
        <label class="flex items-center gap-2.5 p-2.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50">
            <input type="checkbox" name="audience_category_ids[]" value="{{ $opt->id }}"
                   {{ in_array($opt->id, $selectedAudienceIds) ? 'checked' : '' }} class="rounded">
            <span class="text-sm text-gray-700">{{ $opt->label }}</span>
        </label>
        @empty
        <p class="text-xs text-gray-400 italic">Aucune catégorie définie. Ajoutez-en dans Options formulaires → Catégories de population.</p>
        @endforelse
    </div>
</div>
