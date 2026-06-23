<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SRI 2026') – Appel à Communication · UCAD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes kenburns-pub {
            from { transform: scale(1); }
            to   { transform: scale(1.06); }
        }
        .hero-photo {
            animation: kenburns-pub 14s ease-in-out infinite alternate;
        }
        @keyframes fadein-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fadein-up { animation: fadein-up .7s ease both; }
        .fadein-up-2 { animation: fadein-up .7s .15s ease both; }
        .fadein-up-3 { animation: fadein-up .7s .3s ease both; }
    </style>
</head>
<body class="min-h-full font-sans text-gray-900 bg-gray-100">

{{-- ═══ HERO PHOTO ════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="min-height: 320px; max-height: 380px;">

    {{-- Photo UCAD en fond avec effet Ken Burns --}}
    <img src="{{ asset('images/ucad_bg_1.jpg') }}"
         alt="Campus UCAD"
         class="hero-photo absolute inset-0 w-full h-full object-cover"
         onerror="this.style.background='linear-gradient(135deg,#1e3a8a,#312e81)'; this.style.display='block';">

    {{-- Overlay dégradé sombre --}}
    <div class="absolute inset-0"
         style="background: linear-gradient(
             160deg,
             rgba(10, 15, 40, 0.82) 0%,
             rgba(15, 22, 60, 0.68) 50%,
             rgba(10, 15, 40, 0.78) 100%
         );"></div>

    {{-- Nav overlay --}}
    <div class="absolute top-0 inset-x-0 z-20 flex items-center justify-between px-5 py-3">
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-white/70 hover:text-white transition-colors"
           style="text-shadow:0 1px 3px rgba(0,0,0,0.4);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Accueil
        </a>
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white px-3 py-1.5 rounded-full transition-all"
           style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.22);backdrop-filter:blur(10px);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
            </svg>
            Espace membres
        </a>
    </div>

    {{-- Contenu hero --}}
    <div class="relative z-10 flex flex-col items-center justify-center text-center px-4 py-14 h-full" style="min-height: 320px;">

        {{-- Logo UCAD --}}
        <div class="fadein-up mb-5">
            <img src="{{ asset('images/logo_ucad.png') }}"
                 alt="Logo UCAD"
                 class="h-16 w-auto object-contain mx-auto drop-shadow-2xl"
                 style="filter: brightness(0) invert(1);"
                 onerror="this.style.display='none'">
        </div>

        {{-- Badge --}}
        <div class="fadein-up-2 inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="text-white/80 text-xs font-semibold tracking-wide uppercase">
                @yield('event-badge', 'Formulaire officiel')
            </span>
        </div>

        {{-- Titre --}}
        <h1 class="fadein-up-2 text-4xl sm:text-5xl font-extrabold text-white tracking-tight drop-shadow-xl leading-none mb-2">
            @yield('event-name', 'SRI 2026')
        </h1>
        <p class="fadein-up-3 text-blue-200/90 text-base sm:text-lg font-light tracking-wide">
            @yield('event-subtitle', 'Université Cheikh Anta Diop de Dakar')
        </p>
        <p class="fadein-up-3 text-white/40 text-xs mt-2 tracking-wider">
            Dakar · Sénégal
        </p>
    </div>
</div>

{{-- ═══ CONTENU ════════════════════════════════════════════════════════════════ --}}
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Alertes --}}
    @if(session('success'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm mb-6 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm mb-6 shadow-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    @yield('content')

    {{-- Footer --}}
    <div class="mt-10 pb-6 text-center space-y-1">
        <img src="{{ asset('images/logo_ucad.png') }}"
             alt="UCAD"
             class="h-8 w-auto object-contain mx-auto opacity-25 grayscale"
             onerror="this.style.display='none'">
        <p class="text-xs text-gray-400 mt-2">© {{ date('Y') }} Université Cheikh Anta Diop de Dakar · Tous droits réservés</p>
    </div>
</div>

@stack('scripts')
</body>
</html>
