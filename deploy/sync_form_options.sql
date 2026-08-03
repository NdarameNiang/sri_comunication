-- Synchronise le catalogue form_options (idempotent, sans risque de doublon).
INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Sciences et Technologies', 'sciences_technologies', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Sciences de la Santé', 'sciences_sante', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Sciences Humaines et Sociales', 'sciences_humaines_sociales', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Sciences Juridiques et Politiques', 'sciences_juridiques', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Sciences Économiques et de Gestion', 'sciences_economiques', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Lettres et Arts', 'lettres_arts', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Environnement et Développement Durable', 'environnement', 7, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Agriculture et Agroalimentaire', 'agriculture', 8, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Numérique et Intelligence Artificielle', 'numerique_ia', 9, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('scientific_domain', 'Autre', 'autres', 10, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('project_type', 'Recherche fondamentale', 'recherche', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('project_type', 'Innovation', 'innovation', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('project_type', 'Prototype', 'prototype', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('project_type', 'Solution appliquée', 'solution_appliquee', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('project_type', 'Autres', 'autres', 5, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('maturity_level', 'Idée / Concept', 'concept', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('maturity_level', 'Prototype', 'prototype', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('maturity_level', 'Testé / Validé', 'teste', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('maturity_level', 'Déployé / En service', 'deploye', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('maturity_level', 'Autre', 'autres', 5, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Brevet', 'brevet', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Droit d''auteur', 'droit_auteur', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Marque déposée', 'marque', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Secret industriel', 'secret', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Dessins et modèles', 'dessins_modeles', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Semi-conducteur', 'semi_conducteur', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Certificat végétal', 'certificat_vegetal', 7, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Base de données', 'base_donnees', 8, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Droits voisins', 'droits_voisins', 9, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Certificat d''obtention végétale', 'certificat_vegetale', 10, 0, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Droits voisins', 'droit_voisins', 11, 0, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('protection_type', 'Autres', 'autres', 12, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Publication scientifique', 'publication', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Start-up / Spin-off', 'startup', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Transfert de technologie', 'transfert_technologique', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Transfert de savoir-faire', 'transfert_savoir_faire', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Contrats d''exploitation', 'contrats_exploitation', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Coopération scientifique', 'cooperation', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Valorisation sociale', 'sociale', 7, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Valorisation économique', 'economique', 8, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('valorisation_type', 'Autres', 'autres', 9, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Scientifique', 'scientifique', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Économique', 'economique', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Social', 'social', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Environnemental', 'environnemental', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Culturel', 'culturel', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_type', 'Autres', 'autres', 6, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Poster', 'poster', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Démonstration', 'demonstration', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Pitch', 'pitch', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Stand', 'stand', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Communication orale', 'communication_orale', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Autres', 'autres', 8, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('participant_type', 'Chercheur / Enseignant-chercheur', 'chercheur', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('participant_type', 'Étudiant', 'etudiant', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('participant_type', 'Partenaire institutionnel', 'partenaire', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('participant_type', 'Journaliste / Média', 'media', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('participant_type', 'Grand public', 'public', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Co-auteur', 'co_auteur', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Chercheur', 'chercheur', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Ingénieur', 'ingenieur', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Doctorant', 'doctorant', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Technicien', 'technicien', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('collaborator_role', 'Partenaire', 'partenaire', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('population_category', 'PER — Personnel Enseignant-Chercheur', 'per', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('population_category', 'PATS — Personnel Administratif, Technique et de Service', 'pats', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('population_category', 'Étudiant — Licence', 'etudiant_licence', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('population_category', 'Étudiant — Master', 'etudiant_master', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('population_category', 'Étudiant — Doctorat', 'etudiant_doctorat', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Conférence', 'conference', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('presentation_format', 'Atelier', 'atelier', 7, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('trl_level', 'TRL 1–3 : Idée / preuve de concept', 'trl_1_3', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('trl_level', 'TRL 4–6 : Prototype validé en laboratoire', 'trl_4_6', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('trl_level', 'TRL 7–8 : Démonstration en environnement réel', 'trl_7_8', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('trl_level', 'TRL 9 : Solution déployée et opérationnelle', 'trl_9', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Publication scientifique', 'publication_scientifique', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Brevet / Propriété intellectuelle', 'brevet_pi', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Création de start-up / spin-off', 'startup_spinoff', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Transfert technologique vers un industriel', 'transfert_industriel', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Licence d''exploitation', 'licence_exploitation', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('voie_valorisation', 'Politique publique / Recommandation', 'politique_publique', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Scientifique', 'scientifique', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Économique', 'economique', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Social', 'social', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Environnemental', 'environnemental', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Sanitaire', 'sanitaire', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('impact_dimension', 'Pédagogique', 'pedagogique', 6, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Pitch deck (présentation synthétique)', 'pitch_deck', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Photos / vidéos du prototype', 'photos_videos', 2, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Liste des publications', 'liste_publications', 3, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Lettres d''engagement / partenariat', 'lettres_engagement', 4, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Justificatifs de propriété intellectuelle', 'justificatifs_pi', 5, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);

INSERT INTO form_options (`group`, label, value, sort_order, is_active, is_other, created_at, updated_at)
VALUES ('annexe_type', 'Autres', 'autres', 6, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), sort_order=VALUES(sort_order), is_active=VALUES(is_active), is_other=VALUES(is_other);
