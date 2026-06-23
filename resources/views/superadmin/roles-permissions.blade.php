@extends('layouts.app')
@section('title', 'Rôles & Permissions')
@section('page-title', 'Rôles & Permissions')
@section('page-subtitle', 'Matrice des droits par rôle dans la plateforme')

@section('content')
<div class="space-y-6">

    {{-- Stats par rôle --}}
    @php
    $roleColors = [
        'superadmin'          => 'bg-purple-100 text-purple-700 border-purple-200',
        'direction_recherche' => 'bg-blue-100 text-blue-700 border-blue-200',
        'comite_scientifique' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'secretaire'          => 'bg-amber-100 text-amber-700 border-amber-200',
        'point_focal'         => 'bg-sky-100 text-sky-700 border-sky-200',
        'porteur_projet'      => 'bg-rose-100 text-rose-700 border-rose-200',
    ];
    $roleLabels = [
        'superadmin'          => 'Super Administrateur',
        'direction_recherche' => 'Organisateur (DR)',
        'comite_scientifique' => 'Comité Scientifique',
        'secretaire'          => 'Secrétaire',
        'point_focal'         => 'Observateur',
        'porteur_projet'      => 'Porteur de Projet',
    ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach($roleLabels as $roleKey => $roleLabel)
        <div class="card p-4 text-center border {{ $roleColors[$roleKey] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
            <p class="text-2xl font-bold">{{ $stats[$roleKey] ?? 0 }}</p>
            <p class="text-xs font-medium mt-1 leading-tight">{{ $roleLabel }}</p>
        </div>
        @endforeach
    </div>

    {{-- Matrice des permissions --}}
    <div class="card overflow-hidden">
        <div class="card-header">
            <h3 class="section-title text-base">Matrice des droits</h3>
            <a href="{{ route('superadmin.users.index') }}" class="btn-primary text-xs">
                Gérer les utilisateurs
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="table text-xs">
                <thead>
                    <tr class="text-center">
                        <th class="text-left">Fonctionnalité</th>
                        <th class="text-purple-700">SuperAdmin</th>
                        <th class="text-blue-700">Organisateur</th>
                        <th class="text-emerald-700">Comité</th>
                        <th class="text-amber-700">Secrétaire</th>
                        <th class="text-sky-700">Observateur</th>
                        <th class="text-rose-700">Porteur</th>
                    </tr>
                </thead>
                <tbody>
                @php
                $perms = [
                    ['feat' => 'Tableau de bord personnalisé',                'sa'=>1,'dr'=>1,'cs'=>1,'se'=>1,'pf'=>1,'pp'=>1],
                    ['feat' => 'Gestion des utilisateurs (tous rôles)',        'sa'=>1,'dr'=>0,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Voir matrice Rôles & Permissions',             'sa'=>1,'dr'=>0,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Config événements (nom, dates)',               'sa'=>1,'dr'=>1,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Gestion options formulaire (listes)',          'sa'=>1,'dr'=>1,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Créer / modifier porteurs de projet',          'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Créer / modifier observateurs',                'sa'=>1,'dr'=>1,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Créer membres comité scientifique',            'sa'=>1,'dr'=>1,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Définir la période de soumission',             'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Consulter tous les projets soumis',            'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>1,'pp'=>0],
                    ['feat' => 'Sélectionner des projets',                     'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Remplir / modifier son dossier projet',        'sa'=>0,'dr'=>0,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>1],
                    ['feat' => 'Soumettre un projet',                          'sa'=>0,'dr'=>0,'cs'=>0,'se'=>0,'pf'=>0,'pp'=>1],
                    ['feat' => 'Voir contenu projet (lecture seule)',          'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>1,'pp'=>1],
                    ['feat' => 'Gérer inscriptions publiques',                 'sa'=>1,'dr'=>0,'cs'=>0,'se'=>1,'pf'=>0,'pp'=>0],
                    ['feat' => 'Confirmer présence (scan QR)',                 'sa'=>1,'dr'=>0,'cs'=>0,'se'=>1,'pf'=>0,'pp'=>0],
                    ['feat' => 'Consulter questionnaires d\'évaluation',       'sa'=>1,'dr'=>0,'cs'=>0,'se'=>1,'pf'=>0,'pp'=>0],
                    ['feat' => 'Envoi identifiants de connexion',              'sa'=>1,'dr'=>1,'cs'=>1,'se'=>0,'pf'=>0,'pp'=>0],
                    ['feat' => 'Modifier son mot de passe',                    'sa'=>1,'dr'=>1,'cs'=>1,'se'=>1,'pf'=>1,'pp'=>1],
                ];
                @endphp
                @foreach($perms as $p)
                <tr>
                    <td class="font-medium text-gray-700">{{ $p['feat'] }}</td>
                    @foreach(['sa','dr','cs','se','pf','pp'] as $col)
                    <td class="text-center">
                        @if($p[$col])
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('superadmin.users.create') }}" class="card p-5 flex items-center gap-3 hover:shadow-md transition-shadow group">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Créer un utilisateur</p>
                <p class="text-xs text-gray-400">Tous les rôles disponibles</p>
            </div>
        </a>
        <a href="{{ route('superadmin.users.index') }}" class="card p-5 flex items-center gap-3 hover:shadow-md transition-shadow group">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Liste des utilisateurs</p>
                <p class="text-xs text-gray-400">Activer / désactiver / modifier</p>
            </div>
        </a>
        <a href="{{ route('admin.event-configs.index') }}" class="card p-5 flex items-center gap-3 hover:shadow-md transition-shadow group">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Config événements</p>
                <p class="text-xs text-gray-400">Périodes, noms, paramètres</p>
            </div>
        </a>
    </div>

</div>
@endsection
