<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

    .header { background: #1e293b; padding: 28px 36px; color: #fff; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .header h1 { font-size: 18px; font-weight: 700; margin: 0 0 4px; }
    .header .subtitle { color: #94a3b8; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
    .header .date-ref { text-align: right; color: #94a3b8; font-size: 11px; }

    .badge { display: inline-block; background: #22c55e; color: #fff; font-size: 10px; font-weight: 700;
             padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px; margin-top: 8px; }
    .badge-template { display: inline-block; background: rgba(255,255,255,.15); color: #fff; font-size: 9px; font-weight: 700;
             padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px; margin-top: 8px; margin-left: 6px; }

    .body { padding: 28px 36px; }

    .section { margin-bottom: 20px; }
    .section-title { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;
                     letter-spacing: 1.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px; }
    .subsection-title { font-size: 11px; font-weight: 700; color: #4338ca; margin: 14px 0 6px; }

    table.info { width: 100%; border-collapse: collapse; }
    table.info td { padding: 7px 10px; font-size: 12px; vertical-align: top; }
    table.info td.label { color: #64748b; width: 38%; font-weight: 600; }
    table.info td.value { color: #1e293b; }
    table.info tr:nth-child(even) td { background: #f8fafc; }

    .text-block { background: #f8fafc; border-left: 3px solid #334155; padding: 10px 14px;
                  margin-bottom: 14px; border-radius: 0 6px 6px 0; }
    .text-block.indigo { border-left-color: #4338ca; }
    .text-block .text-label { font-size: 10px; font-weight: 700; color: #94a3b8;
                               text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .text-block p { font-size: 12px; color: #374151; line-height: 1.6; }

    .collab-row { padding: 6px 10px; border-bottom: 1px solid #f1f5f9; }
    .collab-row:last-child { border-bottom: none; }
    .collab-name { font-weight: 600; color: #1e293b; }
    .collab-meta { color: #94a3b8; font-size: 11px; }

    .tag { display: inline-block; background: #eff6ff; color: #3b82f6; font-size: 10px;
           padding: 2px 8px; border-radius: 10px; margin: 2px; border: 1px solid #bfdbfe; }
    .tag-purple { background: #f5f3ff; color: #7c3aed; border-color: #ddd6fe; }
    .tag-green  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
    .tag-indigo { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }

    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 36px;
              text-align: center; color: #94a3b8; font-size: 10px; }

    .num { display: inline-block; background: #334155; color: #fff; font-size: 10px;
           font-weight: 700; width: 18px; height: 18px; border-radius: 4px;
           text-align: center; line-height: 18px; margin-right: 6px; }
    .num.indigo { background: #4338ca; }
</style>
</head>
<body>

@php
    $isApprofondi = $project->isApprofondi();
    $d = $project->approfondiDetails;
    $mainTitle = $isApprofondi ? ($d?->titre_complet ?? $project->assignment?->title) : $project->assignment?->title;
@endphp

{{-- En-tête --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="subtitle">Appel à communication · {{ $project->structure?->name }}</div>
            <h1>{{ $mainTitle ?? 'Communication soumise' }}</h1>
            @if($isApprofondi && $d?->acronyme_projet)
            <div class="subtitle" style="text-transform:none;letter-spacing:0;margin-top:2px;">({{ $d->acronyme_projet }})</div>
            @endif
            <div class="badge">✓ Soumis officiellement</div>
            <span class="badge-template">{{ $isApprofondi ? 'Format Approfondi' : 'Format Standard' }}</span>
        </div>
        <div class="date-ref">
            <div>Date de soumission</div>
            <div style="color:#fff;font-weight:700;font-size:13px;margin-top:4px;">{{ now()->format('d/m/Y à H:i') }}</div>
            <div style="margin-top:6px;">Réf. #{{ str_pad($project->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
</div>

<div class="body">

    {{-- Responsable --}}
    <div class="section">
        <div class="section-title">Responsable principal</div>
        <table class="info">
            <tr><td class="label">Nom complet</td><td class="value">{{ $project->responsable_nom }}</td></tr>
            <tr><td class="label">Email institutionnel</td><td class="value">{{ $project->contact_email }}</td></tr>
            @if($project->email_professionnel)
            <tr><td class="label">Email personnel</td><td class="value">{{ $project->email_professionnel }}</td></tr>
            @endif
            @if($project->contact_phone)
            <tr><td class="label">Téléphone</td><td class="value">{{ $project->contact_phone }}</td></tr>
            @endif
            <tr><td class="label">Structure</td><td class="value">{{ $project->structure?->name }} ({{ $project->structure?->acronym }})</td></tr>
            @if($isApprofondi)
                @if($d?->responsable_titre || $d?->responsable_fonction)
                <tr><td class="label">Titre / Fonction</td><td class="value">{{ trim(($d->responsable_titre ?? '') . ' — ' . ($d->responsable_fonction ?? ''), ' —') }}</td></tr>
                @endif
                @if($d?->laboratoire_nom)
                <tr><td class="label">Laboratoire / Équipe</td><td class="value">{{ $d->laboratoire_nom }}{{ $d->laboratoire_acronyme ? ' ('.$d->laboratoire_acronyme.')' : '' }}</td></tr>
                @endif
                @if($d?->laboratoire_site_web)
                <tr><td class="label">Site web du laboratoire</td><td class="value">{{ $d->laboratoire_site_web }}</td></tr>
                @endif
            @endif
        </table>
    </div>

    {{-- Identification --}}
    <div class="section">
        <div class="section-title">Identification du projet</div>
        <table class="info">
            @if($project->scientific_domain)
            <tr><td class="label">Domaine scientifique</td><td class="value">{{ $project->scientific_domain }}</td></tr>
            @endif
            @if(!$isApprofondi && $project->maturity_level)
            <tr><td class="label">Niveau de maturité</td><td class="value">{{ \App\Models\Project::maturityLabels()[$project->maturity_level] ?? $project->maturity_level }}</td></tr>
            @endif
            @if($project->project_types)
            <tr>
                <td class="label">Type(s) de projet</td>
                <td class="value">
                    @foreach($project->project_types as $t)
                        <span class="tag">{{ \App\Models\Project::projectTypeLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
            @if($isApprofondi)
                @if($d?->sous_domaines)
                <tr><td class="label">Sous-domaines</td><td class="value">{{ implode(', ', $d->sous_domaines) }}</td></tr>
                @endif
                @if($d?->mots_cles)
                <tr><td class="label">Mots-clés</td><td class="value">{{ implode(', ', $d->mots_cles) }}</td></tr>
                @endif
                @if($d?->date_demarrage || $d?->duree_prevue)
                <tr><td class="label">Calendrier</td><td class="value">{{ $d->date_demarrage?->format('d/m/Y') }}{{ $d->duree_prevue ? ' · durée prévue : ' . $d->duree_prevue : '' }}</td></tr>
                @endif
                @if($d?->trl_level)
                <tr><td class="label">Maturité technologique (TRL)</td><td class="value">{{ \App\Models\Project::trlLabels()[$d->trl_level] ?? $d->trl_level }}</td></tr>
                @endif
            @endif
        </table>
    </div>

    {{-- Description --}}
    <div class="section">
        <div class="section-title">Description synthétique</div>
        @foreach([
            ['01', 'Résumé',            $project->summary],
            ['02', 'Problématique',     $project->problematic],
            ['03', 'Solution proposée', $project->solution],
            ['04', 'Résultats attendus',$project->results],
        ] as [$num, $label, $content])
        @if($content)
        <div class="text-block">
            <div class="text-label"><span class="num">{{ $num }}</span>{{ $label }}</div>
            <p>{{ $content }}</p>
        </div>
        @endif
        @endforeach
    </div>

    @if($isApprofondi)
    {{-- Description scientifique (Section B) --}}
    @if($d?->contexte_etat_art || $d?->approche_methodologique || $d?->caractere_innovant)
    <div class="section">
        <div class="section-title">B — Description scientifique</div>
        @foreach([
            ['B1', 'Contexte et état de l\'art',              $d?->contexte_etat_art],
            ['B3', 'Approche méthodologique',                 $d?->approche_methodologique],
            ['B4', 'Caractère innovant et différenciation',   $d?->caractere_innovant],
        ] as [$num, $label, $content])
        @if($content)
        <div class="text-block indigo">
            <div class="text-label"><span class="num indigo">{{ $num }}</span>{{ $label }}</div>
            <p>{{ $content }}</p>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- Résultats consolidés & maturité (Sections C, D) --}}
    @if($d?->resultats_scientifiques || $d?->resultats_techniques || $d?->indicateurs_chiffres || $d?->propriete_intellectuelle || $d?->modele_economique || $d?->partenariats_financement || $d?->voies_valorisation)
    <div class="section">
        <div class="section-title">C — Résultats consolidés / D — Maturité &amp; valorisation</div>
        @foreach([
            ['C1', 'Résultats scientifiques',   $d?->resultats_scientifiques],
            ['C2', 'Résultats techniques',      $d?->resultats_techniques],
            ['C3', 'Indicateurs chiffrés',      $d?->indicateurs_chiffres],
            ['D1', 'Propriété intellectuelle',  $d?->propriete_intellectuelle],
            ['D2', 'Modèle économique envisagé',$d?->modele_economique],
            ['D3', 'Partenariats et financement',$d?->partenariats_financement],
        ] as [$num, $label, $content])
        @if($content)
        <div class="text-block indigo">
            <div class="text-label"><span class="num indigo">{{ $num }}</span>{{ $label }}</div>
            <p>{{ $content }}</p>
        </div>
        @endif
        @endforeach
        @if($d?->voies_valorisation)
        <table class="info">
            <tr>
                <td class="label">Voies de valorisation</td>
                <td class="value">
                    @foreach($d->voies_valorisation as $t)
                        <span class="tag tag-indigo">{{ \App\Models\Project::voieValorisationLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        @endif
    </div>
    @endif

    {{-- Impact (Section E) --}}
    @if($d?->dimensions_impact || $d?->beneficiaires || $d?->indicateurs_impact || $d?->contribution_odd || $d?->pertinence_senegal_afrique)
    <div class="section">
        <div class="section-title">E — Impact</div>
        @if($d?->dimensions_impact)
        <table class="info">
            <tr>
                <td class="label">Dimensions d'impact</td>
                <td class="value">
                    @foreach($d->dimensions_impact as $t)
                        <span class="tag tag-indigo">{{ \App\Models\Project::impactDimensionLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        @endif
        @foreach([
            ['E1', 'Bénéficiaires directs et indirects',       $d?->beneficiaires],
            ['E2', 'Indicateurs d\'impact chiffrés',           $d?->indicateurs_impact],
            ['E3', 'Contribution aux ODD',                     $d?->contribution_odd],
            ['E4', 'Pertinence pour le Sénégal et l\'Afrique', $d?->pertinence_senegal_afrique],
        ] as [$num, $label, $content])
        @if($content)
        <div class="text-block indigo">
            <div class="text-label"><span class="num indigo">{{ $num }}</span>{{ $label }}</div>
            <p>{{ $content }}</p>
        </div>
        @endif
        @endforeach
    </div>
    @endif
    @endif

    {{-- Co-porteurs (approfondi uniquement) --}}
    @if($isApprofondi && $project->coPorteurs->count() > 0)
    <div class="section">
        <div class="section-title">Co-porteurs ({{ $project->coPorteurs->count() }})</div>
        @foreach($project->coPorteurs as $coPorteur)
        <div class="collab-row">
            <span class="collab-name">{{ $coPorteur->fullName() }}</span>
            @if($coPorteur->institution)
                <span class="collab-meta"> · {{ $coPorteur->institution }}</span>
            @endif
            @if($coPorteur->email)
                <span class="collab-meta"> · {{ $coPorteur->email }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Collaborateurs --}}
    @if($project->collaborators->count() > 0)
    <div class="section">
        <div class="section-title">Collaborateurs ({{ $project->collaborators->count() }})</div>
        @foreach($project->collaborators as $collab)
        <div class="collab-row">
            <span class="collab-name">{{ $collab->fullName() }}</span>
            @if($collab->role_collaborateur)
                <span class="collab-meta"> — {{ $collab->role_collaborateur }}</span>
            @endif
            @if($collab->institution)
                <span class="collab-meta"> · {{ $collab->institution }}</span>
            @endif
            @if($collab->email)
                <span class="collab-meta"> · {{ $collab->email }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Valorisation --}}
    @if($project->impact_types || $project->protection_types || $project->valorisation_types)
    <div class="section">
        <div class="section-title">Valorisation & Impact (format Standard)</div>
        <table class="info">
            @if($project->protection_types)
            <tr>
                <td class="label">Protection IP</td>
                <td class="value">
                    @foreach($project->protection_types as $t)
                        <span class="tag tag-purple">{{ \App\Models\Project::protectionLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
            @if($project->impact_types)
            <tr>
                <td class="label">Impact attendu</td>
                <td class="value">
                    @foreach($project->impact_types as $t)
                        <span class="tag tag-green">{{ \App\Models\Project::impactLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
            @if($project->presentation_formats)
            <tr>
                <td class="label">Format présentation</td>
                <td class="value">
                    @foreach($project->presentation_formats as $t)
                        <span class="tag tag-indigo">{{ \App\Models\Project::presentationLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- Présentation SRI & Annexes (Sections F, G) --}}
    @if($isApprofondi && ($d?->public_cible_vise || $d?->supports_prevus || $d?->annexes_checklist || $project->logistic_needs))
    <div class="section">
        <div class="section-title">F — Présentation lors de la SRI / G — Annexes</div>
        <table class="info">
            @if($project->presentation_formats)
            <tr>
                <td class="label">Format(s) de présentation</td>
                <td class="value">
                    @foreach($project->presentation_formats as $t)
                        <span class="tag tag-indigo">{{ \App\Models\Project::presentationLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
            @if($project->logistic_needs)
            <tr><td class="label">F2 — Besoins logistiques</td><td class="value">{{ $project->logistic_needs }}</td></tr>
            @endif
        </table>
        @foreach([
            ['F1', 'Public cible visé', $d?->public_cible_vise],
            ['F3', 'Supports prévus',   $d?->supports_prevus],
        ] as [$num, $label, $content])
        @if($content)
        <div class="text-block indigo">
            <div class="text-label"><span class="num indigo">{{ $num }}</span>{{ $label }}</div>
            <p>{{ $content }}</p>
        </div>
        @endif
        @endforeach
        @if($d?->annexes_checklist)
        <table class="info">
            <tr>
                <td class="label">Annexes prévues</td>
                <td class="value">
                    @foreach($d->annexes_checklist as $t)
                        <span class="tag tag-indigo">{{ \App\Models\Project::annexeTypeLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @if($d?->media_link)
            <tr><td class="label">Lien photos/vidéos</td><td class="value">{{ $d->media_link }}</td></tr>
            @endif
        </table>
        @endif
    </div>
    @endif

</div>

<div class="footer">
    Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }} · {{ \App\Models\ContentBlock::resolve('landing.footer', $event ?? null)?->content ?? (config('app.name') . ' · Université Cheikh Anta Diop de Dakar') }}
</div>

</body>
</html>
