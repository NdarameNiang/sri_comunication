<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Erreur') – SRI 2026</title>
    <link rel="icon" type="image/png" href="/favicon-ucad.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        body { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -.04em;
            background: linear-gradient(135deg, #1d4ed8 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: .75rem;
        }
        .title   { font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: .6rem; }
        .message { font-size: .925rem; color: #64748b; line-height: 1.65; margin-bottom: 2rem; }

        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .65rem 1.4rem; border-radius: .6rem; border: none; cursor: pointer;
            font-size: .875rem; font-weight: 600; text-decoration: none; transition: opacity .15s;
        }
        .btn:hover { opacity: .88; }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-secondary { background: #f1f5f9; color: #374151; }

        .icon-wrap {
            width: 72px; height: 72px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon-wrap svg { width: 36px; height: 36px; }

        .divider { height: 1px; background: #f1f5f9; margin: 1.5rem 0; }

        footer { margin-top: 1.5rem; font-size: .8rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card">
        @yield('content')
    </div>
    <footer>SRI 2026 – Appel à Communication · UCAD</footer>
</body>
</html>
