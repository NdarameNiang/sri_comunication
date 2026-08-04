<?php

namespace App\Http\Controllers\Concerns;

use App\Models\FormOption;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Validation et sauvegarde du formulaire de soumission (Standard/Approfondi), partagées
 * entre le flux authentifié (PorteurProjet\ProjectController) et le dépôt public sans
 * compte (Public\ProjectSubmissionController) — même règles, même comportement, une seule
 * source de vérité.
 */
trait HandlesProjectSubmission
{
    /**
     * required_if:champ.*,valeur ne fonctionne pas sur les tableaux (checkboxes multiples) —
     * Laravel compare la valeur brute du tableau entier, jamais un de ses éléments. Génère
     * l'équivalent fonctionnel : requis seulement si $needle figure dans le tableau soumis
     * sous $arrayField.
     */
    private function requiredIfArrayContains(Request $request, string $arrayField, string $needle): array
    {
        return [
            Rule::requiredIf(fn () => in_array($needle, (array) $request->input($arrayField, []))),
            'nullable',
        ];
    }

    protected function loadFormOptions(): array
    {
        return [
            'scientific_domain'   => FormOption::forGroup('scientific_domain'),
            'project_type'        => FormOption::forGroup('project_type'),
            'maturity_level'      => FormOption::forGroup('maturity_level'),
            'protection_type'     => FormOption::forGroup('protection_type'),
            'valorisation_type'   => FormOption::forGroup('valorisation_type'),
            'impact_type'         => FormOption::forGroup('impact_type'),
            'presentation_format' => FormOption::forGroup('presentation_format'),
        ];
    }

    protected function saveCollaborators(Project $project, array $collaborateurs, string $category = 'collaborateur'): void
    {
        $relation = $category === 'co_porteur' ? $project->coPorteurs() : $project->collaborators();
        $relation->delete();
        foreach ($collaborateurs as $collab) {
            if (!empty(trim($collab['nom'] ?? ''))) {
                $relation->create([
                    'nom'               => $collab['nom'] ?? '',
                    'prenom'            => $collab['prenom'] ?? null,
                    'email'             => $collab['email'] ?? null,
                    'telephone'         => $collab['telephone'] ?? null,
                    'institution'       => $collab['institution'] ?? null,
                    'role_collaborateur'=> $collab['role'] ?? null,
                    'category'          => $category,
                ]);
            }
        }
    }

    protected function saveApprofondiDetails(Project $project, Request $request): void
    {
        $splitCsv = fn (?string $v) => $v ? array_values(array_filter(array_map('trim', explode(',', $v)))) : [];

        $project->approfondiDetails()->updateOrCreate(['project_id' => $project->id], [
            'laboratoire_nom'           => $request->input('laboratoire_nom'),
            'laboratoire_acronyme'      => $request->input('laboratoire_acronyme'),
            'laboratoire_site_web'      => $request->input('laboratoire_site_web'),
            'responsable_titre'         => $request->input('responsable_titre'),
            'responsable_fonction'      => $request->input('responsable_fonction'),
            'titre_complet'             => $request->input('titre_complet'),
            'acronyme_projet'           => $request->input('acronyme_projet'),
            'sous_domaines'             => $splitCsv($request->input('sous_domaines_text')),
            'mots_cles'                 => $splitCsv($request->input('mots_cles_text')),
            'date_demarrage'            => $request->input('date_demarrage'),
            'duree_prevue'              => $request->input('duree_prevue'),
            'contexte_etat_art'         => $request->input('contexte_etat_art'),
            'approche_methodologique'   => $request->input('approche_methodologique'),
            'caractere_innovant'        => $request->input('caractere_innovant'),
            'resultats_scientifiques'   => $request->input('resultats_scientifiques'),
            'resultats_techniques'      => $request->input('resultats_techniques'),
            'indicateurs_chiffres'      => $request->input('indicateurs_chiffres'),
            'trl_level'                 => $request->input('trl_level'),
            'voies_valorisation'        => $request->input('voies_valorisation', []),
            'propriete_intellectuelle'  => $request->input('propriete_intellectuelle'),
            'modele_economique'         => $request->input('modele_economique'),
            'partenariats_financement'  => $request->input('partenariats_financement'),
            'dimensions_impact'         => $request->input('dimensions_impact', []),
            'beneficiaires'             => $request->input('beneficiaires'),
            'indicateurs_impact'        => $request->input('indicateurs_impact'),
            'contribution_odd'          => $request->input('contribution_odd'),
            'pertinence_senegal_afrique'=> $request->input('pertinence_senegal_afrique'),
            'public_cible_vise'         => $request->input('public_cible_vise'),
            'supports_prevus'           => $request->input('supports_prevus'),
            'annexes_checklist'         => $request->input('annexes_checklist', []),
            'annexes_autres_texte'      => $request->input('annexes_autres_texte'),
            'media_link'                => $request->input('media_link'),
        ]);
    }

    protected function validateForm(Request $request): array
    {
        return $request->validate([
            'responsable_nom'                   => 'required|string|max:255',
            'contact_email'                     => 'required|email|max:255',
            'email_professionnel'               => 'required|email|max:255',
            'contact_phone'                     => ['required', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'scientific_domain'                 => 'required|string|max:255',
            'scientific_domain_autre'           => 'required_if:scientific_domain,autres|nullable|string|max:255',
            'project_types'                     => 'required|array|min:1',
            'project_types.*'                   => 'string|max:100',
            'project_types_autres'              => $this->requiredIfArrayContains($request, 'project_types', 'autres') + ['string', 'max:500'],
            'summary'                           => 'required|string|min:50',
            'problematic'                       => 'required|string|min:20',
            'solution'                          => 'required|string|min:20',
            'results'                           => 'required|string',
            'maturity_level'                    => 'required|string|max:100',
            'maturity_level_autre'              => 'required_if:maturity_level,autres|nullable|string|max:255',
            'protection_types'                  => 'required|array|min:1',
            'protection_types.*'                => 'string|max:100',
            'protection_autres'                 => $this->requiredIfArrayContains($request, 'protection_types', 'autres') + ['string', 'max:500'],
            'valorisation_types'                => 'required|array|min:1',
            'valorisation_types.*'              => 'string|max:100',
            'valorisation_autres'               => $this->requiredIfArrayContains($request, 'valorisation_types', 'autres') + ['string', 'max:500'],
            'impact_types'                      => 'required|array|min:1',
            'impact_types.*'                    => 'string|max:100',
            'impact_types_autres'               => $this->requiredIfArrayContains($request, 'impact_types', 'autres') + ['string', 'max:500'],
            'presentation_formats'              => 'required|array|min:1',
            'presentation_formats.*'            => 'string|max:100',
            'presentation_autres'               => $this->requiredIfArrayContains($request, 'presentation_formats', 'autres') + ['string', 'max:500'],
            'logistic_needs'                    => 'required|string',
            // Collaborateurs
            'collaborateurs'                    => 'nullable|array|max:20',
            'collaborateurs.*.nom'              => 'required_with:collaborateurs.*.prenom,collaborateurs.*.email|nullable|string|max:255',
            'collaborateurs.*.prenom'           => 'nullable|string|max:255',
            'collaborateurs.*.email'            => 'nullable|email|max:255',
            'collaborateurs.*.telephone'        => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'collaborateurs.*.institution'      => 'nullable|string|max:255',
            'collaborateurs.*.role'             => 'nullable|string|max:100',
        ], [
            'summary.min'     => 'Le résumé doit contenir au moins 50 caractères.',
            'problematic.min' => 'La problématique doit contenir au moins 20 caractères.',
            'solution.min'    => 'La solution doit contenir au moins 20 caractères.',
            'project_types.required' => 'Veuillez sélectionner au moins un type de projet.',
            'protection_types.required' => 'Veuillez sélectionner au moins un type de protection.',
            'valorisation_types.required' => 'Veuillez sélectionner au moins un type de valorisation.',
            'impact_types.required' => 'Veuillez sélectionner au moins un type d\'impact.',
            'presentation_formats.required' => 'Veuillez sélectionner au moins un format de présentation.',
            'contact_phone.regex' => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
            'collaborateurs.*.nom.required_with' => 'Le nom est requis pour chaque collaborateur renseigné.',
        ]);
    }

    protected function validateApprofondiForm(Request $request): array
    {
        $data = $request->validate([
            'responsable_nom'                   => 'required|string|max:255',
            'contact_email'                     => 'required|email|max:255',
            'email_professionnel'               => 'required|email|max:255',
            'contact_phone'                     => ['required', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'scientific_domain'                 => 'required|string|max:255',
            'scientific_domain_autre'           => 'required_if:scientific_domain,autres|nullable|string|max:255',
            'project_types'                     => 'required|array|min:1',
            'project_types.*'                   => 'string|max:100',
            'project_types_autres'              => $this->requiredIfArrayContains($request, 'project_types', 'autres') + ['string', 'max:500'],
            'summary'                           => 'required|string|min:50',
            'problematic'                       => 'required|string|min:20',
            'solution'                          => 'required|string|min:20',
            'results'                           => 'nullable|string',
            'protection_types'                  => 'required|array|min:1',
            'protection_types.*'                => 'string|max:100',
            'protection_autres'                 => $this->requiredIfArrayContains($request, 'protection_types', 'autres') + ['string', 'max:500'],
            'valorisation_types'                => 'required|array|min:1',
            'valorisation_types.*'              => 'string|max:100',
            'valorisation_autres'               => $this->requiredIfArrayContains($request, 'valorisation_types', 'autres') + ['string', 'max:500'],
            'presentation_formats'              => 'required|array|min:1',
            'presentation_formats.*'            => 'string|max:100',
            'presentation_autres'               => $this->requiredIfArrayContains($request, 'presentation_formats', 'autres') + ['string', 'max:500'],
            'logistic_needs'                    => 'required|string',

            // Section A — identité
            'laboratoire_nom'                   => 'required|string|max:255',
            'laboratoire_acronyme'              => 'nullable|string|max:100',
            'laboratoire_site_web'              => 'nullable|url|max:255',
            'responsable_titre'                 => 'required|string|max:255',
            'responsable_fonction'              => 'required|string|max:255',
            'titre_complet'                     => 'required|string|max:500',
            'acronyme_projet'                   => 'nullable|string|max:100',
            'sous_domaines_text'                => 'required|string|max:500',
            'mots_cles_text'                    => 'required|string|max:500',
            'date_demarrage'                    => 'required|date',
            'duree_prevue'                      => 'required|string|max:100',

            // Section B — description scientifique
            'contexte_etat_art'                 => 'required|string',
            'approche_methodologique'           => 'required|string',
            'caractere_innovant'                => 'required|string',

            // Section C — résultats
            'resultats_scientifiques'           => 'required|string',
            'resultats_techniques'              => 'required|string',
            'indicateurs_chiffres'              => 'required|string',

            // Section D — maturité
            'trl_level'                         => 'required|string|max:100',
            'voies_valorisation'                => 'required|array|min:1',
            'voies_valorisation.*'              => 'string|max:100',
            'propriete_intellectuelle'          => 'required|string',
            'modele_economique'                 => 'required|string',
            'partenariats_financement'          => 'required|string',

            // Section E — impact
            'dimensions_impact'                 => 'required|array|min:1',
            'dimensions_impact.*'               => 'string|max:100',
            'beneficiaires'                     => 'required|string',
            'indicateurs_impact'                => 'required|string',
            'contribution_odd'                  => 'required|string',
            'pertinence_senegal_afrique'         => 'required|string',

            // Section F — présentation SRI
            'public_cible_vise'                 => 'required|string',
            'supports_prevus'                   => 'required|string',

            // Section G — annexes
            'annexes_checklist'                 => 'required|array|min:1',
            'annexes_checklist.*'               => 'string|max:100',
            'annexes_autres_texte'              => $this->requiredIfArrayContains($request, 'annexes_checklist', 'autres') + ['string', 'max:500'],
            'media_link'                        => $this->requiredIfArrayContains($request, 'annexes_checklist', 'photos_videos') + ['url', 'max:500'],

            // Co-porteurs
            'co_porteurs'                       => 'nullable|array|max:10',
            'co_porteurs.*.nom'                 => 'required_with:co_porteurs.*.prenom,co_porteurs.*.email|nullable|string|max:255',
            'co_porteurs.*.prenom'              => 'nullable|string|max:255',
            'co_porteurs.*.email'               => 'nullable|email|max:255',
            'co_porteurs.*.institution'         => 'nullable|string|max:255',

            // Collaborateurs
            'collaborateurs'                    => 'nullable|array|max:20',
            'collaborateurs.*.nom'              => 'required_with:collaborateurs.*.prenom,collaborateurs.*.email|nullable|string|max:255',
            'collaborateurs.*.prenom'           => 'nullable|string|max:255',
            'collaborateurs.*.email'            => 'nullable|email|max:255',
            'collaborateurs.*.telephone'        => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'collaborateurs.*.institution'      => 'nullable|string|max:255',
            'collaborateurs.*.role'             => 'nullable|string|max:100',
        ], [
            'summary.min'          => 'Le résumé doit contenir au moins 50 caractères.',
            'problematic.min'      => 'La problématique doit contenir au moins 20 caractères.',
            'solution.min'         => 'La solution doit contenir au moins 20 caractères.',
            'project_types.required' => 'Veuillez sélectionner au moins un type de projet.',
            'protection_types.required' => 'Veuillez sélectionner au moins un type de protection.',
            'valorisation_types.required' => 'Veuillez sélectionner au moins un type de valorisation.',
            'presentation_formats.required' => 'Veuillez sélectionner au moins un format de présentation.',
            'voies_valorisation.required' => 'Veuillez sélectionner au moins une voie de valorisation.',
            'dimensions_impact.required' => 'Veuillez sélectionner au moins une dimension d\'impact.',
            'annexes_checklist.required' => 'Veuillez sélectionner au moins une annexe.',
            'media_link.required_if' => 'Veuillez fournir un lien vers vos photos/vidéos.',
            'media_link.url' => 'Le lien doit être une URL valide.',
            'contact_phone.regex' => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
            'laboratoire_nom.required' => 'Le nom du laboratoire est requis.',
            'titre_complet.required'   => 'Le titre complet du projet est requis.',
            'collaborateurs.*.nom.required_with' => 'Le nom est requis pour chaque collaborateur renseigné.',
            'co_porteurs.*.nom.required_with'    => 'Le nom est requis pour chaque co-porteur renseigné.',
        ]);

        // Champs propres à project_approfondi_details : ne pas les envoyer à Project::create/update.
        return collect($data)->except([
            'laboratoire_nom', 'laboratoire_acronyme', 'laboratoire_site_web',
            'responsable_titre', 'responsable_fonction',
            'titre_complet', 'acronyme_projet', 'sous_domaines_text', 'mots_cles_text',
            'date_demarrage', 'duree_prevue',
            'contexte_etat_art', 'approche_methodologique', 'caractere_innovant',
            'resultats_scientifiques', 'resultats_techniques', 'indicateurs_chiffres',
            'trl_level', 'voies_valorisation', 'propriete_intellectuelle',
            'modele_economique', 'partenariats_financement',
            'dimensions_impact', 'beneficiaires', 'indicateurs_impact',
            'contribution_odd', 'pertinence_senegal_afrique',
            'public_cible_vise', 'supports_prevus',
            'annexes_checklist', 'annexes_autres_texte', 'media_link',
            'co_porteurs', 'collaborateurs',
        ])->toArray();
    }
}
