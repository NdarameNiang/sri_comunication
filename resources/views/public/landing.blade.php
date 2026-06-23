<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->event_name }} – UCAD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ---- Diaporama ---- */
        .bg-slide {
            position: absolute; inset: 0;
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
            from { transform: scale(1); }
            to   { transform: scale(1.07); }
        }

        /* ---- Dots ---- */
        .slide-dot {
            height: 5px; width: 5px;
            border-radius: 999px;
            background: rgba(255,255,255,0.35);
            cursor: pointer;
            transition: all 0.4s ease;
        }
        .slide-dot.active { background: #f59e0b; width: 22px; }

        /* ---- Pulse ---- */
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* ---- Glass card ---- */
        .glass-form {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.22);
        }

        /* ---- Bouton action glass ---- */
        .glass-btn {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            transition: all .2s ease;
        }
        .glass-btn:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.30);
            transform: translateY(-1px);
        }
        .glass-btn:active { transform: scale(.98); }

        /* ---- Onglets ---- */
        .tab-btn { color: rgba(255,255,255,0.45); }
        .tab-btn.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        /* ---- QR code frame ---- */
        .qr-frame { display:flex; align-items:center; gap:12px; }
        .qr-frame .qr-box {
            flex-shrink: 0;
            width: 72px; height: 72px;
            background: #fff;
            border-radius: 10px;
            padding: 6px;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.25), 0 2px 8px rgba(0,0,0,0.25);
        }
        .qr-frame .qr-box svg {
            width: 100%; height: auto;
            display: block;
        }
    </style>
</head>
<body class="font-sans overflow-hidden h-screen">

{{-- ===== FOND DIAPORAMA ===== --}}
<div class="fixed inset-0 z-0">
    <div class="bg-slide active" id="slide-0"
         style="background-image: url('{{ asset('images/ucad_bg_1.jpg') }}');"></div>
    <div class="bg-slide" id="slide-1"
         style="background-image: url('{{ asset('images/ucad_bg.2.jpg') }}');"></div>
    <div class="absolute inset-0"
         style="background: linear-gradient(110deg,rgba(10,16,35,.80) 0%,rgba(10,16,35,.55) 45%,rgba(10,16,35,.78) 100%);"></div>
</div>

{{-- ===== LAYOUT ===== --}}
<div class="relative z-10 flex h-screen">


    {{-- ===== GAUCHE : Branding ===== --}}
    <div class="hidden lg:flex lg:w-[55%] flex-col justify-between p-12">

        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo_ucad.png') }}" alt="Logo UCAD"
                 class="h-14 w-auto object-contain drop-shadow-lg"
                 onerror="this.style.display='none'">
            <div class="border-l border-white/20 pl-4">
                <p class="text-white font-bold text-base tracking-wide leading-tight">Université Cheikh Anta Diop</p>
                <p class="text-white/50 text-xs tracking-wider">Dakar · Sénégal</p>
            </div>
        </div>

        {{-- Contenu central --}}
        <div>
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-7">
                <span class="pulse-dot w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">
                    Appel à contribution
                    @if($event->event_start_date) · {{ $event->event_start_date->translatedFormat('F Y') }} @endif
                </span>
            </div>

            {{-- Titre --}}
            @php
                $parts = preg_split('/(\d+)/', $event->event_name, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            @endphp
            <h1 class="text-6xl font-extrabold text-white tracking-tight drop-shadow-2xl leading-none mb-2">
                @foreach($parts as $part)
                    @if(is_numeric($part))
                        <span class="text-amber-400">{{ $part }}</span>
                    @else
                        {{ $part }}
                    @endif
                @endforeach
            </h1>
            <p class="text-xl text-white/70 font-light tracking-wide mb-6">
                Semaine de la Recherche et de l'Innovation
            </p>

            {{-- Description ou texte institutionnel --}}
            <div class="max-w-md space-y-4">
                @if($event->event_description)
                <p class="text-sm text-white/65 leading-relaxed">{{ $event->event_description }}</p>
                @else
                <p class="text-sm text-white/65 leading-relaxed">
                    Dans le cadre de l'organisation de la <span class="text-white/90 font-medium">{{ $event->event_name }}</span>,
                    la Direction de la Recherche et de l'Innovation (<span class="text-amber-400 font-semibold">DRI</span>)
                    lance un appel à contribution à l'ensemble des structures académiques, scientifiques et pédagogiques de l'UCAD.
                </p>
                @endif

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
                            <span class="font-semibold">{{ $kw }}</span> {{ $desc }}
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
                © {{ date('Y') }} Direction de la Recherche · UCAD · Dakar, Sénégal
            </p>
        </div>
    </div>

    {{-- ===== DROITE : Actions glass ===== --}}
    <div class="w-full lg:w-[45%] flex items-center justify-center p-6 lg:p-12">

        <div class="glass-form rounded-3xl shadow-2xl p-8 lg:p-10 w-full max-w-md">

            {{-- Header mobile --}}
            <div class="lg:hidden text-center mb-7">
                <img src="{{ asset('images/logo_ucad.png') }}" alt="Logo UCAD"
                     class="h-14 mx-auto mb-3 object-contain drop-shadow"
                     onerror="this.style.display='none'">
                <p class="text-white font-bold text-lg">{{ $event->event_name }} · UCAD</p>
                <p class="text-white/50 text-xs mt-0.5">Appel à Communication</p>
            </div>

            {{-- ── En-tête ── --}}
            <div class="mb-5">
                <p class="text-white font-bold text-lg leading-tight">{{ $event->event_name }}</p>
                <p class="text-white/40 text-xs mt-0.5">
                    @if($event->event_start_date)
                        {{ $event->event_start_date->translatedFormat('d F Y') }}
                        @if($event->event_end_date) – {{ $event->event_end_date->translatedFormat('d F Y') }} @endif
                    @else
                        Semaine de la Recherche et de l'Innovation
                    @endif
                </p>
            </div>

            {{-- ── Barre d'onglets ── --}}
            @php $hasQuestionnaire = $event->show_questionnaire; @endphp
            <div class="flex gap-1 p-1 rounded-xl mb-5" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12);">
                <button id="tab-btn-inscription" onclick="switchTab('inscription')"
                        class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                    </svg>
                    S'inscrire
                </button>
                @if($hasQuestionnaire)
                <button id="tab-btn-questionnaire" onclick="switchTab('questionnaire')"
                        class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                    Questionnaire
                </button>
                @endif
            </div>

            {{-- ── Panneau Inscription ── --}}
            <div id="tab-inscription">
                <p class="text-white/50 text-sm leading-relaxed mb-5">
                    Remplissez le formulaire d'inscription pour participer à l'événement.
                </p>
                <a href="{{ route('public.registration.show', $event->event_slug) }}"
                   class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl mb-4
                          bg-amber-400 hover:bg-amber-300 active:bg-amber-500
                          text-slate-900 font-bold text-sm tracking-wide
                          transition-all duration-200 shadow-lg hover:shadow-amber-400/30 active:scale-[.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                    </svg>
                    Accéder au formulaire d'inscription
                </a>
                @if($qrInscription)
                <div class="qr-frame p-3 rounded-xl bg-white/6 border border-white/10">
                    <div class="qr-box">{!! $qrInscription !!}</div>
                    <div>
                        <p class="text-white/65 text-xs font-semibold mb-0.5">Scanner pour s'inscrire</p>
                        <p class="text-white/35 text-xs leading-snug">Pointez la caméra de votre téléphone vers ce code</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── Panneau Questionnaire ── --}}
            @if($hasQuestionnaire)
            <div id="tab-questionnaire" style="display:none;">
                <p class="text-white/50 text-sm leading-relaxed mb-5">
                    Donnez votre avis sur l'événement en répondant au questionnaire d'appréciation.
                </p>
                <a href="{{ route('public.questionnaire.show', $event->event_slug) }}"
                   class="glass-btn w-full flex items-center justify-center gap-2 py-3.5 rounded-xl mb-4 text-white font-semibold text-sm tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                    Accéder au questionnaire
                </a>
                @if($qrQuestionnaire)
                <div class="qr-frame p-3 rounded-xl bg-white/6 border border-white/10">
                    <div class="qr-box">{!! $qrQuestionnaire !!}</div>
                    <div>
                        <p class="text-white/65 text-xs font-semibold mb-0.5">Scanner le questionnaire</p>
                        <p class="text-white/35 text-xs leading-snug">Pointez la caméra de votre téléphone vers ce code</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Séparateur --}}
            <div class="flex items-center gap-3 mt-6 mb-4">
                <div class="h-px flex-1 bg-white/10"></div>
                <span class="text-white/20 text-xs">•</span>
                <div class="h-px flex-1 bg-white/10"></div>
            </div>

            {{-- Lien espace membres --}}
            <div class="flex items-start gap-2.5 p-3.5 rounded-xl bg-white/6 border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/35 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-white/40 text-xs leading-relaxed">
                    Membre de l'équipe ?
                    <a href="{{ route('login') }}" class="text-amber-400/80 hover:text-amber-400 font-medium underline underline-offset-2 transition-colors">
                        Accéder à l'espace membres
                    </a>
                </p>
            </div>

            <p class="text-center text-white/20 text-xs mt-6">
                © {{ date('Y') }} SRI · Université Cheikh Anta Diop · Dakar
            </p>
        </div>
    </div>
</div>

<script>
    // ── Onglets ──────────────────────────────────────────────────────────
    function switchTab(tab) {
        const tabs = ['inscription', 'questionnaire'];
        tabs.forEach(function(t) {
            const panel = document.getElementById('tab-' + t);
            const btn   = document.getElementById('tab-btn-' + t);
            if (!panel || !btn) return;
            if (t === tab) {
                panel.style.display = 'block';
                btn.classList.add('active');
            } else {
                panel.style.display = 'none';
                btn.classList.remove('active');
            }
        });
    }
    // Activer l'onglet inscription par défaut
    switchTab('inscription');

    // ── Diaporama ────────────────────────────────────────────────────────
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
</script>
</body>
</html>
