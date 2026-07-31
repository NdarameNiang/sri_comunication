<?php

namespace App\Http\Controllers\Concerns;

use App\Models\FormOption;
use App\Models\Project;
use Illuminate\Http\Request;

/**
 * Validation et sauvegarde du formulaire de soumission (Standard/Approfondi), partagées
 * entre le flux authentifié (PorteurProjet\ProjectController) et le dépôt public sans
 * compte (Public\ProjectSubmissionController) — même règles, même comportement, une seule
 * source de vérité.
 */
trait HandlesProjectSubmission
{
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
        ]);
    }

    protected function validateForm(Request $request): array
    {
        return $request->validate([
            'responsable_nom'                   => 'required|string|max:255',
            'contact_email'                     => 'required|email|max:255',
            'email_professionnel'               => 'nullable|email|max:255',
            'contact_phone'                     => 'nullable|string|max:20',
            'scientific_domain'                 => 'required|string|max:255',
            'scientific_domain_autre'           => 'nullable|string|max:255',
            'project_types'                     => 'required|array|min:1',
            'project_types.*'                   => 'string|max:100',
            'project_types_autres'              => 'nullable|string|max:500',
            'summary'                           => 'required|string|min:50',
            'problematic'                       => 'required|string|min:20',
            'solution'                          => 'required|string|min:20',
            'results'                           => 'nullable|string',
            'maturity_level'                    => 'nullable|string|max:100',
            'maturity_level_autre'              => 'nullable|string|max:255',
            'protection_types'                  => 'nullable|array',
            'protection_types.*'                => 'string|max:100',
            'protection_autres'                 => 'nullable|string|max:500',
            'valorisation_types'                => 'nullable|array',
            'valorisation_types.*'              => 'string|max:100',
            'valorisation_autres'               => 'nullable|string|max:500',
            'impact_types'                      => 'nullable|array',
            'impact_types.*'                    => 'string|max:100',
            'impact_types_autres'               => 'nullable|string|max:500',
            'presentation_formats'              => 'nullable|array',
            'presentation_formats.*'            => 'string|max:100',
            'presentation_autres'               => 'nullable|string|max:500',
            'logistic_needs'                    => 'nullable|string',
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
            'collaborateurs.*.nom.required_with' => 'Le nom est requis pour chaque collaborateur renseigné.',
        ]);
    }

    protected function validateApprofondiForm(Request $request): array
    {
        $data = $request->validate([
            'responsable_nom'                   => 'required|string|max:255',
            'contact_email'                     => 'required|email|max:255',
            'email_professionnel'               => 'nullable|email|max:255',
            'contact_phone'                     => 'nullable|string|max:20',
            'scientific_domain'                 => 'required|string|max:255',
            'scientific_domain_autre'           => 'nullable|string|max:255',
            'project_types'                     => 'required|array|min:1',
            'project_types.*'                   => 'string|max:100',
            'project_types_autres'              => 'nullable|string|max:500',
            'summary'                           => 'required|string|min:50',
            'problematic'                       => 'required|string|min:20',
            'solution'                          => 'required|string|min:20',
            'results'                           => 'nullable|string',
            'protection_types'                  => 'nullable|array',
            'protection_types.*'                => 'string|max:100',
            'protection_autres'                 => 'nullable|string|max:500',
            'valorisation_types'                => 'nullable|array',
            'valorisation_types.*'              => 'string|max:100',
            'valorisation_autres'               => 'nullable|string|max:500',
            'presentation_formats'              => 'nullable|array',
            'presentation_formats.*'            => 'string|max:100',
            'presentation_autres'               => 'nullable|string|max:500',
            'logistic_needs'                    => 'nullable|string',

            // Section A — identité
            'laboratoire_nom'                   => 'required|string|max:255',
            'laboratoire_acronyme'              => 'nullable|string|max:100',
            'laboratoire_site_web'              => 'nullable|url|max:255',
            'responsable_titre'                 => 'nullable|string|max:255',
            'responsable_fonction'              => 'nullable|string|max:255',
            'titre_complet'                     => 'required|string|max:500',
            'acronyme_projet'                   => 'nullable|string|max:100',
            'sous_domaines_text'                => 'nullable|string|max:500',
            'mots_cles_text'                    => 'nullable|string|max:500',
            'date_demarrage'                    => 'nullable|date',
            'duree_prevue'                      => 'nullable|string|max:100',

            // Section B — description scientifique
            'contexte_etat_art'                 => 'nullable|string',
            'approche_methodologique'           => 'nullable|string',
            'caractere_innovant'                => 'nullable|string',

            // Section C — résultats
            'resultats_scientifiques'           => 'nullable|string',
            'resultats_techniques'              => 'nullable|string',
            'indicateurs_chiffres'              => 'nullable|string',

            // Section D — maturité
            'trl_level'                         => 'nullable|string|max:100',
            'voies_valorisation'                => 'nullable|array',
            'voies_valorisation.*'              => 'string|max:100',
            'propriete_intellectuelle'          => 'nullable|string',
            'modele_economique'                 => 'nullable|string',
            'partenariats_financement'          => 'nullable|string',

            // Section E — impact
            'dimensions_impact'                 => 'nullable|array',
            'dimensions_impact.*'               => 'string|max:100',
            'beneficiaires'                     => 'nullable|string',
            'indicateurs_impact'                => 'nullable|string',
            'contribution_odd'                  => 'nullable|string',
            'pertinence_senegal_afrique'         => 'nullable|string',

            // Section F — présentation SRI
            'public_cible_vise'                 => 'nullable|string',
            'supports_prevus'                   => 'nullable|string',

            // Section G — annexes
            'annexes_checklist'                 => 'nullable|array',
            'annexes_checklist.*'               => 'string|max:100',
            'annexes_autres_texte'              => 'nullable|string|max:500',

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
            'annexes_checklist', 'annexes_autres_texte',
            'co_porteurs', 'collaborateurs',
        ])->toArray();
    }
}
