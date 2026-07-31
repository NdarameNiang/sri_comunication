<?php

namespace Database\Seeders;

use App\Models\FormOption;
use Illuminate\Database\Seeder;

class FormOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Domaines scientifiques
            ['group' => 'scientific_domain', 'label' => 'Sciences et Technologies',          'value' => 'sciences_technologies',       'sort_order' => 1],
            ['group' => 'scientific_domain', 'label' => 'Sciences de la Santé',              'value' => 'sciences_sante',              'sort_order' => 2],
            ['group' => 'scientific_domain', 'label' => 'Sciences Humaines et Sociales',     'value' => 'sciences_humaines_sociales',  'sort_order' => 3],
            ['group' => 'scientific_domain', 'label' => 'Sciences Juridiques et Politiques', 'value' => 'sciences_juridiques',         'sort_order' => 4],
            ['group' => 'scientific_domain', 'label' => 'Sciences Économiques et de Gestion','value' => 'sciences_economiques',        'sort_order' => 5],
            ['group' => 'scientific_domain', 'label' => 'Lettres et Arts',                   'value' => 'lettres_arts',               'sort_order' => 6],
            ['group' => 'scientific_domain', 'label' => 'Environnement et Développement Durable','value' => 'environnement',          'sort_order' => 7],
            ['group' => 'scientific_domain', 'label' => 'Agriculture et Agroalimentaire',    'value' => 'agriculture',                'sort_order' => 8],
            ['group' => 'scientific_domain', 'label' => 'Numérique et Intelligence Artificielle','value' => 'numerique_ia',           'sort_order' => 9],
            ['group' => 'scientific_domain', 'label' => 'Autre',                             'value' => 'autres', 'sort_order' => 10, 'is_other' => true],

            // Types de projet
            ['group' => 'project_type', 'label' => 'Recherche fondamentale', 'value' => 'recherche',          'sort_order' => 1],
            ['group' => 'project_type', 'label' => 'Innovation',             'value' => 'innovation',         'sort_order' => 2],
            ['group' => 'project_type', 'label' => 'Prototype',              'value' => 'prototype',          'sort_order' => 3],
            ['group' => 'project_type', 'label' => 'Solution appliquée',     'value' => 'solution_appliquee', 'sort_order' => 4],
            ['group' => 'project_type', 'label' => 'Autres',                 'value' => 'autres', 'sort_order' => 5, 'is_other' => true],

            // Niveaux de maturité
            ['group' => 'maturity_level', 'label' => 'Idée / Concept',       'value' => 'concept',   'sort_order' => 1],
            ['group' => 'maturity_level', 'label' => 'Prototype',            'value' => 'prototype', 'sort_order' => 2],
            ['group' => 'maturity_level', 'label' => 'Testé / Validé',       'value' => 'teste',     'sort_order' => 3],
            ['group' => 'maturity_level', 'label' => 'Déployé / En service', 'value' => 'deploye',   'sort_order' => 4],
            ['group' => 'maturity_level', 'label' => 'Autre',                'value' => 'autres', 'sort_order' => 5, 'is_other' => true],

            // Types de protection
            ['group' => 'protection_type', 'label' => 'Brevet',                      'value' => 'brevet',            'sort_order' => 1],
            ['group' => 'protection_type', 'label' => 'Droit d\'auteur',             'value' => 'droit_auteur',      'sort_order' => 2],
            ['group' => 'protection_type', 'label' => 'Marque déposée',              'value' => 'marque',            'sort_order' => 3],
            ['group' => 'protection_type', 'label' => 'Secret industriel',           'value' => 'secret',            'sort_order' => 4],
            ['group' => 'protection_type', 'label' => 'Dessins et modèles',          'value' => 'dessins_modeles',   'sort_order' => 5],
            ['group' => 'protection_type', 'label' => 'Semi-conducteur',             'value' => 'semi_conducteur',   'sort_order' => 6],
            ['group' => 'protection_type', 'label' => 'Certificat végétal',          'value' => 'certificat_vegetal','sort_order' => 7],
            ['group' => 'protection_type', 'label' => 'Base de données',             'value' => 'base_donnees',      'sort_order' => 8],
            ['group' => 'protection_type', 'label' => 'Droits voisins',              'value' => 'droits_voisins',    'sort_order' => 9],
            // Alias historiques : d'anciens slugs codés en dur (Project::protectionLabels())
            // ont pu être enregistrés sur des projets avant que ce groupe soit piloté par FormOption.
            ['group' => 'protection_type', 'label' => 'Certificat d\'obtention végétale', 'value' => 'certificat_vegetale', 'sort_order' => 10, 'is_active' => false],
            ['group' => 'protection_type', 'label' => 'Droits voisins',                   'value' => 'droit_voisins',       'sort_order' => 11, 'is_active' => false],
            ['group' => 'protection_type', 'label' => 'Autres',                      'value' => 'autres', 'sort_order' => 12, 'is_other' => true],

            // Types de valorisation
            ['group' => 'valorisation_type', 'label' => 'Publication scientifique',  'value' => 'publication',            'sort_order' => 1],
            ['group' => 'valorisation_type', 'label' => 'Start-up / Spin-off',       'value' => 'startup',                'sort_order' => 2],
            ['group' => 'valorisation_type', 'label' => 'Transfert de technologie',  'value' => 'transfert_technologique', 'sort_order' => 3],
            ['group' => 'valorisation_type', 'label' => 'Transfert de savoir-faire', 'value' => 'transfert_savoir_faire',  'sort_order' => 4],
            ['group' => 'valorisation_type', 'label' => 'Contrats d\'exploitation',  'value' => 'contrats_exploitation',   'sort_order' => 5],
            ['group' => 'valorisation_type', 'label' => 'Coopération scientifique',  'value' => 'cooperation',            'sort_order' => 6],
            ['group' => 'valorisation_type', 'label' => 'Valorisation sociale',      'value' => 'sociale',                'sort_order' => 7],
            ['group' => 'valorisation_type', 'label' => 'Valorisation économique',   'value' => 'economique',             'sort_order' => 8],
            ['group' => 'valorisation_type', 'label' => 'Autres',                    'value' => 'autres', 'sort_order' => 9, 'is_other' => true],

            // Types d'impact
            ['group' => 'impact_type', 'label' => 'Scientifique',    'value' => 'scientifique',    'sort_order' => 1],
            ['group' => 'impact_type', 'label' => 'Économique',      'value' => 'economique',      'sort_order' => 2],
            ['group' => 'impact_type', 'label' => 'Social',          'value' => 'social',          'sort_order' => 3],
            ['group' => 'impact_type', 'label' => 'Environnemental', 'value' => 'environnemental', 'sort_order' => 4],
            ['group' => 'impact_type', 'label' => 'Culturel',        'value' => 'culturel',        'sort_order' => 5],
            ['group' => 'impact_type', 'label' => 'Autres',          'value' => 'autres', 'sort_order' => 6, 'is_other' => true],

            // Formats de présentation
            ['group' => 'presentation_format', 'label' => 'Poster',          'value' => 'poster',        'sort_order' => 1],
            ['group' => 'presentation_format', 'label' => 'Démonstration',   'value' => 'demonstration', 'sort_order' => 2],
            ['group' => 'presentation_format', 'label' => 'Pitch',           'value' => 'pitch',         'sort_order' => 3],
            ['group' => 'presentation_format', 'label' => 'Stand',           'value' => 'stand',         'sort_order' => 4],
            ['group' => 'presentation_format', 'label' => 'Communication orale', 'value' => 'communication_orale', 'sort_order' => 5],
            ['group' => 'presentation_format', 'label' => 'Conférence',      'value' => 'conference', 'sort_order' => 6],
            ['group' => 'presentation_format', 'label' => 'Atelier',         'value' => 'atelier', 'sort_order' => 7],
            ['group' => 'presentation_format', 'label' => 'Autres',          'value' => 'autres', 'sort_order' => 8, 'is_other' => true],

            // Types de participant (inscription)
            ['group' => 'participant_type', 'label' => 'Chercheur / Enseignant-chercheur', 'value' => 'chercheur',   'sort_order' => 1],
            ['group' => 'participant_type', 'label' => 'Étudiant',                         'value' => 'etudiant',    'sort_order' => 2],
            ['group' => 'participant_type', 'label' => 'Partenaire institutionnel',         'value' => 'partenaire',  'sort_order' => 3],
            ['group' => 'participant_type', 'label' => 'Journaliste / Média',              'value' => 'media',       'sort_order' => 4],
            ['group' => 'participant_type', 'label' => 'Grand public',                     'value' => 'public',      'sort_order' => 5],

            // Catégories de population (ciblage d'audience à l'inscription)
            ['group' => 'population_category', 'label' => 'PER — Personnel Enseignant-Chercheur',     'value' => 'per',                'sort_order' => 1],
            ['group' => 'population_category', 'label' => 'PATS — Personnel Administratif, Technique et de Service', 'value' => 'pats', 'sort_order' => 2],
            ['group' => 'population_category', 'label' => 'Étudiant — Licence',                        'value' => 'etudiant_licence',   'sort_order' => 3],
            ['group' => 'population_category', 'label' => 'Étudiant — Master',                         'value' => 'etudiant_master',    'sort_order' => 4],
            ['group' => 'population_category', 'label' => 'Étudiant — Doctorat',                       'value' => 'etudiant_doctorat',  'sort_order' => 5],

            // Rôles de collaborateur
            ['group' => 'collaborator_role', 'label' => 'Co-auteur',       'value' => 'co_auteur',   'sort_order' => 1],
            ['group' => 'collaborator_role', 'label' => 'Chercheur',       'value' => 'chercheur',   'sort_order' => 2],
            ['group' => 'collaborator_role', 'label' => 'Ingénieur',       'value' => 'ingenieur',   'sort_order' => 3],
            ['group' => 'collaborator_role', 'label' => 'Doctorant',       'value' => 'doctorant',   'sort_order' => 4],
            ['group' => 'collaborator_role', 'label' => 'Technicien',      'value' => 'technicien',  'sort_order' => 5],
            ['group' => 'collaborator_role', 'label' => 'Partenaire',      'value' => 'partenaire',  'sort_order' => 6],

            // Niveaux TRL (soumission Approfondie, Section D)
            ['group' => 'trl_level', 'label' => 'TRL 1–3 : Idée / preuve de concept',                    'value' => 'trl_1_3', 'sort_order' => 1],
            ['group' => 'trl_level', 'label' => 'TRL 4–6 : Prototype validé en laboratoire',              'value' => 'trl_4_6', 'sort_order' => 2],
            ['group' => 'trl_level', 'label' => 'TRL 7–8 : Démonstration en environnement réel',          'value' => 'trl_7_8', 'sort_order' => 3],
            ['group' => 'trl_level', 'label' => 'TRL 9 : Solution déployée et opérationnelle',            'value' => 'trl_9',   'sort_order' => 4],

            // Voies de valorisation (soumission Approfondie, Section D)
            ['group' => 'voie_valorisation', 'label' => 'Publication scientifique',                'value' => 'publication_scientifique', 'sort_order' => 1],
            ['group' => 'voie_valorisation', 'label' => 'Brevet / Propriété intellectuelle',        'value' => 'brevet_pi',                'sort_order' => 2],
            ['group' => 'voie_valorisation', 'label' => 'Création de start-up / spin-off',           'value' => 'startup_spinoff',          'sort_order' => 3],
            ['group' => 'voie_valorisation', 'label' => 'Transfert technologique vers un industriel', 'value' => 'transfert_industriel',    'sort_order' => 4],
            ['group' => 'voie_valorisation', 'label' => 'Licence d\'exploitation',                   'value' => 'licence_exploitation',     'sort_order' => 5],
            ['group' => 'voie_valorisation', 'label' => 'Politique publique / Recommandation',       'value' => 'politique_publique',       'sort_order' => 6],

            // Dimensions d'impact (soumission Approfondie, Section E)
            ['group' => 'impact_dimension', 'label' => 'Scientifique',     'value' => 'scientifique',    'sort_order' => 1],
            ['group' => 'impact_dimension', 'label' => 'Économique',       'value' => 'economique',      'sort_order' => 2],
            ['group' => 'impact_dimension', 'label' => 'Social',           'value' => 'social',          'sort_order' => 3],
            ['group' => 'impact_dimension', 'label' => 'Environnemental',  'value' => 'environnemental', 'sort_order' => 4],
            ['group' => 'impact_dimension', 'label' => 'Sanitaire',        'value' => 'sanitaire',       'sort_order' => 5],
            ['group' => 'impact_dimension', 'label' => 'Pédagogique',      'value' => 'pedagogique',     'sort_order' => 6],

            // Types d'annexes (soumission Approfondie, Section G)
            ['group' => 'annexe_type', 'label' => 'Pitch deck (présentation synthétique)',  'value' => 'pitch_deck',           'sort_order' => 1],
            ['group' => 'annexe_type', 'label' => 'Photos / vidéos du prototype',           'value' => 'photos_videos',        'sort_order' => 2],
            ['group' => 'annexe_type', 'label' => 'Liste des publications',                 'value' => 'liste_publications',   'sort_order' => 3],
            ['group' => 'annexe_type', 'label' => 'Lettres d\'engagement / partenariat',    'value' => 'lettres_engagement',   'sort_order' => 4],
            ['group' => 'annexe_type', 'label' => 'Justificatifs de propriété intellectuelle', 'value' => 'justificatifs_pi',  'sort_order' => 5],
            ['group' => 'annexe_type', 'label' => 'Autres',                                 'value' => 'autres', 'sort_order' => 6, 'is_other' => true],
        ];

        // upsert (pas insert/truncate) : idempotent et ne détruit pas les options
        // ajoutées depuis l'admin ou les lignes référencées par une FK ailleurs
        // (ex: event_audience_categories.form_option_id).
        FormOption::upsert(
            array_map(fn($o) => array_merge([
                'is_active' => true,
                'is_other'  => false,
            ], $o, [
                'created_at' => now(),
                'updated_at' => now(),
            ]), $options),
            ['group', 'value'],
            ['label', 'sort_order', 'is_active', 'is_other', 'updated_at']
        );
    }
}
