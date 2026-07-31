<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectApprofondiDetail extends Model
{
    protected $fillable = [
        'project_id',
        'laboratoire_nom', 'laboratoire_acronyme', 'laboratoire_site_web',
        'responsable_titre', 'responsable_fonction',
        'titre_complet', 'acronyme_projet', 'sous_domaines', 'mots_cles',
        'date_demarrage', 'duree_prevue',
        'contexte_etat_art', 'approche_methodologique', 'caractere_innovant',
        'resultats_scientifiques', 'resultats_techniques', 'indicateurs_chiffres',
        'trl_level', 'voies_valorisation', 'propriete_intellectuelle',
        'modele_economique', 'partenariats_financement',
        'dimensions_impact', 'beneficiaires', 'indicateurs_impact',
        'contribution_odd', 'pertinence_senegal_afrique',
        'public_cible_vise', 'supports_prevus',
        'annexes_checklist', 'annexes_autres_texte',
    ];

    protected function casts(): array
    {
        return [
            'sous_domaines'      => 'array',
            'mots_cles'          => 'array',
            'voies_valorisation' => 'array',
            'dimensions_impact'  => 'array',
            'annexes_checklist'  => 'array',
            'date_demarrage'     => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
