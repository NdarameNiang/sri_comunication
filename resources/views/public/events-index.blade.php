<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRI · UCAD – Appels à Communication</title>
    <link rel="icon" type="image/png" href="/favicon-ucad.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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
            animation: kenburns 10s ease-in-out forwards;
        }
        @keyframes kenburns {
            from { transform: scale(1); }
            to   { transform: scale(1.06); }
        }
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* ---- Carrousel texte du hero ---- */
        .hero-text-slide {
            display: none;
            animation: fadein-up .6s ease both;
        }
        .hero-text-slide.active { display: block; }
        @keyframes fadein-up {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-dot {
            height: 6px; width: 6px;
            border-radius: 999px;
            background: rgba(255,255,255,0.35);
            cursor: pointer;
            transition: all 0.4s ease;
        }
        .hero-dot.active { background: #f59e0b; width: 24px; }

        .event-card {
            background: #fff;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="font-sans bg-slate-50">

{{-- ===== BANNIÈRE PHOTO (contenue, pas toute la page) ===== --}}
<div class="relative overflow-hidden" style="min-height: 520px;">

    <div class="bg-slide active" id="bg-slide-0" style="background-image: url('{{ asset('images/ucad_bg_1.jpg') }}');"></div>
    <div class="bg-slide" id="bg-slide-1" style="background-image: url('{{ asset('images/ucad_bg.2.jpg') }}');"></div>
    <div class="absolute inset-0"
         style="background: linear-gradient(160deg,rgba(10,16,35,.85) 0%,rgba(10,16,35,.62) 45%,rgba(10,16,35,.85) 100%);"></div>

    <div class="relative z-10 flex flex-col" style="min-height: 520px;">

        {{-- ===== NAV ===== --}}
        <div class="flex items-center justify-between px-5 sm:px-10 py-5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_ucad.png') }}" alt="Logo UCAD"
                     class="h-10 w-auto object-contain drop-shadow" onerror="this.style.display='none'">
                <div class="hidden sm:block border-l border-white/20 pl-3">
                    <p class="text-white font-semibold text-sm leading-tight">Université Cheikh Anta Diop</p>
                    <p class="text-white/50 text-xs">Dakar · Sénégal</p>
                </div>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white px-3.5 py-2 rounded-full transition-all"
               style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.22);backdrop-filter:blur(10px);">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Espace membres
            </a>
        </div>

        {{-- ===== HERO INSTITUTIONNEL — carrousel texte synchronisé avec les photos ===== --}}
        <div class="flex-1 flex flex-col justify-center px-5 sm:px-10 py-8 text-center max-w-3xl mx-auto">

            {{-- Slide 1 : définition SRI --}}
            <div class="hero-text-slide active" id="hero-text-0">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-6">
                    <span class="pulse-dot w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                    <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">Direction de la Recherche et de l'Innovation</span>
                </div>

                <div class="inline-block mb-5 px-6 py-3.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-lg">
                    <p class="text-xl sm:text-2xl font-bold text-white tracking-wide">
                        <span class="text-amber-400">SRI</span> — Semaine de la Recherche et de l'Innovation
                    </p>
                </div>

                <p class="text-sm sm:text-base text-white/90 leading-relaxed max-w-2xl mx-auto">
                    La <span class="text-white font-medium">DRI</span> lance des appels à contribution à l'ensemble
                    des structures académiques, scientifiques et pédagogiques de l'UCAD. Découvrez ci-dessous les
                    événements ouverts et à venir.
                </p>
            </div>

            {{-- Slide 2 : objectifs --}}
            <div class="hero-text-slide" id="hero-text-1">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-6">
                    <span class="pulse-dot w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                    <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">Cet événement vise à</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-xl mx-auto text-left">
                    @foreach([
                        ['Faire connaître', "les capacités scientifiques et technologiques de l'UCAD"],
                        ['Créer',           'des passerelles entre chercheurs, décideurs publics et acteurs socio-économiques'],
                        ['Renforcer',       "l'ancrage de la recherche dans les dynamiques de transformation socio-économique"],
                        ['Mobiliser',       "des financements en faveur de la recherche, de l'innovation et de la valorisation"],
                    ] as [$kw, $desc])
                    <div class="flex items-start gap-2.5">
                        <div class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></div>
                        <p class="text-xs sm:text-sm text-white/80 leading-snug">
                            <span class="font-semibold text-white">{{ $kw }}</span> {{ $desc }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Points de navigation --}}
            <div class="flex items-center justify-center gap-2 mt-7" id="hero-dots">
                <button class="hero-dot active" onclick="goToHeroSlide(0)" aria-label="Diapositive 1"></button>
                <button class="hero-dot"        onclick="goToHeroSlide(1)" aria-label="Diapositive 2"></button>
            </div>
        </div>
    </div>
</div>

{{-- ===== LISTE DES ÉVÉNEMENTS (fond clair) ===== --}}
<div class="px-5 sm:px-10 py-14">
    <div class="max-w-5xl mx-auto">

        @if($events->isEmpty())
            <div class="event-card rounded-2xl shadow-xl p-10 text-center max-w-md mx-auto border border-slate-100">
                <p class="text-slate-700 font-semibold mb-1">Aucun événement disponible pour le moment</p>
                <p class="text-slate-500 text-sm">Revenez bientôt ou contactez la Direction de la Recherche.</p>
            </div>
        @else
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest mb-4 text-center">
                Événements disponibles
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                @foreach($events as $event)
                    @php
                        $statusLabel = 'Événement';
                        $statusClasses = 'bg-slate-100 text-slate-500 border-slate-200';
                        if ($event->is_active) {
                            $statusLabel = 'Actif';
                            $statusClasses = 'bg-amber-100 text-amber-700 border-amber-200';
                        } elseif ($event->event_end_date && $event->event_end_date->isPast()) {
                            $statusLabel = 'Terminé';
                            $statusClasses = 'bg-slate-100 text-slate-500 border-slate-200';
                        } elseif ($event->event_start_date && $event->event_start_date->isFuture()) {
                            $statusLabel = 'À venir';
                            $statusClasses = 'bg-blue-50 text-blue-600 border-blue-200';
                        }
                    @endphp
                    <a href="{{ route('public.landing', $event->event_slug) }}"
                       class="event-card rounded-2xl shadow-lg border border-slate-100 p-7 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <h3 class="text-slate-900 font-bold text-xl leading-tight">{{ $event->event_name }}</h3>
                            <span class="shrink-0 text-[11px] font-semibold px-2.5 py-1 rounded-full border {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        @if($event->event_start_date)
                        <div class="flex items-center gap-1.5 text-amber-600 text-xs font-semibold mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            {{ $event->event_start_date->translatedFormat('d F Y') }}
                            @if($event->event_end_date) – {{ $event->event_end_date->translatedFormat('d F Y') }} @endif
                        </div>
                        @endif

                        <p class="text-slate-600 text-base leading-loose mb-6 flex-1">
                            {{ \Illuminate\Support\Str::limit($event->event_description ?: "Appel à contribution de la Direction de la Recherche et de l'Innovation.", 180) }}
                        </p>

                        <span class="inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-amber-400 text-slate-900 font-bold text-sm tracking-wide">
                            Découvrir l'événement
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ===== FOOTER ===== --}}
<p class="text-center text-slate-400 text-xs pb-8">
    © {{ date('Y') }} SRI · Université Cheikh Anta Diop · Dakar
</p>

<script>
    const HERO_TOTAL = 2, HERO_DELAY = 6000;
    let heroCur = 0, heroTimer;

    function goToHeroSlide(i) {
        document.getElementById('bg-slide-' + heroCur).classList.remove('active');
        document.getElementById('hero-text-' + heroCur).classList.remove('active');
        document.querySelectorAll('.hero-dot')[heroCur].classList.remove('active');

        heroCur = i;

        document.getElementById('bg-slide-' + heroCur).classList.add('active');
        document.getElementById('hero-text-' + heroCur).classList.add('active');
        document.querySelectorAll('.hero-dot')[heroCur].classList.add('active');

        clearInterval(heroTimer);
        heroTimer = setInterval(heroNext, HERO_DELAY);
    }
    function heroNext() { goToHeroSlide((heroCur + 1) % HERO_TOTAL); }
    heroTimer = setInterval(heroNext, HERO_DELAY);
</script>
</body>
</html>
