-- Rôles, permissions et leurs attributions Spatie (config système, identique partout).

START TRANSACTION;

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('superadmin', 'web', 'Super Administrateur', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('direction_recherche', 'web', 'Organisateur (DR)', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('comite_scientifique', 'web', 'Comité Scientifique', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('secretaire', 'web', 'Secrétaire', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('point_focal', 'web', 'Observateur', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO roles (name, guard_name, label, is_system, created_at, updated_at)
VALUES ('porteur_projet', 'web', 'Porteur de Projet', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), is_system=VALUES(is_system);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('users.viewAny', 'web', 'Voir la liste des utilisateurs', 'Utilisateurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('users.create', 'web', 'Créer un utilisateur', 'Utilisateurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('users.update', 'web', 'Modifier un utilisateur', 'Utilisateurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('users.delete', 'web', 'Supprimer un utilisateur', 'Utilisateurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('roles.manage', 'web', 'Gérer les rôles & permissions', 'Utilisateurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('events.manage', 'web', 'Configurer les événements', 'Configuration', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('form-options.manage', 'web', 'Gérer les options de formulaire', 'Configuration', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('porteurs.manage', 'web', 'Créer / modifier des porteurs', 'Porteurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('porteurs.credentials', 'web', 'Envoyer les identifiants porteur', 'Porteurs', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('projects.viewAll', 'web', 'Consulter tous les projets soumis', 'Projets', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('projects.select', 'web', 'Sélectionner / valider des projets', 'Projets', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('submission-period.manage', 'web', 'Définir la période de soumission', 'Projets', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('inscriptions.manage', 'web', 'Gérer les inscriptions publiques', 'Événement public', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('questionnaires.view', 'web', 'Consulter les questionnaires', 'Événement public', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('content-blocks.manage', 'web', 'Gérer le contenu de la page publique', 'Configuration', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.manageRubric', 'web', 'Configurer la grille d''évaluation', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.score', 'web', 'Évaluer des projets soumis', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.viewRanking', 'web', 'Consulter le classement', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.finalize', 'web', 'Finaliser le classement / quota', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.resolveTies', 'web', 'Départager manuellement les ex-æquo', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('evaluation.sendEmails', 'web', 'Envoyer les emails de sélection', 'Évaluation', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT INTO permissions (name, guard_name, label, `group`, created_at, updated_at)
VALUES ('inscriptions.manageAudience', 'web', 'Définir le public autorisé à l''inscription', 'Événement public', NOW(), NOW())
ON DUPLICATE KEY UPDATE label=VALUES(label), `group`=VALUES(`group`);

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.manage' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.credentials' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.viewAll' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.select' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'submission-period.manage' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.score' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.viewRanking' AND r.name = 'comite_scientifique';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'users.viewAny' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'events.manage' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'form-options.manage' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.manage' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.credentials' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.viewAll' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'submission-period.manage' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'content-blocks.manage' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.manageRubric' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.viewRanking' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.finalize' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.resolveTies' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.sendEmails' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'inscriptions.manageAudience' AND r.name = 'direction_recherche';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.viewAll' AND r.name = 'point_focal';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'inscriptions.manage' AND r.name = 'secretaire';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'questionnaires.view' AND r.name = 'secretaire';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'users.viewAny' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'users.create' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'users.update' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'users.delete' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'roles.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'events.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'form-options.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'porteurs.credentials' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.viewAll' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'projects.select' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'submission-period.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'inscriptions.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'questionnaires.view' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'content-blocks.manage' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.manageRubric' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.score' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.viewRanking' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.finalize' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.resolveTies' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'evaluation.sendEmails' AND r.name = 'superadmin';

INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id FROM permissions p, roles r WHERE p.name = 'inscriptions.manageAudience' AND r.name = 'superadmin';

COMMIT;
