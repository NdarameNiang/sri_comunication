<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->event_name }}</title>
    <link rel="icon" type="image/png" href="/favicon-ucad.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --brand-primary: {{ $event->primaryColor() }}; }
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
        .slide-dot.active { background: var(--brand-primary); width: 22px; }

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
            background: var(--brand-primary);
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

        /* ---- Lisibilité garantie du texte sur photo, quel que soit le fond ---- */
        .text-shadow-strong { text-shadow: 0 2px 12px rgba(0,0,0,0.55), 0 1px 2px rgba(0,0,0,0.5); }

        /* ---- Animation d'apparition au défilement ---- */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
        }
        .reveal.reveal-visible { opacity: 1; transform: translateY(0); }

        /* ---- Cartes de section : légère élévation au survol ---- */
        .detail-card { transition: transform .3s ease, box-shadow .3s ease; }
        .detail-card:hover { transform: translateY(-3px); box-shadow: 0 24px 48px -12px rgba(15,23,42,0.16); }
        .detail-image { transition: transform .5s ease; }
        .detail-card:hover .detail-image { transform: scale(1.035); }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .detail-card, .detail-image { transition: none; }
        }
    </style>
</head>
<body class="font-sans">

{{-- ===== FOND DIAPORAMA ===== --}}
<div class="fixed inset-0 z-0">
    <div class="bg-slide active" id="slide-0"
         style="background-image: url('{{ $event->hero_image_url }}');"></div>
    <div class="bg-slide" id="slide-1"
         style="background-image: url('{{ asset('images/ucad_bg.2.jpg') }}');"></div>
    <div class="absolute inset-0"
         style="background: linear-gradient(115deg,rgba(8,13,30,.92) 0%,rgba(8,13,30,.78) 45%,rgba(8,13,30,.90) 100%);"></div>
    <div class="absolute inset-0" style="background: radial-gradient(ellipse 60% 50% at 30% 40%, rgba(8,13,30,.35), transparent);"></div>
</div>

{{-- ===== LAYOUT ===== --}}
<div class="relative z-10 flex flex-col lg:flex-row min-h-screen">


    {{-- ===== GAUCHE : Branding ===== --}}
    <div class="hidden lg:flex lg:w-[55%] flex-col justify-between p-12">

        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <img src="{{ $event->logo_url }}" alt="Logo"
                 class="h-14 w-auto object-contain drop-shadow-lg"
                 onerror="this.style.display='none'">
            <div class="border-l border-white/20 pl-4">
                <p class="text-white font-bold text-base tracking-wide leading-tight">Université Cheikh Anta Diop</p>
                <p class="text-white/70 text-xs tracking-wider">Dakar · Sénégal</p>
            </div>
        </div>

        {{-- Contenu central --}}
        <div>
            {{-- Organisateur --}}
            <div class="inline-block mb-5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-lg">
                <p class="text-lg sm:text-xl font-bold text-white tracking-wide">
                    {{ $event->organizer ?? 'Direction de la Recherche et de l\'Innovation – UCAD' }}
                </p>
            </div>

            {{-- Titre --}}
            @php
                $parts = preg_split('/(\d+)/', $event->event_name, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            @endphp
            <h1 class="text-6xl font-extrabold text-white tracking-tight text-shadow-strong leading-none mb-2">
                @foreach($parts as $part)
                    @if(is_numeric($part))
                        <span class="text-blue-300">{{ $part }}</span>
                    @else
                        {{ $part }}
                    @endif
                @endforeach
            </h1>

            {{-- Sous-titre dynamique --}}
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-5 py-2 mb-7">
                <span class="pulse-dot w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">
                    {{ $event->landingSubtitle() ?? 'Appel à contribution' }}
                </span>
            </div>

            {{-- Dates de l'événement --}}
            @if($event->event_start_date)
            <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 rounded-lg bg-white/10 border border-white/15">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                @php $introBlock = \App\Models\ContentBlock::resolve('landing.intro', $event); @endphp
                @if($event->event_description)
                <p class="text-sm text-white leading-relaxed text-shadow-strong">{{ $event->event_description }}</p>
                @elseif($introBlock)
                <p class="text-sm text-white leading-relaxed text-shadow-strong">{{ $introBlock->content }}</p>
                @else
                <p class="text-sm text-white leading-relaxed text-shadow-strong">
                    Dans le cadre de l'organisation de la <span class="font-medium">{{ $event->event_name }}</span>,
                    la Direction de la Recherche et de l'Innovation (<span class="text-blue-300 font-semibold">DRI</span>)
                    lance un appel à contribution à l'ensemble des structures académiques, scientifiques et pédagogiques de l'UCAD.
                </p>
                @endif

                @if(\App\Models\ContentBlock::sectionsFor($event)->count())
                <a href="#appel-details" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-300 hover:text-blue-200 transition-colors">
                    Découvrir l'appel en détail
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </a>
                @endif
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
                <img src="{{ $event->logo_url }}" alt="Logo"
                     class="h-14 mx-auto mb-3 object-contain drop-shadow"
                     onerror="this.style.display='none'">
                <p class="text-slate-900 font-bold text-lg">{{ $event->event_name }}</p>
                <p class="text-slate-500 text-xs mt-0.5">Appel à Communication</p>
            </div>

            {{-- ── En-tête ── --}}
            <div class="mb-5">
                <p class="text-slate-900 font-bold text-lg leading-tight">{{ $event->event_name }}</p>
                <p class="text-slate-500 text-xs mt-0.5 mb-1.5">
                    {{ $event->organizer ?? 'Direction de la Recherche et de l\'Innovation – UCAD' }}
                </p>
                @if($event->event_start_date)
                <p class="text-blue-600 text-sm font-semibold tracking-wide">
                    {{ $event->event_start_date->translatedFormat('d F Y') }}
                    @if($event->event_end_date) – {{ $event->event_end_date->translatedFormat('d F Y') }} @endif
                </p>
                @endif
            </div>

            {{-- ── Barre d'onglets ── --}}
            @php
                $hasQuestionnaire = $event->show_questionnaire;
                $inscriptionStatus = $event->inscriptionStatus();
                // Masqué si jamais configuré ou si la date d'ouverture n'est pas encore atteinte ;
                // visible (avec message de clôture) si la période est passée.
                $hasInscription = in_array($inscriptionStatus, ['open', 'closed']);
                $submissionStatus = $event->allow_public_submission ? $event->submissionStatus() : 'not_configured';
                $hasSubmission = $event->allow_public_submission && in_array($submissionStatus, ['open', 'closed']);
            @endphp
            <div class="flex gap-1 p-1 rounded-xl mb-5 bg-slate-100 border border-slate-200">
                @if($hasInscription)
                <button id="tab-btn-inscription" onclick="switchTab('inscription')"
                        class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                    </svg>
                    S'inscrire
                </button>
                @endif
                @if($hasQuestionnaire)
                <button id="tab-btn-questionnaire" onclick="switchTab('questionnaire')"
                        class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                    Questionnaire
                </button>
                @endif
                @if($hasSubmission)
                <button id="tab-btn-soumission" onclick="switchTab('soumission')"
                        class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    Appel à projet
                </button>
                @endif
            </div>

            {{-- ── Panneau Inscription ── --}}
            @if($hasInscription)
            <div id="tab-inscription">
                @if($inscriptionStatus === 'closed')
                <div class="flex items-start gap-2.5 p-4 rounded-xl bg-slate-100 border border-slate-200 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <div>
                        <p class="text-slate-700 text-sm font-semibold">Inscriptions closes</p>
                        <p class="text-slate-500 text-xs mt-0.5">La période d'inscription s'est terminée le {{ $event->inscription_close_at->translatedFormat('d F Y à H:i') }}.</p>
                    </div>
                </div>
                @else
                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                    Remplissez le formulaire d'inscription pour participer à l'événement.
                </p>
                <a href="{{ route('public.registration.show', $event->event_slug) }}"
                   class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl mb-4
                          bg-[var(--brand-primary)] hover:opacity-90 active:opacity-80
                          text-white font-bold text-sm tracking-wide
                          transition-all duration-200 shadow-lg hover:shadow-blue-600/30 active:scale-[.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                    </svg>
                    Accéder au formulaire d'inscription
                </a>
                @endif
            </div>
            @endif

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

            {{-- ── Panneau Soumission de projet ── --}}
            @if($hasSubmission)
            <div id="tab-soumission" style="display:none;">
                @if($submissionStatus === 'closed')
                <div class="flex items-start gap-2.5 p-4 rounded-xl bg-slate-100 border border-slate-200 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <div>
                        <p class="text-slate-700 text-sm font-semibold">Dépôt clôturé</p>
                        <p class="text-slate-500 text-xs mt-0.5">La période de dépôt s'est terminée le {{ $event->submission_close_at->translatedFormat('d F Y à H:i') }}.</p>
                    </div>
                </div>
                @else
                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                    Vous n'avez pas encore de compte porteur ? Créez votre dossier directement — un espace vous est ouvert automatiquement.
                </p>
                <a href="{{ route('public.project-submission.identify', $event->event_slug) }}"
                   class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl mb-4
                          bg-[var(--brand-primary)] hover:opacity-90 active:opacity-80
                          text-white font-bold text-sm tracking-wide
                          transition-all duration-200 shadow-lg hover:shadow-blue-600/30 active:scale-[.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    Créer mon dossier de soumission
                </a>
                @endif
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
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium underline underline-offset-2 transition-colors">
                        Accéder à l'espace membres
                    </a>
                </p>
            </div>

            <p class="text-center text-slate-400 text-xs mt-6">
                © {{ date('Y') }} {{ \App\Models\ContentBlock::resolve('landing.footer', $event)?->content ?? 'Université Cheikh Anta Diop · Dakar' }}
            </p>
        </div>
    </div>
</div>

{{-- ===== DÉTAILS DE L'APPEL — sections libres pilotées par l'admin ===== --}}
@php $sections = \App\Models\ContentBlock::sectionsFor($event); @endphp
@if($sections->count())
<div id="appel-details" class="relative z-10 scroll-mt-4" style="background: linear-gradient(180deg,#f8fafc 0%,#eef2f9 100%);">

    {{-- Séparateur décoratif en haut de section --}}
    <svg class="block w-full text-[#f8fafc]" style="margin-top:-1px" viewBox="0 0 1440 48" fill="none" preserveAspectRatio="none" height="48">
        <path d="M0 48 C 360 0, 1080 0, 1440 48 L1440 0 L0 0 Z" fill="currentColor"/>
    </svg>

    <div class="max-w-5xl mx-auto px-4 sm:px-8 py-14 sm:py-20">

        <div class="reveal text-center mb-14">
            <p class="text-blue-600 text-xs font-bold uppercase tracking-[0.2em] mb-3">L'appel en détail</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Tout ce qu'il faut savoir</h2>
            <div class="w-14 h-1 bg-blue-600 rounded-full mx-auto mt-5"></div>
        </div>

        <div class="space-y-8 sm:space-y-12">
            @foreach($sections as $index => $section)
            @php $imageRight = $index % 2 === 1; @endphp

            @if($section->image_url)
            {{-- Section avec image : mise en page alternée gauche/droite --}}
            <div class="reveal detail-card bg-white rounded-3xl shadow-xl shadow-slate-200/60 overflow-hidden grid grid-cols-1 md:grid-cols-2">
                <div class="relative h-56 md:h-auto overflow-hidden {{ $imageRight ? 'md:order-2' : '' }}">
                    <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="detail-image absolute inset-0 w-full h-full object-cover">
                </div>
                <div class="p-7 sm:p-10 flex flex-col justify-center {{ $imageRight ? 'md:order-1' : '' }}">
                    <span class="text-xs font-bold text-blue-600 tracking-widest uppercase mb-2">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4">{{ $section->title }}</h3>
                    @include('public.partials.section-content', ['section' => $section])
                </div>
            </div>
            @else
            {{-- Section sans image : carte pleine largeur --}}
            <div class="reveal detail-card bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-7 sm:p-10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">{{ $section->title }}</h3>
                </div>
                <div class="sm:pl-12">
                    @include('public.partials.section-content', ['section' => $section])
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <div class="reveal text-center mt-14">
            <a href="{{ route('public.registration.show', $event->event_slug) }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-[var(--brand-primary)] hover:opacity-90 text-white font-bold text-sm tracking-wide shadow-lg transition-all active:scale-[.98]">
                Je participe à l'appel
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5-5m5 5H6"/></svg>
            </a>
        </div>
    </div>
</div>
@endif

<script>
    // ── Onglets ──────────────────────────────────────────────────────────
    function switchTab(tab) {
        const tabs = ['inscription', 'questionnaire', 'soumission'];
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
    // Activer le premier onglet disponible par défaut (inscription si présente, sinon le suivant)
    switchTab(@json($hasInscription ? 'inscription' : ($hasQuestionnaire ? 'questionnaire' : 'soumission')));

    // ── Animation d'apparition au défilement ────────────────────────────────
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.reveal').forEach((el, i) => {
            el.style.transitionDelay = Math.min(i * 60, 240) + 'ms';
            revealObserver.observe(el);
        });
    } else {
        document.querySelectorAll('.reveal').forEach(el => el.classList.add('reveal-visible'));
    }

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
