@php
    $adminMode   = $adminMode ?? false;
    $readonly    = $project?->isSubmitted() && !$adminMode;
    $assignment  = $assignment ?? $project?->assignment;
    $backRoute   = $adminMode ? route('superadmin.projects.index') : route('porteur.dashboard');
    $d = $project?->approfondiDetails;

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

    $TOTAL_STEPS = 8;
    $stepErrors = [
        1 => ['responsable_nom','contact_email','email_professionnel','contact_phone'],
        2 => ['scientific_domain','project_types','titre_complet','laboratoire_nom'],
        3 => ['summary','problematic','solution','results','contexte_etat_art','approche_methodologique','caractere_innovant'],
        4 => ['resultats_scientifiques','resultats_techniques','indicateurs_chiffres','trl_level'],
        5 => ['protection_types','valorisation_types','impact_types','dimensions_impact'],
        6 => ['presentation_formats','logistic_needs','public_cible_vise'],
        7 => array_filter($errors->keys(), fn($k) => str_starts_with($k, 'collaborateurs') || str_starts_with($k, 'co_porteurs')),
    ];
    $firstErrorStep = 0;
    foreach ($stepErrors as $stepNum => $fields) {
        foreach ($fields as $f) {
            if ($errors->has($f)) { $firstErrorStep = $stepNum; break 2; }
        }
    }
@endphp

@if($adminMode)
<div class="flex items-center gap-2.5 p-3 bg-violet-50 border border-violet-200 rounded-xl text-sm text-violet-800 mb-4">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
    <span><strong>Mode superadmin</strong> — modification directe, restrictions de statut levées.</span>
</div>
@endif

<div class="flex items-center gap-2.5 p-3 bg-indigo-50 border border-indigo-200 rounded-xl text-sm text-indigo-800 mb-4">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
    <span><strong>Format Approfondi</strong> — pour les projets à fort potentiel de valorisation (prototype testé, brevet, start-up, transfert technologique…).</span>
</div>

@if($readonly)
<div class="alert-info mb-4">
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>Ce projet a été soumis et est en lecture seule.</span>
</div>
@endif

<form method="POST" action="{{ $action }}" id="project-form" class="space-y-0">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <input type="hidden" name="submission_template" value="approfondi">

    {{-- Progression --}}
    @if(!$readonly)
    <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 mb-4" id="stepper-header">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Étape <span id="step-current">1</span> sur {{ $TOTAL_STEPS }}</span>
            <span class="text-xs text-gray-400" id="step-label-top">Informations générales</span>
        </div>
        <div class="flex gap-1.5">
            @for($s = 1; $s <= $TOTAL_STEPS; $s++)
            <div class="step-dot h-1.5 flex-1 rounded-full bg-gray-200 transition-all duration-300" data-step="{{ $s }}"></div>
            @endfor
        </div>
        <div class="flex justify-between mt-3 text-[10px] sm:text-xs text-gray-400">
            <span>Infos</span><span>Identité</span><span>Description</span><span>Résultats</span><span>Valorisation</span><span>Présentation</span><span>Équipe</span><span>Récap</span>
        </div>
    </div>
    @endif

    {{-- ===== ÉTAPE 1 : Informations générales ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="1" data-label="Informations générales">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">1</div>
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
                            <p class="text-gray-500 text-xs mb-0.5">Titre du projet (assignation)</p>
                            <p class="font-semibold text-gray-900">{{ $assignment->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Responsable scientifique <span class="text-red-500">*</span></label>
                        <input type="text" name="responsable_nom" value="{{ old('responsable_nom', $project?->responsable_nom) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required placeholder="Nom complet">
                        @error('responsable_nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email institutionnel <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $project?->contact_email ?? auth()->user()->email) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        @error('contact_email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email personnel</label>
                        <input type="email" name="email_professionnel" value="{{ old('email_professionnel', $project?->email_professionnel) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }}>
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

    {{-- ===== ÉTAPE 2 : Identité du projet (Section A) ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="2" data-label="Carte d'identité (Section A)">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">2</div>
                    <h3 class="section-title text-base">A — Carte d'identité du projet</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                @php $domainOptions = $formOptions['scientific_domain'] ?? collect(); @endphp
                <div>
                    <label class="form-label">Domaine principal <span class="text-red-500">*</span></label>
                    @if($domainOptions->count())
                    <select name="scientific_domain" class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        <option value="">– Sélectionner –</option>
                        @foreach($domainOptions as $opt)
                        <option value="{{ $opt->value }}" {{ old('scientific_domain', $project?->scientific_domain) === $opt->value ? 'selected' : '' }}>{{ $opt->label }}</option>
                        @endforeach
                    </select>
                    <div id="scientific_domain_autre_wrap" class="{{ old('scientific_domain', $project?->scientific_domain) === 'autres' ? '' : 'hidden' }} mt-2">
                        <input type="text" name="scientific_domain_autre" value="{{ old('scientific_domain_autre', $project?->scientific_domain_autre) }}" class="form-input text-sm" placeholder="Précisez le domaine…">
                    </div>
                    @else
                    <input type="text" name="scientific_domain" value="{{ old('scientific_domain', $project?->scientific_domain) }}" class="form-input" required>
                    @endif
                    @error('scientific_domain') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Titre complet du projet <span class="text-red-500">*</span></label>
                        <textarea name="titre_complet" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required>{{ old('titre_complet', $d?->titre_complet) }}</textarea>
                        @error('titre_complet') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Acronyme</label>
                        <input type="text" name="acronyme_projet" value="{{ old('acronyme_projet', $d?->acronyme_projet) }}" class="form-input" {{ $readonly ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="form-label">Laboratoire / Équipe <span class="text-red-500">*</span></label>
                        <input type="text" name="laboratoire_nom" value="{{ old('laboratoire_nom', $d?->laboratoire_nom) }}" class="form-input" {{ $readonly ? 'disabled' : '' }} required placeholder="Nom complet">
                        @error('laboratoire_nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Acronyme du laboratoire</label>
                        <input type="text" name="laboratoire_acronyme" value="{{ old('laboratoire_acronyme', $d?->laboratoire_acronyme) }}" class="form-input" {{ $readonly ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="form-label">Site web du laboratoire</label>
                        <input type="url" name="laboratoire_site_web" value="{{ old('laboratoire_site_web', $d?->laboratoire_site_web) }}" class="form-input" {{ $readonly ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="form-label">Titre du responsable</label>
                        <input type="text" name="responsable_titre" value="{{ old('responsable_titre', $d?->responsable_titre) }}" class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Professeur, Maître de conférences…">
                    </div>
                    <div>
                        <label class="form-label">Fonction du responsable</label>
                        <input type="text" name="responsable_fonction" value="{{ old('responsable_fonction', $d?->responsable_fonction) }}" class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Directeur de laboratoire, Chef d'équipe…">
                    </div>
                    <div>
                        <label class="form-label">Date de démarrage</label>
                        <input type="date" name="date_demarrage" value="{{ old('date_demarrage', $d?->date_demarrage?->format('Y-m-d')) }}" class="form-input" {{ $readonly ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="form-label">Durée totale prévue</label>
                        <input type="text" name="duree_prevue" value="{{ old('duree_prevue', $d?->duree_prevue) }}" class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Ex : 18 mois">
                    </div>
                </div>

                <div>
                    <label class="form-label">Mots-clés (3 à 5, séparés par des virgules)</label>
                    <input type="text" name="mots_cles_text" value="{{ old('mots_cles_text', $d && $d->mots_cles ? implode(', ', $d->mots_cles) : '') }}" class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Ex : intelligence artificielle, santé, diagnostic">
                </div>
                <div>
                    <label class="form-label">Sous-domaines / disciplines connexes (séparés par des virgules)</label>
                    <input type="text" name="sous_domaines_text" value="{{ old('sous_domaines_text', $d && $d->sous_domaines ? implode(', ', $d->sous_domaines) : '') }}" class="form-input" {{ $readonly ? 'disabled' : '' }}>
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
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($opt->value, $selectedTypes) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="project_types[]" value="{{ $opt->value }}"
                                   {{ in_array($opt->value, $selectedTypes) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-sm text-gray-700 font-medium">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 3 : Description scientifique (Section B) ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="3" data-label="Description scientifique (Section B)">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">3</div>
                    <h3 class="section-title text-base">B — Description scientifique et innovation</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Résumé général <span class="text-red-500">*</span></label>
                    <textarea name="summary" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required>{{ old('summary', $project?->summary) }}</textarea>
                    @error('summary') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Problématique adressée <span class="text-red-500">*</span></label>
                    <textarea name="problematic" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required>{{ old('problematic', $project?->problematic) }}</textarea>
                    @error('problematic') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Solution proposée <span class="text-red-500">*</span></label>
                    <textarea name="solution" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }} required>{{ old('solution', $project?->solution) }}</textarea>
                    @error('solution') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">B1 — Contexte et état de l'art</label>
                    <textarea name="contexte_etat_art" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Situer le projet par rapport aux travaux existants au niveau africain et international.">{{ old('contexte_etat_art', $d?->contexte_etat_art) }}</textarea>
                </div>
                <div>
                    <label class="form-label">B3 — Approche méthodologique</label>
                    <textarea name="approche_methodologique" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Méthodes, outils, terrains, populations ou échantillons mobilisés.">{{ old('approche_methodologique', $d?->approche_methodologique) }}</textarea>
                </div>
                <div>
                    <label class="form-label">B4 — Caractère innovant et différenciation</label>
                    <textarea name="caractere_innovant" rows="4" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Rupture, avantage compétitif, pertinence pour le contexte sénégalais et africain.">{{ old('caractere_innovant', $d?->caractere_innovant) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 4 : Résultats & Maturité (Sections C, D) ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="4" data-label="Résultats & maturité (Sections C, D)">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">4</div>
                    <h3 class="section-title text-base">C — Résultats consolidés / D — Maturité</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">C1 — Résultats scientifiques</label>
                    <textarea name="resultats_scientifiques" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Publications majeures, communications, distinctions.">{{ old('resultats_scientifiques', $d?->resultats_scientifiques) }}</textarea>
                </div>
                <div>
                    <label class="form-label">C2 — Résultats techniques</label>
                    <textarea name="resultats_techniques" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Prototypes, démonstrateurs, jeux de données, logiciels.">{{ old('resultats_techniques', $d?->resultats_techniques) }}</textarea>
                </div>
                <div>
                    <label class="form-label">C3 — Indicateurs chiffrés</label>
                    <textarea name="indicateurs_chiffres" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}>{{ old('indicateurs_chiffres', $d?->indicateurs_chiffres) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Niveau de maturité technologique (TRL)</label>
                    @php $selectedTrl = old('trl_level', $d?->trl_level); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                        @foreach(($formOptions['trl_level'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ $selectedTrl === $opt->value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="radio" name="trl_level" value="{{ $opt->value }}" {{ $selectedTrl === $opt->value ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">Voies de valorisation envisagées</label>
                    @php $selectedVoies = old('voies_valorisation', $d?->voies_valorisation ?? []); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(($formOptions['voie_valorisation'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selectedVoies) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="voies_valorisation[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selectedVoies) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">D1 — Propriété intellectuelle</label>
                    <textarea name="propriete_intellectuelle" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Brevets déposés, demandes en cours, marques, licences.">{{ old('propriete_intellectuelle', $d?->propriete_intellectuelle) }}</textarea>
                </div>
                <div>
                    <label class="form-label">D2 — Modèle économique envisagé</label>
                    <textarea name="modele_economique" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Marché cible, clients/usagers, proposition de valeur, modèle de revenus.">{{ old('modele_economique', $d?->modele_economique) }}</textarea>
                </div>
                <div>
                    <label class="form-label">D3 — Partenariats et financement</label>
                    <textarea name="partenariats_financement" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}>{{ old('partenariats_financement', $d?->partenariats_financement) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 5 : Valorisation & Impact (Section E) ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="5" data-label="Valorisation &amp; Impact (Section E)">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">5</div>
                    <h3 class="section-title text-base">E — Impact</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                @php
                    $selProtections = old('protection_types', $project?->protection_types ?? []);
                    $selValorisation = old('valorisation_types', $project?->valorisation_types ?? []);
                @endphp
                <div>
                    <label class="form-label">Protection obtenue / souhaitée</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(($formOptions['protection_type'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selProtections) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="protection_types[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selProtections) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div id="protection_autres_wrap" class="{{ in_array('autres', $selProtections) ? '' : 'hidden' }} mt-2">
                        <input type="text" name="protection_autres" value="{{ old('protection_autres', $project?->protection_autres) }}" class="form-input text-sm" placeholder="Précisez…">
                    </div>
                </div>
                <div>
                    <label class="form-label">Valorisation (format Standard)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(($formOptions['valorisation_type'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selValorisation) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="valorisation_types[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selValorisation) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div id="valorisation_autres_wrap" class="{{ in_array('autres', $selValorisation) ? '' : 'hidden' }} mt-2">
                        <input type="text" name="valorisation_autres" value="{{ old('valorisation_autres', $project?->valorisation_autres) }}" class="form-input text-sm" placeholder="Précisez…">
                    </div>
                </div>

                <div>
                    <label class="form-label">Dimensions d'impact</label>
                    @php $selDimensions = old('dimensions_impact', $d?->dimensions_impact ?? []); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(($formOptions['impact_dimension'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selDimensions) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="dimensions_impact[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selDimensions) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">E1 — Bénéficiaires directs et indirects</label>
                    <textarea name="beneficiaires" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}>{{ old('beneficiaires', $d?->beneficiaires) }}</textarea>
                </div>
                <div>
                    <label class="form-label">E2 — Indicateurs d'impact chiffrés</label>
                    <textarea name="indicateurs_impact" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}>{{ old('indicateurs_impact', $d?->indicateurs_impact) }}</textarea>
                </div>
                <div>
                    <label class="form-label">E3 — Contribution aux ODD</label>
                    <textarea name="contribution_odd" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Objectifs de Développement Durable concernés.">{{ old('contribution_odd', $d?->contribution_odd) }}</textarea>
                </div>
                <div>
                    <label class="form-label">E4 — Pertinence pour le Sénégal et l'Afrique</label>
                    <textarea name="pertinence_senegal_afrique" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}>{{ old('pertinence_senegal_afrique', $d?->pertinence_senegal_afrique) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 6 : Présentation SRI & Annexes (Sections F, G) ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="6" data-label="Présentation SRI &amp; Annexes (Sections F, G)">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">6</div>
                    <h3 class="section-title text-base">F — Présentation lors de la SRI / G — Annexes</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                @php $selFormats = old('presentation_formats', $project?->presentation_formats ?? []); @endphp
                <div>
                    <label class="form-label">Format(s) de présentation souhaité(s)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-1">
                        @foreach(($formOptions['presentation_format'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($opt->value, $selFormats) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="presentation_formats[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selFormats) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-sm text-gray-700 font-medium">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div id="presentation_autres_wrap" class="{{ in_array('autres', $selFormats) ? '' : 'hidden' }} mt-2">
                        <input type="text" name="presentation_autres" value="{{ old('presentation_autres', $project?->presentation_autres) }}" class="form-input text-sm" placeholder="Précisez…">
                    </div>
                </div>
                <div>
                    <label class="form-label">F1 — Public cible visé pendant la SRI</label>
                    <textarea name="public_cible_vise" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Industriels, investisseurs, décideurs publics, communauté scientifique…">{{ old('public_cible_vise', $d?->public_cible_vise) }}</textarea>
                </div>
                <div>
                    <label class="form-label">F2 — Besoins logistiques détaillés</label>
                    <textarea name="logistic_needs" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Surface stand, tables, écrans, internet, électricité…">{{ old('logistic_needs', $project?->logistic_needs) }}</textarea>
                </div>
                <div>
                    <label class="form-label">F3 — Supports prévus</label>
                    <textarea name="supports_prevus" rows="2" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Posters, vidéos, brochures, démonstrateur, maquette…">{{ old('supports_prevus', $d?->supports_prevus) }}</textarea>
                </div>

                <div>
                    <label class="form-label">G — Annexes que vous prévoyez de fournir</label>
                    @php $selAnnexes = old('annexes_checklist', $d?->annexes_checklist ?? []); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                        @foreach(($formOptions['annexe_type'] ?? collect()) as $opt)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($opt->value, $selAnnexes) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="annexes_checklist[]" value="{{ $opt->value }}" {{ in_array($opt->value, $selAnnexes) ? 'checked' : '' }} {{ $readonly ? 'disabled' : '' }} class="w-4 h-4 rounded text-indigo-600">
                            <span class="text-xs text-gray-700">{{ $opt->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div id="annexes_autres_wrap" class="{{ in_array('autres', $selAnnexes) ? '' : 'hidden' }} mt-2">
                        <input type="text" name="annexes_autres_texte" value="{{ old('annexes_autres_texte', $d?->annexes_autres_texte) }}" class="form-input text-sm" placeholder="Précisez…">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ÉTAPE 7 : Co-porteurs & Collaborateurs ===== --}}
    <div class="form-step {{ $readonly ? '' : 'hidden' }}" data-step="7" data-label="Co-porteurs &amp; Collaborateurs">
        @if(!$readonly)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">7</div>
                    <h3 class="section-title text-base">Co-porteurs / Co-investigateurs</h3>
                </div>
            </div>
            <div class="card-body">
                <p class="text-sm text-gray-500 mb-4">Chercheurs associés portant scientifiquement le projet avec vous. (Optionnel)</p>
                <div id="co-porteurs-list" class="space-y-4">
                    @php $coPorteurs = old('co_porteurs', $project?->coPorteurs?->toArray() ?? [[]]) @endphp
                    @foreach($coPorteurs as $idx => $cp)
                    <div class="co-porteur-row border border-gray-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-700">Co-porteur {{ $idx + 1 }}</p>
                            @if($idx > 0)<button type="button" onclick="this.closest('.co-porteur-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>@endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="co_porteurs[{{ $idx }}][nom]" value="{{ $cp['nom'] ?? '' }}" placeholder="Nom" class="form-input text-sm">
                            <input type="text" name="co_porteurs[{{ $idx }}][prenom]" value="{{ $cp['prenom'] ?? '' }}" placeholder="Prénom" class="form-input text-sm">
                            <input type="email" name="co_porteurs[{{ $idx }}][email]" value="{{ $cp['email'] ?? '' }}" placeholder="Email" class="form-input text-sm">
                            <input type="text" name="co_porteurs[{{ $idx }}][institution]" value="{{ $cp['institution'] ?? '' }}" placeholder="Institution" class="form-input text-sm">
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addCoPorteur()" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800">+ Ajouter un co-porteur</button>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h3 class="section-title text-base">Collaborateurs</h3></div>
            <div class="card-body">
                <div id="collaborateurs-list" class="space-y-4">
                    @php $collabs = old('collaborateurs', $project?->collaborators?->toArray() ?? [[]]) @endphp
                    @foreach($collabs as $idx => $collab)
                    <div class="collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-700">Collaborateur {{ $idx + 1 }}</p>
                            @if($idx > 0)<button type="button" onclick="this.closest('.collaborateur-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>@endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="collaborateurs[{{ $idx }}][nom]" value="{{ $collab['nom'] ?? '' }}" placeholder="Nom" class="form-input text-sm">
                            <input type="text" name="collaborateurs[{{ $idx }}][prenom]" value="{{ $collab['prenom'] ?? '' }}" placeholder="Prénom" class="form-input text-sm">
                            <input type="email" name="collaborateurs[{{ $idx }}][email]" value="{{ $collab['email'] ?? '' }}" placeholder="Email" class="form-input text-sm">
                            <input type="text" name="collaborateurs[{{ $idx }}][institution]" value="{{ $collab['institution'] ?? '' }}" placeholder="Institution" class="form-input text-sm">
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addCollaborateur()" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800">+ Ajouter un collaborateur</button>
            </div>
        </div>
        @else
            @include('partials.project-approfondi-detail', ['project' => $project])
        @endif
    </div>

    {{-- ===== ÉTAPE 8 : Récapitulatif ===== --}}
    @if(!$readonly)
    <div class="form-step hidden" data-step="8" data-label="Récapitulatif">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">✓</div>
                    <h3 class="section-title text-base">Récapitulatif — vérifiez avant de soumettre</h3>
                </div>
            </div>
            <div class="card-body">
                <p class="text-sm text-gray-500">Relisez chaque étape via les points de progression ci-dessus, puis soumettez votre dossier Approfondi.</p>
            </div>
        </div>

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
                    <button type="submit" class="btn-success flex items-center gap-2">Soumettre pour le porteur</button>
                </form>
                @elseif($project?->isSubmitted())
                <span class="inline-flex items-center gap-1.5 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">Déjà soumis</span>
                @endif
            @else
                @if($project)
                <form method="POST" action="{{ route('porteur.projects.submit', $project) }}"
                      data-confirm="Soumettre définitivement votre projet ? Cette action est irréversible."
                      data-confirm-title="Soumettre le projet" data-confirm-type="warning" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary flex items-center gap-2">Soumettre définitivement</button>
                </form>
                @endif
                <button type="submit" class="btn-secondary flex items-center gap-2">Sauvegarder le brouillon</button>
            @endif
            <a href="{{ $backRoute }}" class="btn-secondary">Retour</a>
        </div>
    </div>
    @endif

    {{-- Navigation étapes --}}
    @if(!$readonly)
    <div class="flex items-center justify-between mt-4" id="step-nav">
        <button type="button" id="btn-prev" onclick="stepPrev()" class="btn-secondary hidden">← Précédent</button>
        <div class="flex-1"></div>
        <button type="button" id="btn-next" onclick="stepNext()" class="btn-primary">Suivant →</button>
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
    const TOTAL_STEPS = {{ $TOTAL_STEPS }};
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
        if (target) { target.classList.remove('hidden'); lblTop && (lblTop.textContent = target.dataset.label || ''); }
        numTop && (numTop.textContent = n);
        dots.forEach((d, i) => {
            const s = parseInt(d.dataset.step);
            d.classList.remove('bg-indigo-600', 'bg-emerald-500', 'bg-gray-200');
            if (s < n) d.classList.add('bg-emerald-500');
            else if (s === n) d.classList.add('bg-indigo-600');
            else d.classList.add('bg-gray-200');
        });
        btnPrev && btnPrev.classList.toggle('hidden', n === 1);
        if (btnNext) {
            if (n === TOTAL_STEPS) btnNext.classList.add('hidden');
            else { btnNext.classList.remove('hidden'); btnNext.textContent = n === TOTAL_STEPS - 1 ? 'Voir le récapitulatif →' : 'Suivant →'; }
        }
        const nav = document.getElementById('step-nav');
        if (nav) nav.classList.toggle('hidden', n === TOTAL_STEPS);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(n) {
        const section = document.querySelector(`.form-step[data-step="${n}"]`);
        if (!section) return true;
        const required = section.querySelectorAll('[required]');
        let ok = true, firstInvalid = null;
        required.forEach(el => {
            el.classList.remove('!border-red-400', '!bg-red-50');
            if (el.type === 'checkbox' || el.type === 'radio') {
                const checked = section.querySelector(`[name="${el.name}"]:checked`);
                if (!checked) { ok = false; }
            } else if (!el.value.trim()) {
                ok = false; el.classList.add('!border-red-400', '!bg-red-50');
                if (!firstInvalid) firstInvalid = el;
            }
        });
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    window.stepNext = function() { if (validateStep(current)) { current = Math.min(current + 1, TOTAL_STEPS); showStep(current); } };
    window.stepPrev = function() { current = Math.max(current - 1, 1); showStep(current); };

    document.querySelectorAll('.form-error').forEach(err => {
        const wrap = err.closest('div');
        if (!wrap) return;
        const field = wrap.querySelector('input:not([type="checkbox"]):not([type="radio"]), select, textarea');
        if (field) field.classList.add('!border-red-400', '!bg-red-50');
    });

    showStep(current);

    // Co-porteurs
    let coPorteurCount = document.querySelectorAll('.co-porteur-row').length;
    window.addCoPorteur = function() {
        const idx = coPorteurCount++;
        const div = document.createElement('div');
        div.className = 'co-porteur-row border border-gray-200 rounded-xl p-4 space-y-3';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-700">Co-porteur ${idx + 1}</p>
                <button type="button" onclick="this.closest('.co-porteur-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text" name="co_porteurs[${idx}][nom]" placeholder="Nom" class="form-input text-sm">
                <input type="text" name="co_porteurs[${idx}][prenom]" placeholder="Prénom" class="form-input text-sm">
                <input type="email" name="co_porteurs[${idx}][email]" placeholder="Email" class="form-input text-sm">
                <input type="text" name="co_porteurs[${idx}][institution]" placeholder="Institution" class="form-input text-sm">
            </div>`;
        document.getElementById('co-porteurs-list').appendChild(div);
    };

    // Collaborateurs
    let collabCount = document.querySelectorAll('.collaborateur-row').length;
    window.addCollaborateur = function() {
        const idx = collabCount++;
        const div = document.createElement('div');
        div.className = 'collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-700">Collaborateur ${idx + 1}</p>
                <button type="button" onclick="this.closest('.collaborateur-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text" name="collaborateurs[${idx}][nom]" placeholder="Nom" class="form-input text-sm">
                <input type="text" name="collaborateurs[${idx}][prenom]" placeholder="Prénom" class="form-input text-sm">
                <input type="email" name="collaborateurs[${idx}][email]" placeholder="Email" class="form-input text-sm">
                <input type="text" name="collaborateurs[${idx}][institution]" placeholder="Institution" class="form-input text-sm">
            </div>`;
        document.getElementById('collaborateurs-list').appendChild(div);
    };

    // Bascule générique des champs "Autre"
    function initAutreToggle(inputsSelector, wrapId) {
        const wrap = document.getElementById(wrapId);
        if (!wrap) return;
        const inputs = document.querySelectorAll(inputsSelector);
        function sync() {
            const anyOther = [...inputs].some(el => (el.type === 'checkbox' || el.type === 'radio') ? (el.checked && el.value === 'autres') : (el.value === 'autres'));
            wrap.classList.toggle('hidden', !anyOther);
        }
        inputs.forEach(el => el.addEventListener('change', sync));
        sync();
    }
    initAutreToggle('select[name="scientific_domain"]', 'scientific_domain_autre_wrap');
    initAutreToggle('input[name="protection_types[]"]', 'protection_autres_wrap');
    initAutreToggle('input[name="valorisation_types[]"]', 'valorisation_autres_wrap');
    initAutreToggle('input[name="presentation_formats[]"]', 'presentation_autres_wrap');
    initAutreToggle('input[name="annexes_checklist[]"]', 'annexes_autres_wrap');
})();
</script>
@endpush
