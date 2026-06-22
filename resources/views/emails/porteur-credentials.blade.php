<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos identifiants – SRI 2026</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f1f5f9; padding: 32px 16px; color: #334155; }
        .wrap { max-width: 580px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }

        /* Header */
        .header { background: linear-gradient(135deg, #1e293b, #334155); padding: 28px 36px; }
        .header-logo { font-size: 11px; letter-spacing: .1em; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; }
        .header h1 { font-size: 20px; color: #f8fafc; font-weight: 700; margin: 0; }
        .header p { font-size: 13px; color: #94a3b8; margin-top: 4px; }

        /* Body */
        .body { padding: 32px 36px; }
        .body p { font-size: 14px; line-height: 1.75; color: #475569; margin-bottom: 16px; }
        .body strong { color: #1e293b; }

        /* Credentials box */
        .creds { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
        .creds-row { display: flex; align-items: baseline; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .creds-row:last-child { border-bottom: none; }
        .creds-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; width: 90px; flex-shrink: 0; }
        .creds-value { font-size: 14px; color: #1e293b; font-weight: 600; word-break: break-all; }
        .creds-value.mono { font-family: 'Courier New', monospace; background: #e2e8f0; padding: 2px 8px; border-radius: 4px; font-size: 15px; letter-spacing: .05em; }

        /* CTA */
        .cta-wrap { text-align: center; margin: 28px 0 16px; }
        .cta { display: inline-block; background: #334155; color: #f8fafc !important; text-decoration: none;
               padding: 12px 32px; border-radius: 8px; font-size: 14px; font-weight: 600; letter-spacing: .02em; }

        /* Notice */
        .notice { background: #fffbeb; border-left: 3px solid #d97706; border-radius: 4px; padding: 12px 16px; margin-top: 20px; }
        .notice p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.6; }

        /* Footer */
        .footer { padding: 20px 36px; text-align: center; }
        .footer p { font-size: 11px; color: #94a3b8; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrap">
<div class="card">

    <div class="header">
        <div class="header-logo">Université Cheikh Anta Diop de Dakar</div>
        <h1>Vos identifiants de connexion</h1>
        <p>Semaine de la Recherche et de l'Innovation 2026</p>
    </div>

    <div class="body">
        <p>Bonjour <strong>{{ $porteur->name }}</strong>,</p>

        <p>
            Dans le cadre de l'organisation de la <strong>SRI 2026</strong>, vous avez été enregistré(e)
            en tant que porteur de projet par la Direction de la Recherche et de l'Innovation.
            Voici vos identifiants pour accéder à votre espace de dépôt :
        </p>

        <div class="creds">
            <div class="creds-row">
                <span class="creds-label">Adresse</span>
                <span class="creds-value">{{ route('login') }}</span>
            </div>
            <div class="creds-row">
                <span class="creds-label">Email</span>
                <span class="creds-value">{{ $porteur->email }}</span>
            </div>
            <div class="creds-row">
                <span class="creds-label">Mot de passe</span>
                <span class="creds-value mono">{{ $plainPassword }}</span>
            </div>
            @if($porteur->structure)
            <div class="creds-row">
                <span class="creds-label">Structure</span>
                <span class="creds-value">{{ $porteur->structure->acronym }} – {{ $porteur->structure->name }}</span>
            </div>
            @endif
        </div>

        <div class="cta-wrap">
            <a href="{{ route('login') }}" class="cta">Accéder à mon espace →</a>
        </div>

        <div class="notice">
            <p>
                <strong>Important :</strong> nous vous recommandons de modifier votre mot de passe dès votre première connexion,
                depuis la rubrique <em>Mot de passe</em> dans le menu latéral.
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Direction de la Recherche et de l'Innovation · UCAD Dakar</p>
        <p>Cet email a été envoyé automatiquement — merci de ne pas y répondre directement.</p>
    </div>

</div>
</div>
</body>
</html>
