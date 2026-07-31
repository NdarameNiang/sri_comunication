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
];
