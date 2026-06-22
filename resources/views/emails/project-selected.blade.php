<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection SRI 2026</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 620px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: linear-gradient(135deg, #1e293b, #475569); color: #f8fafc; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0 0 8px; font-size: 24px; }
        .header p { margin: 0; color: #cbd5e1; font-size: 14px; }
        .body { padding: 32px 40px; color: #333; line-height: 1.7; }
        .body h2 { color: #334155; border-bottom: 2px solid #94a3b8; padding-bottom: 8px; }
        .info-box { background: #f8fafc; border-left: 4px solid #64748b; padding: 16px 20px; border-radius: 4px; margin: 20px 0; }
        .info-box p { margin: 4px 0; }
        .info-box strong { color: #1e293b; }
        .footer { background: #1e293b; color: rgba(255,255,255,.6); padding: 20px 40px; text-align: center; font-size: 12px; }
        .badge { display: inline-block; background: #475569; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Félicitations !</h1>
        <p>Semaine de la Recherche et de l'Innovation – UCAD 2026</p>
    </div>

    <div class="body">
        <p>Cher(e) <strong>{{ $project->responsable_nom }}</strong>,</p>

        <p>
            Nous avons l'honneur de vous informer que votre projet a été <strong>sélectionné</strong>
            par le Comité Scientifique pour être présenté lors de la
            <strong>Semaine de la Recherche et de l'Innovation (SRI) 2026</strong>
            de l'Université Cheikh Anta Diop de Dakar.
        </p>

        <h2>Détails du projet retenu</h2>

        <div class="info-box">
            <p><strong>Structure :</strong> {{ $project->structure->name }} ({{ $project->structure->acronym }})</p>
            <p><strong>Titre du projet :</strong> {{ $project->assignment->title }}</p>
            <p><strong>Domaine scientifique :</strong> {{ $project->scientific_domain }}</p>
            <p>
                <strong>Format de présentation :</strong>
                @if($project->presentation_formats)
                    {{ implode(', ', array_map(fn($f) => \App\Models\Project::presentationLabels()[$f] ?? $f, $project->presentation_formats)) }}
                @else
                    À confirmer
                @endif
            </p>
        </div>

        <p>
            L'équipe d'organisation prendra contact avec vous prochainement pour vous communiquer
            le programme définitif, les modalités pratiques et les besoins logistiques.
        </p>

        <p>
            Nous vous remercions chaleureusement pour la qualité de votre contribution
            et nous vous encourageons à poursuivre vos travaux de recherche et d'innovation.
        </p>

        <p>
            Veuillez agréer, Cher(e) Collègue, l'expression de nos salutations distinguées.
        </p>

        <p>
            <strong>Le Comité Scientifique</strong><br>
            <span style="color:#666">SRI 2026 – UCAD</span>
        </p>
    </div>

    <div class="footer">
        <p>Université Cheikh Anta Diop de Dakar · Direction de la Recherche</p>
        <p>Cet email a été envoyé automatiquement – Merci de ne pas y répondre directement.</p>
    </div>
</div>
</body>
</html>
