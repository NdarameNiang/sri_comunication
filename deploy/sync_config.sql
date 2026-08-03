-- Synchronise la configuration de l''événement sri-2026 (event_configs + content_blocks)

-- depuis le local vers le serveur, sans toucher aux autres tables (utilisateurs, projets, etc.).

-- Généré depuis la base locale le 2026-08-03 12:29:49.

START TRANSACTION;

UPDATE event_configs SET `event_name` = 'SRI 2026', `event_description` = 'Dans le cadre de la préparation de la Semaine de la Recherche et de l''Innovation (SRI 2026), qui se tiendra du 7 au 12 décembre 2026, la Direction de la Recherche et de l''Innovation (DRI) lance un appel à candidatures destiné à identifier les meilleurs projets de recherche et d''innovation développés par les chercheurs, enseignants-chercheurs et alumni.', `organizer` = 'Direction de la Recherche et de l''Innovation – UCAD', `landing_subtitle` = 'Semaine de la recherche et de l''innocation', `logo_path` = NULL, `logo_white_path` = NULL, `hero_image_path` = NULL, `primary_color` = '#2563eb', `event_start_date` = '2026-12-07', `event_end_date` = '2026-12-12', `submission_open_at` = '2026-08-03 08:00:00', `submission_close_at` = '2026-11-04 23:59:00', `inscription_open_at` = '2026-09-03 08:00:00', `inscription_close_at` = '2026-10-30 23:59:00', `show_questionnaire` = '0', `max_projects_per_structure` = '500', `max_projects_per_porteur` = NULL, `allow_public_submission` = '1', `evaluation_enabled` = '1', `deliberation_mode` = 'individuelle', `require_identity_verification` = '1', `enabled_profile_types` = '["etudiant"]', `selection_quota` = NULL, `evaluation_open_at` = NULL, `evaluation_close_at` = NULL WHERE event_slug = 'sri-2026';

DELETE FROM content_blocks WHERE event_config_id = (SELECT id FROM event_configs WHERE event_slug = 'sri-2026');

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, 'landing.intro', NULL, 'richtext', 'Dans le cadre de la préparation de la Semaine de la Recherche et de l''Innovation (SRI 2026), qui se tiendra du 7 au 12 décembre 2026, la Direction de la Recherche et de l''Innovation (DRI) lance un appel à candidatures destiné à identifier les meilleurs projets de recherche et d''innovation développés par les chercheurs, enseignants-chercheurs et alumni.', NULL, NULL, 0, 1, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Objectifs de l''appel', 'list', NULL, '[{"title": "Recenser", "description": "les projets de recherche et d''innovation les plus prometteurs"}, {"title": "Valoriser", "description": "les résultats de la recherche auprès des partenaires académiques, institutionnels, économiques et du grand public"}, {"title": "Promouvoir", "description": "le transfert de technologies et l''innovation au service du développement"}, {"title": "Identifier", "description": "des projets à fort potentiel de valorisation qui seront mis en avant durant la SRI 2026"}]', 'sections/cZanBlUOWaxgv013MPPx9zbvZiH7BwB7AP4XDDl0.jpg', 1, 1, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, 'landing.badge_text', NULL, 'text', 'Appel à candidatures', NULL, NULL, 0, 1, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, 'landing.footer', NULL, 'text', 'Direction de la Recherche et de l''Innovation · UCAD · Dakar, Sénégal', NULL, NULL, 0, 1, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Qui peut candidater ?', 'list', NULL, '[{"title": "", "description": "enseignants-chercheurs et chercheurs au sein des équipes de recherche, laboratoires, centres, instituts, écoles et facultés"}, {"title": "", "description": "doctorants, en association avec leur encadrement scientifique, lorsque le projet est suffisamment mature"}]', 'sections/XQFpSEhX9xo6RmJMglgL7F1VCQjUYxtocJt3xg74.jpg', 2, 1, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Projets attendus', 'list', NULL, '[{"title": "", "description": "des résultats de recherche valorisables"}, {"title": "", "description": "des innovations technologiques ou sociales"}, {"title": "", "description": "des prototypes ou démonstrateurs"}, {"title": "", "description": "des procédés, logiciels ou applications innovantes"}, {"title": "", "description": "des projets en partenariat avec le secteur socio-économique"}, {"title": "", "description": "des projets susceptibles de contribuer au développement durable ou au transfert de technologies"}]', NULL, 3, 0, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Modalités de soumission', 'richtext', 'Les candidatures devront être transmises exclusivement à l''aide des fiches de soumission mises à disposition par la DRI (formats Standard ou Approfondi). Chaque Faculté, École, Institut ou Laboratoire est invité à désigner un point focal chargé d''accompagner les porteurs de projets, de centraliser les candidatures et d''assurer le lien avec la DRI.', NULL, NULL, 4, 0, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Calendrier', 'list', NULL, '[{"title": "4 août 2026", "description": "lancement officiel de l''appel à candidatures"}, {"title": "4 octobre 2026 à 23h59", "description": "date limite de dépôt des candidatures"}, {"title": "Octobre 2026", "description": "présélection et validation des projets retenus"}, {"title": "7 au 12 décembre 2026", "description": "présentation des projets sélectionnés lors de la Semaine de la Recherche et de l''Innovation de l''UCAD"}]', NULL, 5, 0, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Critères de sélection', 'list', NULL, '[{"title": "", "description": "leur qualité scientifique et technique"}, {"title": "", "description": "leur caractère innovant"}, {"title": "", "description": "leur niveau de maturité"}, {"title": "", "description": "leur potentiel de valorisation et de transfert"}, {"title": "", "description": "leur impact scientifique, économique, social ou environnemental"}, {"title": "", "description": "leur contribution au rayonnement de l''UCAD"}]', NULL, 6, 0, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

INSERT INTO content_blocks (event_config_id, `key`, title, type, content, content_json, image_path, sort_order, is_active, created_at, updated_at)
SELECT id, NULL, 'Pour conclure', 'richtext', 'Les dossiers complets devront être transmis au plus tard le 4 octobre 2026 à 23h59 selon les modalités précisées par la Direction de la Recherche et de l''Innovation. La DRI invite l''ensemble des chercheurs et enseignants-chercheurs de l''UCAD à participer massivement à cet appel afin de mettre en lumière la richesse, la diversité et l''impact des activités de recherche et d''innovation conduites au sein de notre université.', NULL, NULL, 7, 0, NOW(), NOW()
FROM event_configs WHERE event_slug = 'sri-2026';

COMMIT;
