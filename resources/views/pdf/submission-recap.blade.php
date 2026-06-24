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

    .body { padding: 28px 36px; }

    .section { margin-bottom: 20px; }
    .section-title { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;
                     letter-spacing: 1.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px; }

    table.info { width: 100%; border-collapse: collapse; }
    table.info td { padding: 7px 10px; font-size: 12px; vertical-align: top; }
    table.info td.label { color: #64748b; width: 38%; font-weight: 600; }
    table.info td.value { color: #1e293b; }
    table.info tr:nth-child(even) td { background: #f8fafc; }

    .text-block { background: #f8fafc; border-left: 3px solid #334155; padding: 10px 14px;
                  margin-bottom: 14px; border-radius: 0 6px 6px 0; }
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
    .tag-amber  { background: #fffbeb; color: #d97706; border-color: #fde68a; }

    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 36px;
              text-align: center; color: #94a3b8; font-size: 10px; }

    .num { display: inline-block; background: #334155; color: #fff; font-size: 10px;
           font-weight: 700; width: 18px; height: 18px; border-radius: 4px;
           text-align: center; line-height: 18px; margin-right: 6px; }
</style>
</head>
<body>

{{-- En-tête --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="subtitle">Appel à communication · {{ $project->structure?->name }}</div>
            <h1>{{ $project->assignment?->title ?? 'Communication soumise' }}</h1>
            <div class="badge">✓ Soumis officiellement</div>
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
        </table>
    </div>

    {{-- Identification --}}
    <div class="section">
        <div class="section-title">Identification du projet</div>
        <table class="info">
            @if($project->scientific_domain)
            <tr><td class="label">Domaine scientifique</td><td class="value">{{ $project->scientific_domain }}</td></tr>
            @endif
            @if($project->maturity_level)
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
        <div class="section-title">Valorisation & Impact</div>
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
                        <span class="tag tag-amber">{{ \App\Models\Project::presentationLabels()[$t] ?? $t }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endif

</div>

<div class="footer">
    Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }} · {{ config('app.name') }} · Université Cheikh Anta Diop de Dakar
</div>

</body>
</html>
