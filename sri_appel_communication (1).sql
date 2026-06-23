-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 04:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sri_appel_communication`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_configs`
--

CREATE TABLE `event_configs` (
  `id` bigint UNSIGNED NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_description` text COLLATE utf8mb4_unicode_ci,
  `organizer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_start_date` date DEFAULT NULL,
  `event_end_date` date DEFAULT NULL,
  `submission_open_at` datetime DEFAULT NULL,
  `submission_close_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `show_questionnaire` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_configs`
--

INSERT INTO `event_configs` (`id`, `event_name`, `event_slug`, `event_description`, `organizer`, `event_start_date`, `event_end_date`, `submission_open_at`, `submission_close_at`, `is_active`, `show_questionnaire`, `created_at`, `updated_at`) VALUES
(1, 'SERI 2026', 'sri-2026', 'Semaine de la Recherche et de l\'Innovation de l\'Université Cheikh Anta Diop de Dakar', 'Direction de la Recherche et de l\'Innovation – UCAD', '2026-12-01', '2026-12-05', '2026-09-01 08:00:00', '2026-11-01 23:59:00', 1, 0, '2026-06-23 10:26:48', '2026-06-23 15:27:18');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_options`
--

CREATE TABLE `form_options` (
  `id` bigint UNSIGNED NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_options`
--

INSERT INTO `form_options` (`id`, `group`, `label`, `value`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'scientific_domain', 'Sciences et Technologies', 'sciences_technologies', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(2, 'scientific_domain', 'Sciences de la Santé', 'sciences_sante', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(3, 'scientific_domain', 'Sciences Humaines et Sociales', 'sciences_humaines_sociales', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(4, 'scientific_domain', 'Sciences Juridiques et Politiques', 'sciences_juridiques', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(5, 'scientific_domain', 'Sciences Économiques et de Gestion', 'sciences_economiques', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(6, 'scientific_domain', 'Lettres et Arts', 'lettres_arts', 6, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(7, 'scientific_domain', 'Environnement et Développement Durable', 'environnement', 7, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(8, 'scientific_domain', 'Agriculture et Agroalimentaire', 'agriculture', 8, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(9, 'scientific_domain', 'Numérique et Intelligence Artificielle', 'numerique_ia', 9, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(10, 'project_type', 'Recherche fondamentale', 'recherche', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(11, 'project_type', 'Innovation', 'innovation', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(12, 'project_type', 'Prototype', 'prototype', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(13, 'project_type', 'Solution appliquée', 'solution_appliquee', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(14, 'maturity_level', 'Idée / Concept', 'concept', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(15, 'maturity_level', 'Prototype', 'prototype', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(16, 'maturity_level', 'Testé / Validé', 'teste', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(17, 'maturity_level', 'Déployé / En service', 'deploye', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(18, 'protection_type', 'Brevet', 'brevet', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(19, 'protection_type', 'Droit d\'auteur', 'droit_auteur', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(20, 'protection_type', 'Marque déposée', 'marque', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(21, 'protection_type', 'Secret industriel', 'secret', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(22, 'protection_type', 'Dessins et modèles', 'dessins_modeles', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(23, 'protection_type', 'Semi-conducteur', 'semi_conducteur', 6, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(24, 'protection_type', 'Certificat végétal', 'certificat_vegetal', 7, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(25, 'protection_type', 'Base de données', 'base_donnees', 8, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(26, 'protection_type', 'Droits voisins', 'droits_voisins', 9, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(27, 'valorisation_type', 'Publication scientifique', 'publication', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(28, 'valorisation_type', 'Start-up / Spin-off', 'startup', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(29, 'valorisation_type', 'Transfert de technologie', 'transfert_technologique', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(30, 'valorisation_type', 'Transfert de savoir-faire', 'transfert_savoir_faire', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(31, 'valorisation_type', 'Contrats d\'exploitation', 'contrats_exploitation', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(32, 'valorisation_type', 'Coopération scientifique', 'cooperation', 6, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(33, 'valorisation_type', 'Valorisation sociale', 'sociale', 7, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(34, 'valorisation_type', 'Valorisation économique', 'economique', 8, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(35, 'impact_type', 'Scientifique', 'scientifique', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(36, 'impact_type', 'Économique', 'economique', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(37, 'impact_type', 'Social', 'social', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(38, 'impact_type', 'Environnemental', 'environnemental', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(39, 'impact_type', 'Culturel', 'culturel', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(40, 'presentation_format', 'Poster', 'poster', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(41, 'presentation_format', 'Démonstration', 'demonstration', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(42, 'presentation_format', 'Pitch', 'pitch', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(43, 'presentation_format', 'Stand', 'stand', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(44, 'presentation_format', 'Communication orale', 'communication_orale', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(45, 'participant_type', 'Chercheur / Enseignant-chercheur', 'chercheur', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(46, 'participant_type', 'Étudiant', 'etudiant', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(47, 'participant_type', 'Partenaire institutionnel', 'partenaire', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(48, 'participant_type', 'Journaliste / Média', 'media', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(49, 'participant_type', 'Grand public', 'public', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(50, 'collaborator_role', 'Co-auteur', 'co_auteur', 1, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(51, 'collaborator_role', 'Chercheur', 'chercheur', 2, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(52, 'collaborator_role', 'Ingénieur', 'ingenieur', 3, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(53, 'collaborator_role', 'Doctorant', 'doctorant', 4, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(54, 'collaborator_role', 'Technicien', 'technicien', 5, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49'),
(55, 'collaborator_role', 'Partenaire', 'partenaire', 6, 1, '2026-06-23 10:26:49', '2026-06-23 10:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000001_create_structures_table', 1),
(5, '2025_01_01_000002_add_structure_fk_to_users_table', 1),
(6, '2025_01_01_000003_create_user_structures_table', 1),
(7, '2025_01_01_000004_create_project_assignments_table', 1),
(8, '2025_01_01_000005_create_projects_table', 1),
(9, '2026_06_23_101930_add_secretaire_role_to_users', 2),
(10, '2026_06_23_101935_create_event_configs_table', 2),
(11, '2026_06_23_101935_create_form_options_table', 2),
(12, '2026_06_23_101936_create_project_collaborators_table', 2),
(13, '2026_06_23_101936_create_registrations_table', 2),
(14, '2026_06_23_101937_create_questionnaires_table', 2),
(15, '2026_06_23_105720_add_email_personnel_to_users_table', 3),
(16, '2026_06_23_110812_create_permission_tables', 4),
(17, '2026_06_23_110820_change_role_column_to_string_in_users_table', 5),
(18, '2026_06_23_110948_add_label_to_roles_and_permissions_tables', 6),
(19, '2026_06_23_154823_add_show_questionnaire_to_event_configs_table', 7),
(20, '2026_06_23_162944_add_email_professionnel_to_projects_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `label`, `group`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'users.viewAny', 'Voir la liste des utilisateurs', 'Utilisateurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(2, 'users.create', 'Créer un utilisateur', 'Utilisateurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(3, 'users.update', 'Modifier un utilisateur', 'Utilisateurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(4, 'users.delete', 'Supprimer un utilisateur', 'Utilisateurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(5, 'roles.manage', 'Gérer les rôles & permissions', 'Utilisateurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(6, 'events.manage', 'Configurer les événements', 'Configuration', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(7, 'form-options.manage', 'Gérer les options de formulaire', 'Configuration', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(8, 'porteurs.manage', 'Créer / modifier des porteurs', 'Porteurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(9, 'porteurs.credentials', 'Envoyer les identifiants porteur', 'Porteurs', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(10, 'projects.viewAll', 'Consulter tous les projets soumis', 'Projets', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(11, 'projects.select', 'Sélectionner / valider des projets', 'Projets', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(12, 'submission-period.manage', 'Définir la période de soumission', 'Projets', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(13, 'inscriptions.manage', 'Gérer les inscriptions publiques', 'Événement public', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(14, 'questionnaires.view', 'Consulter les questionnaires', 'Événement public', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `assignment_id` bigint UNSIGNED NOT NULL,
  `porteur_id` bigint UNSIGNED NOT NULL,
  `structure_id` bigint UNSIGNED NOT NULL,
  `responsable_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_professionnel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scientific_domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_types` json NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `problematic` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `solution` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `results` text COLLATE utf8mb4_unicode_ci,
  `maturity_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `protection_types` json DEFAULT NULL,
  `protection_autres` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valorisation_types` json DEFAULT NULL,
  `valorisation_autres` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `impact_types` json DEFAULT NULL,
  `presentation_formats` json DEFAULT NULL,
  `presentation_autres` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logistic_needs` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `selected` tinyint(1) NOT NULL DEFAULT '0',
  `selected_at` timestamp NULL DEFAULT NULL,
  `selected_by` bigint UNSIGNED DEFAULT NULL,
  `email_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `assignment_id`, `porteur_id`, `structure_id`, `responsable_nom`, `contact_email`, `email_professionnel`, `contact_phone`, `scientific_domain`, `project_types`, `summary`, `problematic`, `solution`, `results`, `maturity_level`, `protection_types`, `protection_autres`, `valorisation_types`, `valorisation_autres`, `impact_types`, `presentation_formats`, `presentation_autres`, `logistic_needs`, `status`, `selected`, `selected_at`, `selected_by`, `email_sent_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 8, 'Neko San', 'arame2001.an@gmail.com', NULL, '787887878', 'La vue', '[\"recherche\"]', '\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possim', '\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"', '\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"', '\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"', 'prototype', '[\"brevet\"]', NULL, '[\"publication\"]', NULL, '[\"scientifique\"]', '[\"pitch\"]', NULL, NULL, 'submitted', 1, '2026-06-22 09:43:02', 6, '2026-06-22 09:43:13', '2026-06-22 09:39:29', '2026-06-22 09:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `project_assignments`
--

CREATE TABLE `project_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `porteur_id` bigint UNSIGNED NOT NULL,
  `structure_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_assignments`
--

INSERT INTO `project_assignments` (`id`, `porteur_id`, `structure_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 35, 'La vue dans le noir', 'submitted', '2026-06-19 11:01:57', '2026-06-22 10:07:16'),
(2, 3, 35, 'La vie dans le noir', 'pending', '2026-06-19 11:01:57', '2026-06-22 10:07:16'),
(3, 3, 35, 'Rire dans le noir', 'pending', '2026-06-19 11:01:57', '2026-06-22 10:07:16'),
(4, 4, 35, 'IA dans la JUNGLE', 'pending', '2026-06-19 11:08:54', '2026-06-22 10:07:16'),
(5, 4, 35, 'La jungle dans l\'IA', 'pending', '2026-06-19 11:08:54', '2026-06-22 10:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `project_collaborators`
--

CREATE TABLE `project_collaborators` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_collaborateur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questionnaires`
--

CREATE TABLE `questionnaires` (
  `id` bigint UNSIGNED NOT NULL,
  `event_config_id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_organisation` tinyint UNSIGNED DEFAULT NULL,
  `note_contenu` tinyint UNSIGNED DEFAULT NULL,
  `note_logistique` tinyint UNSIGNED DEFAULT NULL,
  `note_globale` tinyint UNSIGNED DEFAULT NULL,
  `points_positifs` text COLLATE utf8mb4_unicode_ci,
  `points_amelioration` text COLLATE utf8mb4_unicode_ci,
  `suggestions` text COLLATE utf8mb4_unicode_ci,
  `recommanderait` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint UNSIGNED NOT NULL,
  `event_config_id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_participant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `presence_confirmee` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `event_config_id`, `nom`, `prenom`, `email`, `telephone`, `institution`, `fonction`, `type_participant`, `qr_code`, `presence_confirmee`, `created_at`, `updated_at`) VALUES
(1, 1, 'NIANG', 'N\'Dèye Arame', 'arame2001.an@gmail.com', '781627716', 'UCAD', 'Etudiant', 'partenaire', '4bf65cee-2859-4a1c-b862-e9bffaa0a1aa', 0, '2026-06-23 16:09:15', '2026-06-23 16:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Super Administrateur', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(2, 'direction_recherche', 'Organisateur (DR)', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(3, 'comite_scientifique', 'Comité Scientifique', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(4, 'secretaire', 'Comité Secrétaire', 'web', '2026-06-23 11:10:09', '2026-06-23 12:01:54'),
(5, 'point_focal', 'Observateur', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09'),
(6, 'porteur_projet', 'Porteur de Projet', 'web', '2026-06-23 11:10:09', '2026-06-23 11:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(1, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(12, 2),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 4),
(14, 4),
(10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0qZtoseudwGd9PKsuRKMTe0PGSaJR4qH3zzoONEz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0JVVVliWlJqSzJqSHFQb290MTR2V3V4bk5LbExlN0pESU4wajZsSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zcmktYXBwZWwtY29tbXVuaWNhdGlvbi5uZWtvIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782120722),
('ee7KnaQKDBfZ57MphCgIpwff2CWnrqvG53B68pJV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaURNS0pwMExaZzJJaDN2SWJSS1MxN3I2VUkzMzkxVXAwUHNHMzFvUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zcmktYXBwZWwtY29tbXVuaWNhdGlvbi5uZWtvIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782120722),
('pkMocqqr4kALajiyhYKAfGO4PPVstpD4ukkqqWa0', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUxZYkdGV1BkUDJMOThhM1A2YlhsblRVcTlpV0htVzJsZUlTcjhsTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3Qvc3JpLWFwcGVsLWNvbW11bmljYXRpb24vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1782121640),
('qLjjDM1ZSydbOirUlWqvqCbaQXX2mB88DngOrh94', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic3N4RmFXVHpjUGVuMEliM3hadzJFRHZyeFFTUkF4bXZBNzU0Qk01SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zcmktYXBwZWwtY29tbXVuaWNhdGlvbi5uZWtvIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782120722),
('TqmWFDFUpp7nclJHowNJYgVZEScgXtvLbXkuiZOV', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2ZOV21iQ1hXdDFFWXdybVFxYkk1RGFUNEw5Z0lqSW5pMzBubVNMUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3Qvc3JpLWFwcGVsLWNvbW11bmljYXRpb24vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1782120917);

-- --------------------------------------------------------

--
-- Table structure for table `structures`
--

CREATE TABLE `structures` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acronym` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `structures`
--

INSERT INTO `structures` (`id`, `name`, `acronym`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Faculté des Lettres et Sciences Humaines', 'FLSH', NULL, NULL, NULL),
(2, 'Faculté de Médecine, de Pharmacie et d\'Odonto-Stomatologie', 'FMPO', NULL, NULL, NULL),
(3, 'Faculté des Sciences et Techniques', 'FST', NULL, NULL, NULL),
(4, 'Faculté des Sciences Juridiques et Politiques', 'FSJP', NULL, NULL, NULL),
(5, 'Faculté des Sciences Économiques et de Gestion', 'FASEG', NULL, NULL, NULL),
(6, 'Faculté des Sciences et Technologies de l\'Éducation et de la Formation', 'FASTEF', NULL, NULL, NULL),
(7, 'École Supérieure Polytechnique', 'ESP', NULL, NULL, NULL),
(8, 'École des Bibliothécaires, Archivistes et Documentalistes', 'EBAD', NULL, NULL, NULL),
(9, 'École Normale Supérieure d\'Enseignement Technique et Professionnel', 'ENSETP', NULL, NULL, NULL),
(10, 'École Supérieure d\'Économie Appliquée', 'ESEA', NULL, NULL, NULL),
(11, 'École Nationale Supérieure des Mines et de la Géologie', 'ENSMG', NULL, NULL, NULL),
(12, 'École Nationale de Développement Sanitaire et Social', 'ENDSS', NULL, NULL, NULL),
(13, 'Institut Supérieur de Formation à Distance', 'ISFAD', NULL, NULL, NULL),
(14, 'Institut Supérieur des Arts et des Cultures', 'ISAC', NULL, NULL, NULL),
(15, 'Institut National Supérieur d\'Éducation Physique et Sportive', 'INSEPS', NULL, NULL, NULL),
(16, 'Institut Fondamental d\'Afrique Noire', 'IFAN', NULL, NULL, NULL),
(17, 'Institut de Français pour les Étudiants Étrangers', 'IFE', NULL, NULL, NULL),
(18, 'Institut de Santé et Développement', 'ISED', NULL, NULL, NULL),
(19, 'Institut de Médecine Tropicale Appliquée', 'IMTA', NULL, NULL, NULL),
(20, 'Institut de Technologie Nucléaire Appliquée', 'ITNA', NULL, NULL, NULL),
(21, 'Institut de Pédiatrie Sociale', 'IPS', NULL, NULL, NULL),
(22, 'Institut de Recherches sur l\'Enseignement de la Mathématique, de la Physique et de la Technologie', 'IREMPT', NULL, NULL, NULL),
(23, 'Institut de Recherches et d\'Enseignement de Psychopathologie (ex CRPP)', 'IREP', NULL, NULL, NULL),
(24, 'Institut des Droits de l\'Homme et de la Paix', 'IDHP', NULL, NULL, NULL),
(25, 'Institut de Formation et de Recherche en Population, Développement et Santé de la Reproduction', 'IPDSR', NULL, NULL, NULL),
(26, 'Institut de Gouvernance Territoriale et de Développement Local', 'IGTDL', NULL, NULL, NULL),
(27, 'Institut Universitaire de Pêche et d\'Aquaculture', 'IUPA', NULL, NULL, NULL),
(28, 'Institut de Formation en Administration et Création d\'Entreprise', 'IFACE', NULL, NULL, NULL),
(29, 'Institut de Prévoyance Médico-Social', 'IPMS', NULL, NULL, NULL),
(30, 'Institut Confucius', 'IC', NULL, NULL, NULL),
(31, 'Institut Africain de Lutte contre le Cancer', 'IALC', NULL, NULL, NULL),
(32, 'Institut des Politiques Publiques', 'IPP', NULL, NULL, NULL),
(33, 'Institut d\'Égyptologie', 'IEGY', NULL, NULL, NULL),
(34, 'Institut des Sciences du Médicament', 'ISMED', NULL, NULL, NULL),
(35, 'Centre d\'Études des Sciences et Techniques de l\'Information', 'CESTI', NULL, NULL, NULL),
(36, 'Centre de Linguistique Appliquée de Dakar', 'CLAD', NULL, NULL, NULL),
(37, 'Centre d\'Études et de Recherches sur les Énergies Renouvelables', 'CERER', NULL, NULL, NULL),
(38, 'Centre Universitaire de Recherche et de Formations aux Technologies de l\'Internet', 'CURI', NULL, NULL, NULL),
(39, 'Centre d\'Incubation et de Développement d\'Entreprises Innovantes', 'INNODEV', NULL, NULL, NULL),
(40, 'École Doctorale des Sciences Juridiques, Politiques, Économiques et de Gestion', 'EDJPEG', NULL, NULL, NULL),
(41, 'École Doctorale Sciences de la Vie, de la Santé et de l\'Environnement', 'EDSEV', NULL, NULL, NULL),
(42, 'École Doctorale Arts, Cultures et Civilisations', 'EDARCIV', NULL, NULL, NULL),
(43, 'École Doctorale Eau, Qualité et Usage de l\'Eau', 'EDEQUE', NULL, NULL, NULL),
(44, 'École Doctorale sur l\'Homme et la Société', 'ED ETHOS', NULL, NULL, NULL),
(45, 'École Doctorale Physique, Chimie, Sciences de la Terre, de l\'Univers et de l\'Ingénieur', 'PCSTUI', NULL, NULL, NULL),
(46, 'École Doctorale Mathématiques et Informatique', 'EDMI', NULL, NULL, NULL),
(47, 'Rectorat', 'RECTORAT', NULL, NULL, NULL),
(48, 'Bibliothèque Universitaire', 'BU', NULL, NULL, NULL),
(49, 'Office du Baccalauréat', 'OB', NULL, NULL, NULL),
(50, 'WASCAL', 'WAS', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_personnel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'porteur_projet',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `structure_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_personnel`, `phone`, `email_verified_at`, `password`, `role`, `is_active`, `structure_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ndèye Arame Niang', 'ndeyearame2.niang@ucad.edu.sn', NULL, NULL, NULL, '$2y$12$QaHoz1y2DkchSQ050epdFeuzL1L0FRU3IgLw5LbHNdBXmseF9W9pa', 'superadmin', 1, NULL, NULL, '2026-06-18 11:25:49', '2026-06-18 11:25:49'),
(2, 'direction recherche', 'arame2006.an@gmail.com', NULL, '787887878', NULL, '$2y$12$CXyy5./l5zHdQEJmmrjylupCG4vhUhI8Cu1aNHfzmnKEA4QSl/8lW', 'direction_recherche', 1, 47, NULL, '2026-06-19 10:58:51', '2026-06-19 11:24:35'),
(3, 'Neko SAN', 'arame2001.an@gmail.com', NULL, '787887878', NULL, '$2y$12$mUdosOXimyYjHQyRCeyhf.fEe91A4AsneEkUhQAdpDpw95cI39Rm.', 'porteur_projet', 1, 35, NULL, '2026-06-19 11:01:57', '2026-06-22 10:49:45'),
(4, 'N\'Dèye Arame Informaticienne NIANG', 'arame2003.an@gmail.com', NULL, '+221 0781627716', NULL, '$2y$12$3ZRJ.fwYLU08B8JePHO3leNLuza8zZDquH8gVWL2yqXdGitN0uHAu', 'porteur_projet', 1, 35, NULL, '2026-06-19 11:08:54', '2026-06-22 10:44:09'),
(5, 'N\'Dèye Arame  NIANG', 'arame2004.an@gmail.com', NULL, '+221 781627716', NULL, '$2y$12$A2gg.g3trbvi04lZAeBXo.6mI4C1OsTwj3oYa48oOT8ATDd33Jj76', 'point_focal', 1, 35, NULL, '2026-06-19 11:11:21', '2026-06-19 11:22:01'),
(6, 'Amana Sarr', 'arame2005.an@gmail.com', NULL, '78988888', NULL, '$2y$12$NLDR74tZvnRXOKwERImsweiuGIcyQEe4CWzYLFMf25vrvI0Job7Te', 'comite_scientifique', 1, NULL, NULL, '2026-06-19 11:50:15', '2026-06-19 11:50:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_structures`
--

CREATE TABLE `user_structures` (
  `user_id` bigint UNSIGNED NOT NULL,
  `structure_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_structures`
--

INSERT INTO `user_structures` (`user_id`, `structure_id`, `created_at`, `updated_at`) VALUES
(5, 8, '2026-06-19 11:11:22', '2026-06-19 11:11:22'),
(5, 35, '2026-06-19 11:46:50', '2026-06-19 11:46:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `event_configs`
--
ALTER TABLE `event_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_configs_event_slug_unique` (`event_slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `form_options`
--
ALTER TABLE `form_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `form_options_group_value_unique` (`group`,`value`),
  ADD KEY `form_options_group_index` (`group`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_assignment_id_foreign` (`assignment_id`),
  ADD KEY `projects_porteur_id_foreign` (`porteur_id`),
  ADD KEY `projects_structure_id_foreign` (`structure_id`),
  ADD KEY `projects_selected_by_foreign` (`selected_by`);

--
-- Indexes for table `project_assignments`
--
ALTER TABLE `project_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_assignments_porteur_id_foreign` (`porteur_id`),
  ADD KEY `project_assignments_structure_id_foreign` (`structure_id`);

--
-- Indexes for table `project_collaborators`
--
ALTER TABLE `project_collaborators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_collaborators_project_id_foreign` (`project_id`);

--
-- Indexes for table `questionnaires`
--
ALTER TABLE `questionnaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionnaires_event_config_id_foreign` (`event_config_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registrations_event_config_id_foreign` (`event_config_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `structures`
--
ALTER TABLE `structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_structure_id_foreign` (`structure_id`);

--
-- Indexes for table `user_structures`
--
ALTER TABLE `user_structures`
  ADD PRIMARY KEY (`user_id`,`structure_id`),
  ADD KEY `user_structures_structure_id_foreign` (`structure_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event_configs`
--
ALTER TABLE `event_configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_options`
--
ALTER TABLE `form_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_assignments`
--
ALTER TABLE `project_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_collaborators`
--
ALTER TABLE `project_collaborators`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionnaires`
--
ALTER TABLE `questionnaires`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `structures`
--
ALTER TABLE `structures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `project_assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_porteur_id_foreign` FOREIGN KEY (`porteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_selected_by_foreign` FOREIGN KEY (`selected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_assignments`
--
ALTER TABLE `project_assignments`
  ADD CONSTRAINT `project_assignments_porteur_id_foreign` FOREIGN KEY (`porteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_assignments_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_collaborators`
--
ALTER TABLE `project_collaborators`
  ADD CONSTRAINT `project_collaborators_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questionnaires`
--
ALTER TABLE `questionnaires`
  ADD CONSTRAINT `questionnaires_event_config_id_foreign` FOREIGN KEY (`event_config_id`) REFERENCES `event_configs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_event_config_id_foreign` FOREIGN KEY (`event_config_id`) REFERENCES `event_configs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_structures`
--
ALTER TABLE `user_structures`
  ADD CONSTRAINT `user_structures_structure_id_foreign` FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_structures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
