# Synchronisation de la configuration locale → production

Ces scripts transfèrent UNIQUEMENT la configuration (événement SRI 2026, contenu de la
page publique, catalogue d'options de formulaire) vers le serveur — aucune table
métier (utilisateurs, structures, projets, inscriptions...) n'est touchée.

## Étapes côté serveur (SSH)

```bash
cd /var/www/sri2026
git pull origin main

# 1. Config événement + contenu page publique
mysql -u <user> -p <database> < deploy/sync_config.sql

# 2. Catalogue d'options de formulaire (idempotent, sans risque de doublon)
mysql -u <user> -p <database> < deploy/sync_form_options.sql

# 3. Copier les images référencées par le contenu de la page publique
cp deploy/sections/*.jpg storage/app/public/sections/

# 4. Vider les caches
php artisan config:clear
php artisan view:clear
php artisan view:cache
php artisan config:cache
```

Remplacez `<user>` et `<database>` par les identifiants MySQL réels du serveur
(voir `.env` du serveur : `DB_USERNAME`, `DB_DATABASE`).

## Ce qui est transféré

- `deploy/sync_config.sql` — met à jour la ligne `event_configs` correspondant à
  `event_slug = 'sri-2026'` (dates, branding, quotas, mode délibération, profils
  d'identification…), puis remplace entièrement les blocs `content_blocks` de cet
  événement (texte d'intro, sections de la page publique, pied de page).
- `deploy/sync_form_options.sql` — insère/actualise le catalogue d'options
  (domaines scientifiques, types de projet, etc.) par `(group, value)`, sans
  jamais dupliquer une ligne existante.
- `deploy/sections/*.jpg` — images référencées par les sections de contenu ci-dessus.

## Ce qui n'est PAS transféré (à faire séparément si besoin)

- Le `.env` du serveur (tokens `STUDENTCENTER_API_*` / `PERSONNEL_API_*`) — à éditer
  manuellement, jamais versionné par git.
- Les utilisateurs, structures, inscriptions, projets soumis — restent ceux du serveur.
