<?php

// Catalogue fini : capacité Spatie => lien du dashboard générique. Une capacité sans
// entrée ici est simplement omise de la navigation générique (compatible avec les
// capacités "futures" déjà seedées mais pas encore reliées à une vraie route).
return [
    'projects.viewAll' => [
        'route' => 'generic.projects.index',
        'icon'  => 'briefcase',
        'label' => 'Projets soumis',
    ],
    'evaluation.score' => [
        'route' => 'deliberation.scoring.index',
        'icon'  => 'star',
        'label' => 'Noter les projets soumis',
    ],
    'evaluation.viewRanking' => [
        'route' => 'evaluation.ranking.index',
        'icon'  => 'check-circle',
        'label' => 'Classement & sélection',
    ],
    'evaluation.manageRubric' => [
        'route' => 'evaluation.rubrics.index',
        'icon'  => 'cog',
        'label' => 'Grille d\'évaluation',
    ],
];
