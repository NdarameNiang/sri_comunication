<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation de soumission</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Inter',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
<tr><td>
<table width="600" align="center" cellpadding="0" cellspacing="0" style="max-width:600px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.07);">

  {{-- Header --}}
  <tr>
    <td style="background:linear-gradient(135deg,#1e293b,#334155);padding:32px 36px;text-align:center;">
      <p style="color:#94a3b8;font-size:11px;letter-spacing:2px;text-transform:uppercase;margin:0 0 8px;">{{ config('app.name') }}</p>
      <h1 style="color:#fff;font-size:22px;font-weight:700;margin:0;">Soumission confirmée</h1>
      <p style="color:#94a3b8;font-size:13px;margin:8px 0 0;">Votre communication a bien été soumise</p>
    </td>
  </tr>

  {{-- Corps --}}
  <tr>
    <td style="padding:32px 36px;">
      <p style="color:#374151;font-size:15px;margin:0 0 20px;">Bonjour <strong>{{ $porteur->name }}</strong>,</p>
      <p style="color:#6b7280;font-size:14px;line-height:1.6;margin:0 0 24px;">
        Nous confirmons la réception de votre soumission pour l'appel à communication. Voici un récapitulatif complet de votre dossier.
      </p>

      {{-- Titre du projet --}}
      <div style="background:#f8fafc;border-left:4px solid #334155;border-radius:0 8px 8px 0;padding:16px 20px;margin:0 0 24px;">
        <p style="color:#94a3b8;font-size:11px;letter-spacing:1px;text-transform:uppercase;margin:0 0 6px;">Communication soumise</p>
        <p style="color:#1e293b;font-size:17px;font-weight:700;margin:0;">{{ $project->assignment?->title }}</p>
      </div>

      {{-- Infos porteur --}}
      <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        <tr style="background:#f8fafc;">
          <td style="padding:12px 16px;font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;" colspan="2">Responsable principal</td>
        </tr>
        @php $rows = ['Nom' => $project->responsable_nom, 'Email' => $project->contact_email, 'Téléphone' => $project->contact_phone, 'Structure' => $project->structure?->name]; @endphp
        @foreach(array_filter($rows) as $label => $value)
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#6b7280;width:40%;">{{ $label }}</td>
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:500;">{{ $value }}</td>
        </tr>
        @endforeach
      </table>

      {{-- Infos projet --}}
      <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        <tr style="background:#f8fafc;">
          <td style="padding:12px 16px;font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;" colspan="2">Détails de la communication</td>
        </tr>
        @if($project->scientific_domain)
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#6b7280;width:40%;">Domaine scientifique</td>
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:500;">{{ $project->scientific_domain }}</td>
        </tr>
        @endif
        @if($project->maturity_level)
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#6b7280;">Niveau de maturité</td>
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:500;">{{ $project->maturity_level }}</td>
        </tr>
        @endif
        @if($project->project_types && count($project->project_types) > 0)
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#6b7280;">Type(s) de projet</td>
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:500;">{{ implode(', ', $project->project_types) }}</td>
        </tr>
        @endif
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#6b7280;">Date de soumission</td>
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:500;">{{ now()->format('d/m/Y à H:i') }}</td>
        </tr>
      </table>

      {{-- Collaborateurs --}}
      @if($project->collaborators && $project->collaborators->count() > 0)
      <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        <tr style="background:#f8fafc;">
          <td style="padding:12px 16px;font-size:12px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;">
            Collaborateurs ({{ $project->collaborators->count() }})
          </td>
        </tr>
        @foreach($project->collaborators as $collab)
        <tr style="border-top:1px solid #f1f5f9;">
          <td style="padding:10px 16px;font-size:13px;color:#1e293b;">
            <strong>{{ $collab->fullName() }}</strong>
            @if($collab->role_collaborateur) <span style="color:#6b7280;"> – {{ $collab->role_collaborateur }}</span>@endif
            @if($collab->institution) <br><span style="color:#94a3b8;font-size:12px;">{{ $collab->institution }}</span>@endif
          </td>
        </tr>
        @endforeach
      </table>
      @endif

      {{-- Résumé --}}
      @if($project->summary)
      <div style="background:#f8fafc;border-radius:8px;padding:16px 20px;margin:0 0 24px;">
        <p style="color:#94a3b8;font-size:11px;letter-spacing:1px;text-transform:uppercase;margin:0 0 8px;font-weight:600;">Résumé</p>
        <p style="color:#374151;font-size:13px;line-height:1.7;margin:0;">{{ Str::limit($project->summary, 400) }}</p>
      </div>
      @endif

      <p style="color:#6b7280;font-size:13px;line-height:1.6;margin:0 0 8px;">
        Ce message est une confirmation automatique. Conservez-le comme preuve de votre soumission.
      </p>
      <p style="color:#6b7280;font-size:13px;line-height:1.6;margin:0;">
        Pour toute question, contactez l'organisateur.
      </p>
    </td>
  </tr>

  {{-- Footer --}}
  <tr>
    <td style="background:#f8fafc;padding:20px 36px;border-top:1px solid #e2e8f0;text-align:center;">
      <p style="color:#94a3b8;font-size:12px;margin:0;">{{ config('app.name') }} · Université Cheikh Anta Diop de Dakar</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
