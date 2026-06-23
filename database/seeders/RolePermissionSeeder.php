<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ──────────────────────────────────────────────────────
        $permissions = [
            ['name' => 'users.viewAny',          'label' => 'Voir la liste des utilisateurs',       'group' => 'Utilisateurs'],
            ['name' => 'users.create',            'label' => 'Créer un utilisateur',                  'group' => 'Utilisateurs'],
            ['name' => 'users.update',            'label' => 'Modifier un utilisateur',               'group' => 'Utilisateurs'],
            ['name' => 'users.delete',            'label' => 'Supprimer un utilisateur',              'group' => 'Utilisateurs'],
            ['name' => 'roles.manage',            'label' => 'Gérer les rôles & permissions',         'group' => 'Utilisateurs'],
            ['name' => 'events.manage',           'label' => 'Configurer les événements',             'group' => 'Configuration'],
            ['name' => 'form-options.manage',     'label' => 'Gérer les options de formulaire',       'group' => 'Configuration'],
            ['name' => 'porteurs.manage',         'label' => 'Créer / modifier des porteurs',         'group' => 'Porteurs'],
            ['name' => 'porteurs.credentials',    'label' => 'Envoyer les identifiants porteur',      'group' => 'Porteurs'],
            ['name' => 'projects.viewAll',        'label' => 'Consulter tous les projets soumis',     'group' => 'Projets'],
            ['name' => 'projects.select',         'label' => 'Sélectionner / valider des projets',    'group' => 'Projets'],
            ['name' => 'submission-period.manage','label' => 'Définir la période de soumission',      'group' => 'Projets'],
            ['name' => 'inscriptions.manage',     'label' => 'Gérer les inscriptions publiques',      'group' => 'Événement public'],
            ['name' => 'questionnaires.view',     'label' => 'Consulter les questionnaires',          'group' => 'Événement public'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(
                ['name' => $p['name'], 'guard_name' => 'web'],
                ['label' => $p['label'], 'group' => $p['group']]
            );
        }

        // ── Rôles & affectation des permissions ──────────────────────────────
        $roles = [
            [
                'name'        => 'superadmin',
                'label'       => 'Super Administrateur',
                'permissions' => Permission::all()->pluck('name')->toArray(),
            ],
            [
                'name'        => 'direction_recherche',
                'label'       => 'Organisateur (DR)',
                'permissions' => [
                    'events.manage', 'form-options.manage',
                    'porteurs.manage', 'porteurs.credentials',
                    'projects.viewAll', 'submission-period.manage',
                    'users.viewAny',
                ],
            ],
            [
                'name'        => 'comite_scientifique',
                'label'       => 'Comité Scientifique',
                'permissions' => [
                    'porteurs.manage', 'porteurs.credentials',
                    'projects.viewAll', 'projects.select',
                    'submission-period.manage',
                ],
            ],
            [
                'name'        => 'secretaire',
                'label'       => 'Secrétaire',
                'permissions' => ['inscriptions.manage', 'questionnaires.view'],
            ],
            [
                'name'        => 'point_focal',
                'label'       => 'Observateur',
                'permissions' => ['projects.viewAll'],
            ],
            [
                'name'        => 'porteur_projet',
                'label'       => 'Porteur de Projet',
                'permissions' => [],
            ],
        ];

        foreach ($roles as $rd) {
            $role = Role::firstOrCreate(
                ['name' => $rd['name'], 'guard_name' => 'web'],
                ['label' => $rd['label']]
            );
            $role->update(['label' => $rd['label']]);
            $role->syncPermissions($rd['permissions']);
        }

        // ── Assigner les rôles Spatie aux users existants ────────────────────
        \App\Models\User::all()->each(function ($user) {
            if ($user->role && !$user->hasRole($user->role)) {
                try {
                    $user->syncRoles([$user->role]);
                } catch (\Exception) {}
            }
        });
    }
}
