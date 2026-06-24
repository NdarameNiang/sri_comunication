@php
    $adminMode   = $adminMode ?? false;
    $readonly    = $project?->isSubmitted() && !$adminMode;
    $assignment  = $assignment ?? $project?->assignment;
    $backRoute   = $adminMode ? route('superadmin.projects.index') : route('porteur.dashboard');

    if ($adminMode) {
        $action = $adminAction ?? ($project
            ? route('superadmin.projects.update', $project)
            : route('superadmin.assignments.store-fill', $assignment));
        $method = $adminMethod ?? ($project ? 'PUT' : 'POST');
    } else {
        $action = $project
            ? route('porteur.projects.update', $project)
            : route('porteur.projects.store', $assignment);
        $method = $project ? 'PUT' : 'POST';
    }

    // Détecter le step concerné par les erreurs de validation
    $errorFields = $errors->keys();
    $stepErrors = [
        1 => ['responsable_nom','contact_email','email_professionnel','contact_phone'],
        2 => ['scientific_domain','project_types'],
        3 => ['summary','problematic','solution','results'],
        4 => ['protection_types','valorisation_types','impact_types'],
        5 => ['presentation_formats','logistic_needs'],
        6 => array_filter($errorFields, fn($k) => str_starts_with($k, 'collaborateurs')),
    ];
    $firstErrorStep = 0;
    foreach ($stepErrors as $stepNum => $fields) {
        foreach ($fields as $f) {
            if ($errors->has($f)) { $firstErrorStep = $stepNum; break 2; }
        }
    }
@endphp

{{-- Bannière admin --}}
@if($adminMode)
<div class="flex items-center gap-2.5 p-3 bg-violet-50 border border-violet-200 rounded-xl text-sm text-violet-800 mb-4">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
    <span><strong>Mode superadmin</strong> — modification directe, restrictions de statut levées.</span>
</div>
@endif

@if($readonly)
<div class="alert-info mb-4">
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>Ce projet a été soumis et est en lecture seule.</span>
</div>
@endif

<form method="POST" action="{{ $action }}" id="project-form" class="space-y-0">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    {{-- Progression --}}
    @if(!$readonly)
    <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 mb-4" id="stepper-header">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Étape <span id="step-current">1</span> sur 7</span>
            <span class="text-xs text-gray-400" id="step-label-top">Informations générales</span>
        </div>
        <div class="flex gap-1.5">
            @foreach([1,2,3,4,5,6,7] as $s)
            <div class="step-dot h-1.5 flex-1 rounded-full bg-gray-200 transition-all duration-300" data-step="{{ $s }}"></div>
            @endforeach
        </div>
        <div class="flex justify-between mt-3 text-xs text-gray-400">
            <span>Infos</span><span>Identification</span><span>Description</span><span>Valorisation</span><span>Présentation</span><span>Collabs</span><span>Récap</span>
        </div>
    </div>
    @endif

    {{-- ===== ÉTAPE 1 : Informations générales ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="1" data-label="Informations générales">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">1</div>
                    <h3 class="section-title text-base">Informations générales</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Structure</p>
                            <p class="font-semibold text-gray-900">{{ $assignment->structure->name }} ({{ $assignment->structure->acronym }})</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-0.5">Titre du projet</p>
                            <p class="font-semibold text-gray-900">{{ $assignment->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Responsable du projet <span class="text-red-500">*</span></label>
                        <input type="text" name="responsable_nom" value="{{ old('responsable_nom', $project?->responsable_nom) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required placeholder="Nom complet">
                        @error('responsable_nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email institutionnel <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $project?->contact_email ?? auth()->user()->email) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        <p class="text-xs text-gray-400 mt-1">Adresse @ucad.edu.sn de connexion</p>
                        @error('contact_email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email personnel</label>
                        <input type="email" name="email_professionnel" value="{{ old('email_professionnel', $project?->email_professionnel) }}"
                               placeholder="ex : prenom.nom@gmail.com" class="form-input" {{ $readonly ? 'disabled' : '' }}>
                        <p class="text-xs text-gray-400 mt-1">Adresse personnelle si différente de l'email UCAD</p>
                        @error('email_professionnel') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone de contact</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $project?->contact_phone ?? auth()->user()->phone ?? '') }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="7X XXX XX XX">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 2 : Identification ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="2" data-label="Identification du projet">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">2</div>
                    <h3 class="section-title text-base">Identification du projet</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Domaine scientifique <span class="text-red-500">*</span></label>
                    @php $domainOptions = $formOptions['scientific_domain'] ?? collect(); @endphp
                    @if($domainOptions->count())
                    <select name="scientific_domain" class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        <option value="">– Sélectionner –</option>
                        @foreach($domainOptions as $opt)
                        <option value="{{ $opt->value }}" {{ old('scientific_domain', $project?->scientific_domain) === $opt->value ? 'selected' : '' }}>{{ $opt->label }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" name="scientific_domain" value="{{ old('scientific_domain', $project?->scientific_domain) }}"
                           class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Ex : Sciences de l'ingénieur…" required>
                    @endif
                    @error('scientific_domain') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Type de projet <span class="text-red-500">*</span></label>
                    @error('project_types') <p class="form-error">{{ $message }}</p> @enderror
                    @php
                        $selectedTypes = old('project_types', $project?->project_types ?? []);
                        $typeOptions   = $formOptions['project_type'] ?? collect();
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-1">
                        @foreach($typeOptions as $opt)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($opt->value, $selectedTypes) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="project_types[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedTypes) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-blue-600">
                            <span class="text-sm text-gray-700 font-medium">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="form-label">Niveau de maturité</label>
                    @php
                        $selectedMaturity = old('maturity_level', $project?->maturity_level);
                        $maturityOptions  = $formOptions['maturity_level'] ?? collect();
                    @endphp
                    <div class="flex gap-3 flex-wrap mt-1">
                        @foreach($maturityOptions as $opt)
                        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border {{ $selectedMaturity === $opt->value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="radio" name="maturity_level" value="{{ $opt->value }}"
                                   {{ $selectedMaturity === $opt->value ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 text-blue-600">
                            <span class="text-sm font-medium text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 3 : Description ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="3" data-label="Description synthétique">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">3</div>
                    <h3 class="section-title text-base">Description synthétique</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Résumé <span class="text-red-500">*</span> <span class="text-gray-400 font-normal text-xs">(5–7 lignes max)</span></label>
                    <textarea name="summary" rows="5" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required
                              placeholder="Décrivez brièvement votre projet en 5 à 7 lignes...">{{ old('summary', $project?->summary) }}</textarea>
                    @error('summary') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Problématique adressée <span class="text-red-500">*</span></label>
                    <textarea name="problematic" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required
                              placeholder="Quel problème votre projet cherche-t-il à résoudre ?">{{ old('problematic', $project?->problematic) }}</textarea>
                    @error('problematic') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Solution / Innovation proposée <span class="text-red-500">*</span></label>
                    <textarea name="solution" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required
                              placeholder="Décrivez la solution ou l'innovation développée...">{{ old('solution', $project?->solution) }}</textarea>
                    @error('solution') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Résultats obtenus</label>
                    <textarea name="results" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Décrivez les résultats concrets obtenus à ce jour...">{{ old('results', $project?->results) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 4 : Valorisation & Impact ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="4" data-label="Valorisation &amp; Impact">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">4</div>
                    <h3 class="section-title text-base">Valorisation &amp; Impact</h3>
                </div>
            </div>
            <div class="card-body space-y-5">
                <div>
                    <label class="form-label">Protection obtenue / souhaitée</label>
                    @php
                        $selectedProtections = old('protection_types', $project?->protection_types ?? []);
                        $protectionOptions   = $formOptions['protection_type'] ?? collect();
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach($protectionOptions as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selectedProtections) ? 'border-purple-400 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="protection_types[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedProtections) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-purple-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="protection_autres" value="{{ old('protection_autres', $project?->protection_autres) }}"
                           class="form-input text-sm mt-2" {{ $readonly ? 'disabled' : '' }} placeholder="Précisions ou commentaires sur vos choix ci-dessus…">
                </div>
                <div>
                    <label class="form-label">Valorisation</label>
                    @php
                        $selectedValorisation = old('valorisation_types', $project?->valorisation_types ?? []);
                        $valorisationOptions  = $formOptions['valorisation_type'] ?? collect();
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach($valorisationOptions as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selectedValorisation) ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="valorisation_types[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedValorisation) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-emerald-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="valorisation_autres" value="{{ old('valorisation_autres', $project?->valorisation_autres) }}"
                           class="form-input text-sm mt-2" {{ $readonly ? 'disabled' : '' }} placeholder="Précisions ou commentaires sur vos choix ci-dessus…">
                </div>
                <div>
                    <label class="form-label">Impact attendu</label>
                    @php
                        $selectedImpacts = old('impact_types', $project?->impact_types ?? []);
                        $impactOptions   = $formOptions['impact_type'] ?? collect();
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @foreach($impactOptions as $opt)
                        <label class="flex flex-col items-center gap-2 p-3 rounded-xl border text-center {{ in_array($opt->value, $selectedImpacts) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="impact_types[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedImpacts) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-blue-600">
                            <span class="text-xs font-medium text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 5 : Présentation SRI ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="5" data-label="Présentation SRI">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">5</div>
                    <h3 class="section-title text-base">Présentation lors de la SRI</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Format de présentation souhaité</label>
                    @php
                        $selectedFormats = old('presentation_formats', $project?->presentation_formats ?? []);
                        $formatOptions   = $formOptions['presentation_format'] ?? collect();
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-1">
                        @foreach($formatOptions as $opt)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($opt->value, $selectedFormats) ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="presentation_formats[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedFormats) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-amber-600">
                            <span class="text-sm font-medium text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="presentation_autres" value="{{ old('presentation_autres', $project?->presentation_autres) }}"
                           class="form-input text-sm mt-2" {{ $readonly ? 'disabled' : '' }} placeholder="Précisions ou commentaires sur vos choix ci-dessus…">
                </div>
                <div>
                    <label class="form-label">Besoins logistiques spécifiques</label>
                    <textarea name="logistic_needs" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Ex : Connexion électrique, écran, espace spécifique, équipements...">{{ old('logistic_needs', $project?->logistic_needs) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 6 : Collaborateurs ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="6" data-label="Collaborateurs">
        @if(!$readonly)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">6</div>
                    <h3 class="section-title text-base">Collaborateurs de la communication</h3>
                </div>
            </div>
            <div class="card-body">
                <p class="text-sm text-gray-500 mb-4">Ajoutez les personnes impliquées dans ce projet. (Optionnel)</p>
                <div id="collaborateurs-list" class="space-y-4">
                    @php $collabs = old('collaborateurs', $project?->collaborators?->toArray() ?? [[]]) @endphp
                    @foreach($collabs as $idx => $collab)
                    <div class="collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-700">Collaborateur {{ $idx + 1 }}</p>
                            @if($idx > 0)
                            <button type="button" onclick="removeCollaborateur(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div><label class="form-label text-xs">Nom</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][nom]" value="{{ $collab['nom'] ?? '' }}" class="form-input text-sm"></div>
                            <div><label class="form-label text-xs">Prénom</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][prenom]" value="{{ $collab['prenom'] ?? '' }}" class="form-input text-sm"></div>
                            <div><label class="form-label text-xs">Email</label>
                                <input type="email" name="collaborateurs[{{ $idx }}][email]" value="{{ $collab['email'] ?? '' }}" class="form-input text-sm"></div>
                            <div><label class="form-label text-xs">Téléphone</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][telephone]" value="{{ $collab['telephone'] ?? '' }}" class="form-input text-sm"></div>
                            <div><label class="form-label text-xs">Institution</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][institution]" value="{{ $collab['institution'] ?? '' }}" class="form-input text-sm"></div>
                            <div><label class="form-label text-xs">Rôle</label>
                                <select name="collaborateurs[{{ $idx }}][role]" class="form-input text-sm">
                                    <option value="">– Sélectionner –</option>
                                    @foreach($collaboratorRoles as $opt)
                                    <option value="{{ $opt->value }}" {{ ($collab['role_collaborateur'] ?? $collab['role'] ?? '') === $opt->value ? 'selected' : '' }}>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addCollaborateur()" class="mt-4 text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Ajouter un collaborateur
                </button>
            </div>
        </div>
        @else
        @if($project?->collaborators?->count() > 0)
        <div class="card">
            <div class="card-header"><h3 class="section-title text-base">Collaborateurs</h3></div>
            <div class="card-body space-y-3">
                @foreach($project->collaborators as $collab)
                <div class="flex items-start gap-3 text-sm border-b border-gray-50 pb-3 last:border-0">
                    <div>
                        <p class="font-medium text-gray-900">{{ $collab->fullName() }}</p>
                        @if($collab->role_collaborateur)<p class="text-xs text-gray-500">{{ $collab->role_collaborateur }}</p>@endif
                        @if($collab->institution)<p class="text-xs text-gray-400">{{ $collab->institution }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif
    </div>

    {{-- ===== ÉTAPE 7 : Récapitulatif ===== --}}
    @if(!$readonly)
    <div class="form-step hidden" data-step="7" data-label="Récapitulatif">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">✓</div>
                    <h3 class="section-title text-base">Récapitulatif — vérifiez avant de soumettre</h3>
                </div>
            </div>
            <div class="card-body" id="recap-content">
                {{-- Généré dynamiquement par JS --}}
            </div>
        </div>

        {{-- Boutons finaux --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-wrap items-center gap-3 mt-4">
            @if($adminMode)
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Enregistrer les modifications
                </button>
                @if($project && !$project->isSubmitted())
                <form method="POST" action="{{ route('superadmin.projects.submit', $project) }}"
                      data-confirm="Soumettre ce projet au nom du porteur ? Un email de confirmation lui sera envoyé."
                      data-confirm-title="Soumettre le projet" data-confirm-type="warning" class="inline">
                    @csrf
                    <button type="submit" class="btn-success flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        Soumettre pour le porteur
                    </button>
                </form>
                @elseif($project?->isSubmitted())
                <span class="inline-flex items-center gap-1.5 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Déjà soumis
                </span>
                @endif
            @else
                @if($project)
                <form method="POST" action="{{ route('porteur.projects.submit', $project) }}"
                      data-confirm="Soumettre définitivement votre projet ? Cette action est irréversible."
                      data-confirm-title="Soumettre le projet" data-confirm-type="warning" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        Soumettre définitivement
                    </button>
                </form>
                @endif
                <button type="submit" class="btn-secondary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Sauvegarder le brouillon
                </button>
            @endif
            <a href="{{ $backRoute }}" class="btn-secondary">Retour</a>
        </div>
    </div>
    @endif

    {{-- Navigation étapes --}}
    @if(!$readonly)
    <div class="flex items-center justify-between mt-4" id="step-nav">
        <button type="button" id="btn-prev" onclick="stepPrev()"
                class="btn-secondary hidden">
            ← Précédent
        </button>
        <div class="flex-1"></div>
        <button type="button" id="btn-next" onclick="stepNext()"
                class="btn-primary">
            Suivant →
        </button>
    </div>
    @else
    <div class="flex gap-3 mt-4">
        <a href="{{ $backRoute }}" class="btn-secondary">← Retour au tableau de bord</a>
    </div>
    @endif

</form>

@push('scripts')
<script>
(function() {
    const TOTAL_STEPS = 7;
    let current = {{ $firstErrorStep > 0 ? $firstErrorStep : 1 }};

    const steps    = document.querySelectorAll('.form-step');
    const dots     = document.querySelectorAll('.step-dot');
    const btnPrev  = document.getElementById('btn-prev');
    const btnNext  = document.getElementById('btn-next');
    const lblTop   = document.getElementById('step-label-top');
    const numTop   = document.getElementById('step-current');

    function showStep(n) {
        steps.forEach(s => s.classList.add('hidden'));
        const target = document.querySelector(`.form-step[data-step="${n}"]`);
        if (target) {
            target.classList.remove('hidden');
            lblTop && (lblTop.textContent = target.dataset.label || '');
        }
        numTop && (numTop.textContent = n);

        // Dots
        dots.forEach((d, i) => {
            const s = parseInt(d.dataset.step);
            d.classList.remove('bg-blue-600', 'bg-emerald-500', 'bg-gray-200');
            if (s < n)       d.classList.add('bg-emerald-500');
            else if (s === n) d.classList.add('bg-blue-600');
            else              d.classList.add('bg-gray-200');
        });

        // Boutons nav
        btnPrev && btnPrev.classList.toggle('hidden', n === 1);
        if (btnNext) {
            if (n === TOTAL_STEPS) {
                btnNext.classList.add('hidden');
            } else {
                btnNext.classList.remove('hidden');
                btnNext.textContent = n === TOTAL_STEPS - 1 ? 'Voir le récapitulatif →' : 'Suivant →';
            }
        }

        // Nav bar
        const nav = document.getElementById('step-nav');
        if (nav) nav.classList.toggle('hidden', n === TOTAL_STEPS);

        if (n === TOTAL_STEPS) buildRecap();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(n) {
        const section = document.querySelector(`.form-step[data-step="${n}"]`);
        if (!section) return true;
        const required = section.querySelectorAll('[required]');
        let ok = true;
        required.forEach(el => {
            el.classList.remove('!border-red-400', '!bg-red-50');
            if (el.type === 'checkbox' || el.type === 'radio') {
                const name = el.name;
                const checked = section.querySelector(`[name="${name}"]:checked`);
                if (!checked) {
                    ok = false;
                    el.closest('.grid, div')?.classList.add('ring-1', 'ring-red-300', 'rounded-xl');
                }
            } else if (!el.value.trim()) {
                ok = false;
                el.classList.add('!border-red-400', '!bg-red-50');
            }
        });
        if (!ok) el?.scrollIntoView?.({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    window.stepNext = function() {
        if (validateStep(current)) {
            current = Math.min(current + 1, TOTAL_STEPS);
            showStep(current);
        }
    };

    window.stepPrev = function() {
        current = Math.max(current - 1, 1);
        showStep(current);
    };

    // Récapitulatif
    function buildRecap() {
        const form = document.getElementById('project-form');
        const fd   = new FormData(form);
        let html   = '';

        function row(label, val) {
            if (!val || val === '') return '';
            return `<div class="py-2.5 border-b border-gray-50 flex gap-4">
                <span class="text-xs text-gray-400 w-40 shrink-0">${label}</span>
                <span class="text-sm text-gray-800 font-medium">${val}</span>
            </div>`;
        }
        function section(title, rows) {
            const content = rows.join('');
            if (!content) return '';
            return `<div class="mb-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">${title}</p>
                ${content}
            </div>`;
        }
        function checked(name) {
            return [...form.querySelectorAll(`[name="${name}"]:checked`)].map(el => el.value).join(', ') || '—';
        }

        html += section('Responsable', [
            row('Nom complet',        fd.get('responsable_nom')),
            row('Email institutionnel', fd.get('contact_email')),
            row('Email personnel',    fd.get('email_professionnel') || '—'),
            row('Téléphone',          fd.get('contact_phone') || '—'),
        ]);
        html += section('Identification', [
            row('Domaine scientifique', fd.get('scientific_domain')),
            row('Type(s) de projet',   checked('project_types[]')),
            row('Maturité',            fd.get('maturity_level') || '—'),
        ]);
        html += section('Description', [
            row('Résumé',              fd.get('summary')),
            row('Problématique',       fd.get('problematic')),
            row('Solution',            fd.get('solution')),
            row('Résultats',           fd.get('results') || '—'),
        ]);
        html += section('Valorisation & Impact', [
            row('Protection',    checked('protection_types[]')),
            row('Valorisation',  checked('valorisation_types[]')),
            row('Impact',        checked('impact_types[]')),
        ]);
        html += section('Présentation SRI', [
            row('Format',  checked('presentation_formats[]')),
            row('Besoins logistiques', fd.get('logistic_needs') || '—'),
        ]);

        // Collaborateurs
        const collabRows = [];
        const noms = form.querySelectorAll('[name$="[nom]"]');
        noms.forEach((el, i) => {
            if (!el.value.trim()) return;
            const base = el.name.replace('[nom]', '');
            const prenom = form.querySelector(`[name="${base}[prenom]"]`)?.value || '';
            const email  = form.querySelector(`[name="${base}[email]"]`)?.value || '';
            const inst   = form.querySelector(`[name="${base}[institution]"]`)?.value || '';
            collabRows.push(row(`Collaborateur ${i+1}`, `${el.value} ${prenom}${email ? ' · '+email : ''}${inst ? ' · '+inst : ''}`));
        });
        if (collabRows.length) html += section('Collaborateurs', collabRows);

        document.getElementById('recap-content').innerHTML = html || '<p class="text-gray-400 text-sm">Aucune donnée saisie.</p>';
    }

    // Erreurs de validation serveur
    document.querySelectorAll('.form-error').forEach(err => {
        const wrap = err.closest('div');
        if (!wrap) return;
        const field = wrap.querySelector('input:not([type="checkbox"]):not([type="radio"]), select, textarea');
        if (field) field.classList.add('!border-red-400', '!bg-red-50');
    });

    showStep(current);

    // Collaborateurs JS
    let collabCount = document.querySelectorAll('.collaborateur-row').length;
    const rolesHtml = `{{ $collaboratorRoles->map(fn($o) => '<option value="'.$o->value.'">'.$o->label.'</option>')->implode('') }}`;

    window.addCollaborateur = function() {
        const idx = collabCount++;
        const html = `<div class="collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-700">Collaborateur ${idx + 1}</p>
                <button type="button" onclick="removeCollaborateur(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div><label class="form-label text-xs">Nom</label><input type="text" name="collaborateurs[${idx}][nom]" class="form-input text-sm"></div>
                <div><label class="form-label text-xs">Prénom</label><input type="text" name="collaborateurs[${idx}][prenom]" class="form-input text-sm"></div>
                <div><label class="form-label text-xs">Email</label><input type="email" name="collaborateurs[${idx}][email]" class="form-input text-sm"></div>
                <div><label class="form-label text-xs">Téléphone</label><input type="text" name="collaborateurs[${idx}][telephone]" class="form-input text-sm"></div>
                <div><label class="form-label text-xs">Institution</label><input type="text" name="collaborateurs[${idx}][institution]" class="form-input text-sm"></div>
                <div><label class="form-label text-xs">Rôle</label><select name="collaborateurs[${idx}][role]" class="form-input text-sm"><option value="">– Sélectionner –</option>${rolesHtml}</select></div>
            </div>
        </div>`;
        document.getElementById('collaborateurs-list').insertAdjacentHTML('beforeend', html);
    };

    window.removeCollaborateur = function(btn) {
        btn.closest('.collaborateur-row').remove();
    };
})();
</script>
@endpush
