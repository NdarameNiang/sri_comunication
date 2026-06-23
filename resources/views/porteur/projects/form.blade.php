@extends('layouts.app')
@section('title', $project ? 'Modifier le projet' : 'Remplir le formulaire')
@section('page-title', $project ? 'Modifier le projet' : 'Formulaire de collecte – ' . (\App\Models\EventConfig::active()?->event_name ?? 'SRI 2026'))
@section('page-subtitle', $assignment->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if($project?->isSubmitted())
    <div class="alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Ce projet a été soumis et est en lecture seule.</span>
    </div>
    @endif

    @php
        $readonly = $project?->isSubmitted();
        $action = $project
            ? route('porteur.projects.update', $project)
            : route('porteur.projects.store', $assignment);
        $method = $project ? 'PUT' : 'POST';
    @endphp

    <form method="POST" action="{{ $action }}" class="space-y-6">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif

        {{-- ===== SECTION 1 : Informations générales ===== --}}
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
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        @error('responsable_nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email de contact <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $project?->contact_email ?? auth()->user()->email) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }} required>
                        @error('contact_email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone de contact</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $project?->contact_phone ?? auth()->user()->phone) }}"
                               class="form-input" {{ $readonly ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SECTION 2 : Identification ===== --}}
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
                    <input type="text" name="scientific_domain" value="{{ old('scientific_domain', $project?->scientific_domain) }}"
                           class="form-input" {{ $readonly ? 'disabled' : '' }} placeholder="Ex : Sciences de l'ingénieur, Biotechnologie, Droit..."  required>
                    @error('scientific_domain') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Type de projet <span class="text-red-500">*</span></label>
                    @error('project_types') <p class="form-error">{{ $message }}</p> @enderror
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-1">
                        @php $selectedTypes = old('project_types', $project?->project_types ?? []); @endphp
                        @foreach(\App\Models\Project::projectTypeLabels() as $value => $label)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($value, $selectedTypes) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="project_types[]" value="{{ $value }}"
                                   {{ in_array($value, $selectedTypes) ? 'checked' : '' }}
                                   {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-blue-600">
                            <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SECTION 3 : Description synthétique ===== --}}
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
            </div>
        </div>

        {{-- ===== SECTION 4 : Résultats & Valorisation ===== --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">4</div>
                    <h3 class="section-title text-base">Résultats et valorisation</h3>
                </div>
            </div>
            <div class="card-body space-y-5">
                <div>
                    <label class="form-label">Résultats obtenus</label>
                    <textarea name="results" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Décrivez les résultats concrets obtenus à ce jour...">{{ old('results', $project?->results) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Niveau de maturité</label>
                    @php $selectedMaturity = old('maturity_level', $project?->maturity_level); @endphp
                    <div class="flex gap-3 flex-wrap mt-1">
                        @foreach(\App\Models\Project::maturityLabels() as $value => $label)
                        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border {{ $selectedMaturity === $value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="radio" name="maturity_level" value="{{ $value }}"
                                   {{ $selectedMaturity === $value ? 'checked' : '' }}
                                   {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 text-blue-600">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">Protection obtenue / souhaitée</label>
                    @php $selectedProtections = old('protection_types', $project?->protection_types ?? []); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(\App\Models\Project::protectionLabels() as $value => $label)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($value, $selectedProtections) ? 'border-purple-400 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="protection_types[]" value="{{ $value }}"
                                   {{ in_array($value, $selectedProtections) ? 'checked' : '' }}
                                   {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-purple-600">
                            <span class="text-xs text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <input type="text" name="protection_autres" value="{{ old('protection_autres', $project?->protection_autres) }}"
                               class="form-input text-sm" {{ $readonly ? 'disabled' : '' }} placeholder="Autres protections (préciser)...">
                    </div>
                </div>

                <div>
                    <label class="form-label">Valorisation</label>
                    @php $selectedValorisation = old('valorisation_types', $project?->valorisation_types ?? []); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                        @foreach(\App\Models\Project::valorisationLabels() as $value => $label)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg border {{ in_array($value, $selectedValorisation) ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="valorisation_types[]" value="{{ $value }}"
                                   {{ in_array($value, $selectedValorisation) ? 'checked' : '' }}
                                   {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-emerald-600">
                            <span class="text-xs text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <input type="text" name="valorisation_autres" value="{{ old('valorisation_autres', $project?->valorisation_autres) }}"
                               class="form-input text-sm" {{ $readonly ? 'disabled' : '' }} placeholder="Autres types de valorisation...">
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SECTION 5 : Impact ===== --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">5</div>
                    <h3 class="section-title text-base">Impact attendu</h3>
                </div>
            </div>
            <div class="card-body">
                @php $selectedImpacts = old('impact_types', $project?->impact_types ?? []); @endphp
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach(\App\Models\Project::impactLabels() as $value => $label)
                    <label class="flex flex-col items-center gap-2 p-3 rounded-xl border text-center {{ in_array($value, $selectedImpacts) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                        <input type="checkbox" name="impact_types[]" value="{{ $value }}"
                               {{ in_array($value, $selectedImpacts) ? 'checked' : '' }}
                               {{ $readonly ? 'disabled' : '' }}
                               class="w-4 h-4 rounded text-blue-600">
                        <span class="text-xs font-medium text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== SECTION 6 : Présentation SRI ===== --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">6</div>
                    <h3 class="section-title text-base">Présentation lors de la SRI</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Format de présentation souhaité</label>
                    @php $selectedFormats = old('presentation_formats', $project?->presentation_formats ?? []); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-1">
                        @foreach(\App\Models\Project::presentationLabels() as $value => $label)
                        <label class="flex items-center gap-2 p-3 rounded-xl border {{ in_array($value, $selectedFormats) ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-all">
                            <input type="checkbox" name="presentation_formats[]" value="{{ $value }}"
                                   {{ in_array($value, $selectedFormats) ? 'checked' : '' }}
                                   {{ $readonly ? 'disabled' : '' }}
                                   class="w-4 h-4 rounded text-amber-600">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <input type="text" name="presentation_autres" value="{{ old('presentation_autres', $project?->presentation_autres) }}"
                               class="form-input text-sm" {{ $readonly ? 'disabled' : '' }} placeholder="Autres formats...">
                    </div>
                </div>
                <div>
                    <label class="form-label">Besoins logistiques spécifiques</label>
                    <textarea name="logistic_needs" rows="3" class="form-textarea" {{ $readonly ? 'disabled' : '' }}
                              placeholder="Ex : Connexion électrique, écran, espace spécifique, équipements...">{{ old('logistic_needs', $project?->logistic_needs) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ===== SECTION COLLABORATEURS ===== --}}
        @if(!$readonly)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold">+</div>
                    <h3 class="section-title text-base">Collaborateurs de la communication</h3>
                </div>
            </div>
            <div class="card-body">
                <p class="text-sm text-gray-500 mb-4">Ajoutez autant de collaborateurs que nécessaire.</p>

                <div id="collaborateurs-list" class="space-y-4">
                    @php $collabs = old('collaborateurs', $project?->collaborators?->toArray() ?? [[]]) @endphp
                    @foreach($collabs as $idx => $collab)
                    <div class="collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3" data-index="{{ $idx }}">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-700">Collaborateur {{ $idx + 1 }}</p>
                            @if($idx > 0)
                            <button type="button" onclick="removeCollaborateur(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="form-label text-xs">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="collaborateurs[{{ $idx }}][nom]" value="{{ $collab['nom'] ?? '' }}" class="input-field text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Prénom</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][prenom]" value="{{ $collab['prenom'] ?? '' }}" class="input-field text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Email</label>
                                <input type="email" name="collaborateurs[{{ $idx }}][email]" value="{{ $collab['email'] ?? '' }}" class="input-field text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Téléphone</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][telephone]" value="{{ $collab['telephone'] ?? '' }}" class="input-field text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Institution</label>
                                <input type="text" name="collaborateurs[{{ $idx }}][institution]" value="{{ $collab['institution'] ?? '' }}" class="input-field text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Rôle</label>
                                <select name="collaborateurs[{{ $idx }}][role]" class="input-field text-sm">
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
        {{-- Mode lecture seule : afficher les collaborateurs --}}
        @if($project?->collaborators?->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="section-title text-base">Collaborateurs</h3>
            </div>
            <div class="card-body space-y-3">
                @foreach($project->collaborators as $collab)
                <div class="flex items-start gap-3 text-sm border-b border-gray-50 pb-3 last:border-0 last:pb-0">
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

        {{-- Boutons --}}
        @if(!$readonly)
        <div class="flex items-center gap-3 pb-6">
            <button type="submit" name="save_draft" value="1" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Sauvegarder le brouillon
            </button>
            <a href="{{ route('porteur.dashboard') }}" class="btn-secondary">Retour</a>
        </div>
        @else
        <div class="flex gap-3 pb-6">
            <a href="{{ route('porteur.dashboard') }}" class="btn-secondary">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour au tableau de bord
            </a>
        </div>
        @endif
    </form>
</div>
@push('scripts')
<script>
let collabCount = document.querySelectorAll('.collaborateur-row').length;

function addCollaborateur() {
    const idx = collabCount++;
    const rolesHtml = `{{ $collaboratorRoles->map(fn($o) => '<option value="'.$o->value.'">'.$o->label.'</option>')->implode('') }}`;
    const html = `
    <div class="collaborateur-row border border-gray-200 rounded-xl p-4 space-y-3" data-index="${idx}">
        <div class="flex items-center justify-between mb-1">
            <p class="text-sm font-medium text-gray-700">Collaborateur ${idx + 1}</p>
            <button type="button" onclick="removeCollaborateur(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div><label class="form-label text-xs">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="collaborateurs[${idx}][nom]" class="input-field text-sm"></div>
            <div><label class="form-label text-xs">Prénom</label>
                <input type="text" name="collaborateurs[${idx}][prenom]" class="input-field text-sm"></div>
            <div><label class="form-label text-xs">Email</label>
                <input type="email" name="collaborateurs[${idx}][email]" class="input-field text-sm"></div>
            <div><label class="form-label text-xs">Téléphone</label>
                <input type="text" name="collaborateurs[${idx}][telephone]" class="input-field text-sm"></div>
            <div><label class="form-label text-xs">Institution</label>
                <input type="text" name="collaborateurs[${idx}][institution]" class="input-field text-sm"></div>
            <div><label class="form-label text-xs">Rôle</label>
                <select name="collaborateurs[${idx}][role]" class="input-field text-sm">
                    <option value="">– Sélectionner –</option>${rolesHtml}
                </select></div>
        </div>
    </div>`;
    document.getElementById('collaborateurs-list').insertAdjacentHTML('beforeend', html);
}

function removeCollaborateur(btn) {
    btn.closest('.collaborateur-row').remove();
}
</script>
@endpush
@endsection
