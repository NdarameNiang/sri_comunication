<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SRI 2026') – Appel à Communication</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans text-gray-900" style="background: linear-gradient(135deg, #f0f4ff 0%, #fafbff 50%, #f5f0ff 100%);">

{{-- Bandeau supérieur --}}
<div style="background: linear-gradient(90deg, #1e40af 0%, #3730a3 100%);" class="py-3 px-4">
    <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo_ucad.png') }}" alt="UCAD" class="h-8 w-auto object-contain brightness-0 invert opacity-90" onerror="this.style.display='none'">
            <span class="text-white/80 text-xs font-medium hidden sm:block">Université Cheikh Anta Diop de Dakar</span>
        </div>
        <span class="text-white/60 text-xs">@yield('event-name', 'SRI 2026')</span>
    </div>
</div>

{{-- Hero --}}
<div style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 60%, #4c1d95 100%);" class="py-10 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 text-white/80 text-xs font-medium px-3 py-1.5 rounded-full mb-4 backdrop-blur-sm border border-white/20">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            @yield('event-badge', 'Formulaire officiel')
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 tracking-tight">@yield('event-name', 'SRI 2026')</h1>
        <p class="text-blue-200 text-base">@yield('event-subtitle', 'Université Cheikh Anta Diop de Dakar')</p>
    </div>
</div>

{{-- Contenu --}}
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Alertes flash --}}
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

    <p class="text-center text-xs text-gray-400 mt-10 pb-6">© {{ date('Y') }} Université Cheikh Anta Diop de Dakar · Tous droits réservés</p>
</div>

@stack('scripts')
</body>
</html>
