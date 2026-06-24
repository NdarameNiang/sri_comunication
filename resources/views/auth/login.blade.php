<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – SRI 2026 | UCAD</title>
    <link rel="icon" type="image/png" href="/favicon-ucad.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ---- Diaporama ---- */
        .bg-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1.6s ease-in-out;
        }
        .bg-slide.active {
            opacity: 1;
            animation: kenburns 8s ease-in-out forwards;
        }
        @keyframes kenburns {
            from { transform: scale(1);      }
            to   { transform: scale(1.07);   }
        }

        /* ---- Dot indicateur ---- */
        .slide-dot {
            height: 5px; width: 5px;
            border-radius: 999px;
            background: rgba(255,255,255,0.35);
            cursor: pointer;
            transition: all 0.4s ease;
        }
        .slide-dot.active {
            background: #f59e0b;
            width: 22px;
        }

        /* ---- Pulse badge ---- */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* ---- Glass form ---- */
        .glass-form {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        /* ---- Inputs glass ---- */
        .glass-input {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            transition: all .2s ease;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.45); }
        .glass-input:focus {
            outline: none;
            background: rgba(255,255,255,0.22);
            border-color: rgba(245,158,11,0.7);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
        }
        .glass-input.error {
            border-color: rgba(239,68,68,0.7);
            background: rgba(239,68,68,0.10);
        }
        .glass-input option { background: #1e293b; color: #fff; }
    </style>
</head>
<body class="font-sans">

{{-- ===== FOND PLEIN ÉCRAN (photos UCAD) ===== --}}
<div class="fixed inset-0 z-0">

    {{-- Slide 1 --}}
    <div class="bg-slide active" id="slide-0"
         style="background-image: url('{{ asset('images/ucad_bg_1.jpg') }}');"></div>

    {{-- Slide 2 --}}
    <div class="bg-slide" id="slide-1"
         style="background-image: url('{{ asset('images/ucad_bg.2.jpg') }}');"></div>

    {{-- Overlay global --}}
    <div class="absolute inset-0"
         style="background: linear-gradient(
             110deg,
             rgba(10,16,35,0.78) 0%,
             rgba(10,16,35,0.55) 45%,
             rgba(10,16,35,0.75) 100%
         );"></div>
</div>

{{-- ===== LAYOUT SUR LE FOND ===== --}}
<div class="relative z-10 flex flex-col lg:flex-row min-h-screen">

    {{-- ===== GAUCHE : Branding ===== --}}
    <div class="hidden lg:flex lg:w-[55%] flex-col justify-between p-12">

        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo_ucad.png') }}"
                 alt="Logo UCAD"
                 class="h-14 w-auto object-contain drop-shadow-lg"
                 onerror="this.style.display='none'">
            <div class="border-l border-white/20 pl-4">
                <p class="text-white font-bold text-base tracking-wide drop-shadow leading-tight">
                    Université Cheikh Anta Diop
                </p>
                <p class="text-white/50 text-xs tracking-wider">Dakar · Sénégal</p>
            </div>
        </div>

        {{-- Texte central --}}
        <div>
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-7">
                <span class="pulse-dot w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">Appel à contribution · Décembre 2026</span>
            </div>

            {{-- Titre --}}
            <h1 class="text-6xl font-extrabold text-white tracking-tight drop-shadow-2xl leading-none mb-2">
                SRI <span class="text-amber-400">2026</span>
            </h1>
            <p class="text-xl text-white/70 font-light tracking-wide mb-6">
                Semaine de la Recherche et de l'Innovation
            </p>

            {{-- Texte institutionnel --}}
            <div class="max-w-md space-y-4">
                <p class="text-sm text-white/65 leading-relaxed">
                    Dans le cadre de l'organisation de la <span class="text-white/90 font-medium">SRI 2026</span>,
                    la Direction de la Recherche et de l'Innovation (<span class="text-amber-400 font-semibold">DRI</span>)
                    lance un appel à contribution à l'ensemble des structures académiques, scientifiques et pédagogiques de l'UCAD.
                </p>

                {{-- Objectifs --}}
                <div class="space-y-2">
                    <p class="text-xs text-white uppercase tracking-widest font-semibold mb-3">Cet événement vise à</p>
                    @foreach([
                        ['Sécuriser',  'les résultats issus de la recherche institutionnelle'],
                        ['Valoriser',  'les travaux à fort impact scientifique et sociétal'],
                        ['Promouvoir', 'les innovations des laboratoires et équipes de recherche'],
                        ['Renforcer',  'la visibilité et les partenariats académiques et socio-économiques'],
                    ] as [$kw, $desc])
                    <div class="flex items-start gap-3">
                        <div class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></div>
                        <p class="text-sm text-white leading-snug">
                            <span class="font-semibold">{{ $kw }}</span>
                            {{ $desc }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer + dots --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2" id="slide-dots">
                <button class="slide-dot active" onclick="goToSlide(0)"></button>
                <button class="slide-dot"        onclick="goToSlide(1)"></button>
            </div>
            <p class="text-white/25 text-xs tracking-wide">
                © 2026 Direction de la Recherche · UCAD · Dakar, Sénégal
            </p>
        </div>
    </div>

    {{-- ===== DROITE : Formulaire glass ===== --}}
    <div class="w-full lg:w-[45%] flex items-center justify-center px-4 py-8 lg:p-12">

        <div class="glass-form rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-10 w-full max-w-md">

            {{-- Header mobile --}}
            <div class="lg:hidden text-center mb-7">
                <img src="{{ asset('images/logo_ucad.png') }}"
                     alt="Logo UCAD"
                     class="h-14 mx-auto mb-3 object-contain drop-shadow"
                     onerror="this.style.display='none'">
                <p class="text-white font-bold text-lg">SRI 2026 · UCAD</p>
                <p class="text-white/50 text-xs mt-0.5">Appel à Communication</p>
            </div>

            {{-- Titre --}}
            <div class="mb-7">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white leading-tight">Connexion</h2>
                        <p class="text-white/45 text-xs">Plateforme SRI 2026</p>
                    </div>
                </div>
                <p class="text-white/55 text-sm leading-relaxed">
                    Accédez à votre espace de gestion des projets de recherche.
                </p>
            </div>


            {{-- Alerte erreur --}}
            @if(session('error'))
            <div class="flex items-start gap-3 mb-5 p-3.5 rounded-xl bg-red-500/20 border border-red-400/30 text-red-200 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-red-300 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-white/70 text-sm font-medium mb-1.5">Adresse email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               autocomplete="email"
                               placeholder="votre@ucad.edu.sn"
                               class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-sm @error('email') error @enderror"
                               required autofocus>
                    </div>
                    @error('email')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div>
                    <label class="block text-white/70 text-sm font-medium mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password"
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-sm"
                               required>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Se souvenir --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-white/30 bg-white/10 text-amber-400 focus:ring-amber-400/40 focus:ring-offset-0">
                    <label for="remember" class="text-white/55 text-sm cursor-pointer select-none">
                        Se souvenir de moi
                    </label>
                </div>

                {{-- Bouton connexion --}}
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                               bg-amber-400 hover:bg-amber-300 active:bg-amber-500
                               text-slate-900 font-bold text-sm tracking-wide
                               transition-all duration-200 shadow-lg hover:shadow-amber-400/30
                               focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-transparent
                               active:scale-[.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Se connecter
                </button>
            </form>

            {{-- Séparateur --}}
            <div class="flex items-center gap-3 my-5">
                <div class="h-px flex-1 bg-white/10"></div>
                <span class="text-white/20 text-xs">•</span>
                <div class="h-px flex-1 bg-white/10"></div>
            </div>

            {{-- Info --}}
            <div class="flex items-start gap-2.5 p-3.5 rounded-xl bg-white/6 border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/35 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-white/40 text-xs leading-relaxed">
                    Accès réservé aux utilisateurs enregistrés. Contactez la
                    <span class="text-white/65 font-medium">Direction de la Recherche</span>
                    pour tout problème de connexion.
                </p>
            </div>

            <p class="text-center text-white/20 text-xs mt-6">
                © 2026 SRI · Université Cheikh Anta Diop · Dakar
            </p>
        </div>
    </div>
</div>

{{-- ── Modal session expirée ──────────────────────────────────────── --}}
@if(session('warning'))
<div id="session-modal-overlay"
     style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;
            background:rgba(0,0,0,0.65);backdrop-filter:blur(4px);
            opacity:0;transition:opacity .25s ease;">

    <div id="session-modal"
         style="position:relative;width:100%;max-width:420px;margin:1rem;
                background:#fff;border-radius:1.25rem;overflow:hidden;
                box-shadow:0 25px 60px rgba(0,0,0,0.35);
                transform:translateY(-16px) scale(0.96);opacity:0;
                transition:transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;">

        {{-- Bandeau gris ardoise --}}
        <div style="background:linear-gradient(135deg,#1e293b,#334155);padding:1.5rem 1.5rem 1.25rem;">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div style="width:3rem;height:3rem;background:rgba(255,255,255,0.1);border-radius:50%;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:1rem;font-weight:700;color:#f1f5f9;margin:0;">Session expirée</p>
                    <p style="font-size:.75rem;color:#94a3b8;margin:.1rem 0 0;">SRI 2026 – UCAD</p>
                </div>
            </div>
        </div>

        {{-- Corps --}}
        <div style="padding:1.5rem;">
            <p style="color:#374151;font-size:.9rem;line-height:1.6;margin:0 0 1.25rem;">
                {{ session('warning') }}
            </p>
            <p style="color:#6b7280;font-size:.8rem;margin:0 0 1.5rem;">
                Pour des raisons de sécurité, votre session a été automatiquement fermée après une période d'inactivité.
            </p>
            <button id="session-modal-close"
                    style="width:100%;padding:.75rem 1rem;border-radius:.75rem;border:none;cursor:pointer;
                           background:linear-gradient(135deg,#334155,#475569);
                           color:#f1f5f9;font-weight:700;font-size:.9rem;
                           box-shadow:0 4px 12px rgba(30,41,59,.3);
                           transition:opacity .15s;">
                Se reconnecter
            </button>
        </div>
    </div>
</div>
@endif

<script>
    const TOTAL = 2, DELAY = 6000;
    let cur = 0, timer;

    function goToSlide(i) {
        document.getElementById('slide-' + cur).classList.remove('active');
        document.querySelectorAll('.slide-dot')[cur].classList.remove('active');
        cur = i;
        document.getElementById('slide-' + cur).classList.add('active');
        document.querySelectorAll('.slide-dot')[cur].classList.add('active');
        clearInterval(timer);
        timer = setInterval(next, DELAY);
    }
    function next() { goToSlide((cur + 1) % TOTAL); }
    timer = setInterval(next, DELAY);

    // ── Modal session expirée ─────────────────────────────────────────
    @if(session('warning'))
    (function () {
        const overlay = document.getElementById('session-modal-overlay');
        const modal   = document.getElementById('session-modal');

        // Apparition avec animation
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            modal.style.transform = 'translateY(0) scale(1)';
            modal.style.opacity   = '1';
        });

        function closeModal() {
            overlay.style.opacity = '0';
            modal.style.transform = 'translateY(-16px) scale(0.96)';
            modal.style.opacity   = '0';
            setTimeout(() => overlay.remove(), 300);
        }

        document.getElementById('session-modal-close').addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    })();
    @endif

    // ── Afficher / masquer mot de passe ───────────────────────────────
    const EYE_OPEN = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>`;
    const EYE_OFF  = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.599m3.05-2.674A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.357 2.574M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/></svg>`;

    document.querySelectorAll('input[type="password"]').forEach(function (input) {
        const parent = input.parentElement;
        input.style.paddingRight = '2.75rem';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.setAttribute('tabindex', '-1');
        btn.style.cssText = 'position:absolute;top:0;right:0;height:100%;padding:0 0.65rem;display:flex;align-items:center;background:transparent;border:none;cursor:pointer;color:rgba(255,255,255,0.45);';
        btn.innerHTML = EYE_OPEN;
        btn.addEventListener('mouseenter', () => btn.style.color = 'rgba(255,255,255,0.75)');
        btn.addEventListener('mouseleave', () => btn.style.color = input.type === 'text' ? '#f59e0b' : 'rgba(255,255,255,0.45)');
        btn.addEventListener('click', function () {
            const visible = input.type === 'text';
            input.type    = visible ? 'password' : 'text';
            btn.innerHTML = visible ? EYE_OPEN : EYE_OFF;
            btn.style.color = visible ? 'rgba(255,255,255,0.45)' : '#f59e0b';
        });

        parent.appendChild(btn);
    });
</script>
</body>
</html>
