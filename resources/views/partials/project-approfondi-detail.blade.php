{{-- Détails de la soumission Approfondie — inclus à l'identique par les 4 vues
     "voir le projet" (porteur, comité, point focal, secrétaire) pour ne jamais
     dupliquer l'affichage 4 fois (c'est cette absence de partage qui avait causé
     la dérive des libellés corrigée en Phase 1). --}}
@php
    $d = $project->approfondiDetails;
    $trlLabels = \App\Models\Project::trlLabels();
    $voieLabels = \App\Models\Project::voieValorisationLabels();
    $impactDimLabels = \App\Models\Project::impactDimensionLabels();
    $annexeLabels = \App\Models\Project::annexeTypeLabels();
@endphp
@if($d)
<div class="bg-indigo-50 border border-indigo-100 rounded-2xl overflow-hidden">
    <div class="px-5 py-3 border-b border-indigo-100 bg-indigo-100/50">
        <h3 class="text-sm font-bold text-indigo-900">Soumission Approfondie — détails complémentaires</h3>
    </div>
    <div class="p-5 space-y-5">

        {{-- Section A --}}
        @if($d->laboratoire_nom || $d->titre_complet || $d->mots_cles || $d->date_demarrage)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">A — Carte d'identité</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                @if($d->laboratoire_nom)<div><p class="text-xs text-gray-400">Laboratoire / Équipe</p><p class="text-gray-800">{{ $d->laboratoire_nom }}{{ $d->laboratoire_acronyme ? ' ('.$d->laboratoire_acronyme.')' : '' }}</p></div>@endif
                @if($d->responsable_titre || $d->responsable_fonction)<div><p class="text-xs text-gray-400">Responsable scientifique</p><p class="text-gray-800">{{ $d->responsable_titre }} {{ $d->responsable_fonction }}</p></div>@endif
                @if($d->acronyme_projet)<div><p class="text-xs text-gray-400">Acronyme du projet</p><p class="text-gray-800">{{ $d->acronyme_projet }}</p></div>@endif
                @if($d->date_demarrage)<div><p class="text-xs text-gray-400">Démarrage</p><p class="text-gray-800">{{ $d->date_demarrage->format('d/m/Y') }}</p></div>@endif
                @if($d->duree_prevue)<div><p class="text-xs text-gray-400">Durée prévue</p><p class="text-gray-800">{{ $d->duree_prevue }}</p></div>@endif
            </div>
            @if($d->titre_complet)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">Titre complet</span>{{ $d->titre_complet }}</p>@endif
            @if(!empty($d->sous_domaines))<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">Sous-domaines</span>{{ implode(', ', $d->sous_domaines) }}</p>@endif
            @if(!empty($d->mots_cles))
            <div class="flex flex-wrap gap-1.5 mt-2">
                @foreach($d->mots_cles as $mc)<span class="text-xs px-2 py-0.5 rounded-full bg-white border border-indigo-200 text-indigo-700">{{ $mc }}</span>@endforeach
            </div>
            @endif
        </div>
        @endif

        {{-- Section B --}}
        @foreach([
            ['B1 — Contexte et état de l\'art', $d->contexte_etat_art],
            ['B3 — Approche méthodologique', $d->approche_methodologique],
            ['B4 — Caractère innovant', $d->caractere_innovant],
        ] as [$label, $text])
        @if($text)
        <div><p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">{{ $label }}</p><p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $text }}</p></div>
        @endif
        @endforeach

        {{-- Section C --}}
        @foreach([
            ['C1 — Résultats scientifiques', $d->resultats_scientifiques],
            ['C2 — Résultats techniques', $d->resultats_techniques],
            ['C3 — Indicateurs chiffrés', $d->indicateurs_chiffres],
        ] as [$label, $text])
        @if($text)
        <div><p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">{{ $label }}</p><p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $text }}</p></div>
        @endif
        @endforeach

        {{-- Section D --}}
        @if($d->trl_level || !empty($d->voies_valorisation) || $d->propriete_intellectuelle || $d->modele_economique || $d->partenariats_financement)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">D — Maturité et valorisation</p>
            @if($d->trl_level)<p class="text-sm text-gray-700 mb-2"><span class="font-medium">Niveau TRL :</span> {{ $trlLabels[$d->trl_level] ?? $d->trl_level }}</p>@endif
            @if(!empty($d->voies_valorisation))
            <div class="flex flex-wrap gap-1.5 mb-2">
                @foreach($d->voies_valorisation as $v)<span class="text-xs px-2 py-0.5 rounded-full bg-white border border-indigo-200 text-indigo-700">{{ $voieLabels[$v] ?? $v }}</span>@endforeach
            </div>
            @endif
            @if($d->propriete_intellectuelle)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">D1 — Propriété intellectuelle</span>{{ $d->propriete_intellectuelle }}</p>@endif
            @if($d->modele_economique)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">D2 — Modèle économique</span>{{ $d->modele_economique }}</p>@endif
            @if($d->partenariats_financement)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">D3 — Partenariats et financement</span>{{ $d->partenariats_financement }}</p>@endif
        </div>
        @endif

        {{-- Section E --}}
        @if(!empty($d->dimensions_impact) || $d->beneficiaires || $d->indicateurs_impact || $d->contribution_odd || $d->pertinence_senegal_afrique)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">E — Impact</p>
            @if(!empty($d->dimensions_impact))
            <div class="flex flex-wrap gap-1.5 mb-2">
                @foreach($d->dimensions_impact as $v)<span class="text-xs px-2 py-0.5 rounded-full bg-white border border-indigo-200 text-indigo-700">{{ $impactDimLabels[$v] ?? $v }}</span>@endforeach
            </div>
            @endif
            @if($d->beneficiaires)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">E1 — Bénéficiaires</span>{{ $d->beneficiaires }}</p>@endif
            @if($d->indicateurs_impact)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">E2 — Indicateurs d'impact</span>{{ $d->indicateurs_impact }}</p>@endif
            @if($d->contribution_odd)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">E3 — Contribution aux ODD</span>{{ $d->contribution_odd }}</p>@endif
            @if($d->pertinence_senegal_afrique)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">E4 — Pertinence Sénégal/Afrique</span>{{ $d->pertinence_senegal_afrique }}</p>@endif
        </div>
        @endif

        {{-- Section F --}}
        @if($d->public_cible_vise || $d->supports_prevus)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">F — Présentation lors de la SRI</p>
            @if($d->public_cible_vise)<p class="text-sm text-gray-700"><span class="text-xs text-gray-400 block">F1 — Public cible</span>{{ $d->public_cible_vise }}</p>@endif
            @if($d->supports_prevus)<p class="text-sm text-gray-700 mt-2"><span class="text-xs text-gray-400 block">F3 — Supports prévus</span>{{ $d->supports_prevus }}</p>@endif
        </div>
        @endif

        {{-- Section G --}}
        @if(!empty($d->annexes_checklist) || $d->annexes_autres_texte)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">G — Annexes annoncées</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach(($d->annexes_checklist ?? []) as $v)<span class="text-xs px-2 py-0.5 rounded-full bg-white border border-indigo-200 text-indigo-700">{{ $annexeLabels[$v] ?? $v }}</span>@endforeach
            </div>
            @if($d->annexes_autres_texte)<p class="text-xs text-gray-500 mt-2">Autres : {{ $d->annexes_autres_texte }}</p>@endif
        </div>
        @endif

        {{-- Co-porteurs --}}
        @if($project->coPorteurs->count() > 0)
        <div>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">Co-porteurs / Co-investigateurs</p>
            <div class="space-y-1.5">
                @foreach($project->coPorteurs as $cp)
                <p class="text-sm text-gray-700">{{ $cp->fullName() }}{{ $cp->institution ? ' — '.$cp->institution : '' }}{{ $cp->email ? ' · '.$cp->email : '' }}</p>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif
