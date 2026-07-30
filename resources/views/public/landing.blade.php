<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->event_name }} – UCAD</title>
    <link rel="icon" type="image/png" href="/favicon-ucad.png">
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
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.6);
        }

        /* ---- Bouton action glass ---- */
        .glass-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all .2s ease;
        }
        .glass-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .glass-btn:active { transform: scale(.98); }

        /* ---- Onglets ---- */
        .tab-btn { color: #64748b; }
        .tab-btn.active {
            background: #fbbf24;
            color: #1e293b;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

    </style>
</head>
<body class="font-sans">

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
<div class="relative z-10 flex flex-col lg:flex-row min-h-screen">


    {{-- ===== GAUCHE : Branding ===== --}}
    <div class="hidden lg:flex lg:w-[55%] flex-col justify-between p-12">

        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo_ucad.png') }}" alt="Logo UCAD"
                 class="h-14 w-auto object-contain drop-shadow-lg"
                 onerror="this.style.display='none'">
            <div class="border-l border-white/20 pl-4">
                <p class="text-white font-bold text-base tracking-wide leading-tight">Université Cheikh Anta Diop</p>
                <p class="text-white/70 text-xs tracking-wider">Dakar · Sénégal</p>
            </div>
        </div>

        {{-- Contenu central --}}
        <div>
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-7">
                <span class="pulse-dot w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">
                    {{ \App\Models\ContentBlock::resolve('landing.badge_text', $event)?->content ?? 'Appel à contribution' }}
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
            <div class="inline-block mb-5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-lg">
                <p class="text-lg sm:text-xl font-bold text-white tracking-wide">
                    <span class="text-amber-400">SRI</span> — Semaine de la Recherche et de l'Innovation
                </p>
            </div>

            {{-- Dates de l'événement --}}
            @if($event->event_start_date)
            <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 rounded-lg bg-white/10 border border-white/15">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <span class="text-white text-sm font-semibold tracking-wide">
                    {{ $event->event_start_date->translatedFormat('d F Y') }}
                    @if($event->event_end_date) – {{ $event->event_end_date->translatedFormat('d F Y') }} @endif
                </span>
            </div>
            @endif

            {{-- Description ou texte institutionnel --}}
            <div class="max-w-md space-y-4">
                @php
                    $introBlock = \App\Models\ContentBlock::resolve('landing.intro', $event);
                    $objectivesBlock = \App\Models\ContentBlock::resolve('landing.objectives', $event);
                    $objectives = $objectivesBlock?->content_json ?: [
                        ['title' => 'Faire connaître', 'description' => "les capacités scientifiques et technologiques de l'UCAD"],
                        ['title' => 'Créer',           'description' => 'des passerelles opérationnelles entre chercheurs, décideurs publics et acteurs socio-économiques, collectivités territoriales'],
                        ['title' => 'Renforcer',       'description' => "l'ancrage de la recherche dans les dynamiques de transformation socio-économique"],
                        ['title' => 'Mobiliser',       'description' => "des financements publics et privés en faveur de la recherche, de l'innovation et de la valorisation"],
                    ];
                @endphp
                @if($event->event_description)
                <p class="text-sm text-white/90 leading-relaxed">{{ $event->event_description }}</p>
                @elseif($introBlock)
                <p class="text-sm text-white/90 leading-relaxed">{{ $introBlock->content }}</p>
                @else
                <p class="text-sm text-white/90 leading-relaxed">
                    Dans le cadre de l'organisation de la <span class="text-white font-medium">{{ $event->event_name }}</span>,
                    la Direction de la Recherche et de l'Innovation (<span class="text-amber-400 font-semibold">DRI</span>)
                    lance un appel à contribution à l'ensemble des structures académiques, scientifiques et pédagogiques de l'UCAD.
                </p>
                @endif

                <div class="space-y-2">
                    <p class="text-xs text-white uppercase tracking-widest font-semibold mb-3">Cet événement vise à</p>
                    @foreach($objectives as $item)
                    <div class="flex items-start gap-3">
                        <div class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></div>
                        <p class="text-sm text-white leading-snug">
                            <span class="font-semibold">{{ $item['title'] }}</span> {{ $item['description'] }}
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
            <p class="text-white/45 text-xs tracking-wide">
                © {{ date('Y') }} {{ \App\Models\ContentBlock::resolve('landing.footer', $event)?->content ?? 'Direction de la Recherche · UCAD · Dakar, Sénégal' }}
            </p>
        </div>
    </div>

    {{-- ===== DROITE : Actions glass ===== --}}
    <div class="w-full lg:w-[45%] flex items-center justify-center px-4 py-8 lg:p-12">

        <div class="glass-form rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-10 w-full max-w-md">

            {{-- Header mobile --}}
            <div class="lg:hidden text-center mb-7">
                <img src="{{ asset('images/logo_ucad.png') }}" alt="Logo UCAD"
                     class="h-14 mx-auto mb-3 object-contain drop-shadow"
                     onerror="this.style.display='none'">
                <p class="text-slate-900 font-bold text-lg">{{ $event->event_name }} · UCAD</p>
                <p class="text-slate-500 text-xs mt-0.5">Appel à Communication</p>
            </div>

            {{-- ── En-tête ── --}}
            <div class="mb-5">
                <p class="text-slate-900 font-bold text-lg leading-tight">{{ $event->event_name }}</p>
                <p class="text-slate-500 text-xs mt-0.5 mb-1.5">
                    <span class="text-amber-600 font-semibold">SRI</span> — Semaine de la Recherche et de l'Innovation
                </p>
                @if($event->event_start_date)
                <p class="text-amber-600 text-sm font-semibold tracking-wide">
                    {{ $event->event_start_date->translatedFormat('d F Y') }}
                    @if($event->event_end_date) – {{ $event->event_end_date->translatedFormat('d F Y') }} @endif
                </p>
                @endif
            </div>

            {{-- ── Barre d'onglets ── --}}
            @php $hasQuestionnaire = $event->show_questionnaire; @endphp
            <div class="flex gap-1 p-1 rounded-xl mb-5 bg-slate-100 border border-slate-200">
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
                <p class="text-slate-600 text-sm leading-relaxed mb-5">
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
            </div>

            {{-- ── Panneau Questionnaire ── --}}
            @if($hasQuestionnaire)
            <div id="tab-questionnaire" style="display:none;">
                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                    Donnez votre avis sur l'événement en répondant au questionnaire d'appréciation.
                </p>
                <a href="{{ route('public.questionnaire.show', $event->event_slug) }}"
                   class="glass-btn w-full flex items-center justify-center gap-2 py-3.5 rounded-xl mb-4 text-slate-800 font-semibold text-sm tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                    Accéder au questionnaire
                </a>
            </div>
            @endif

            {{-- Séparateur --}}
            <div class="flex items-center gap-3 mt-6 mb-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-slate-300 text-xs">•</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            {{-- Lien espace membres --}}
            <div class="flex items-start gap-2.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Membre de l'équipe ?
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-medium underline underline-offset-2 transition-colors">
                        Accéder à l'espace membres
                    </a>
                </p>
            </div>

            <p class="text-center text-slate-400 text-xs mt-6">
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
