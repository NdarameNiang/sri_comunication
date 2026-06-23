<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SRI 2026') – Appel à Communication</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans bg-gray-50 text-gray-900">

<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo_ucad.png') }}" alt="UCAD" class="h-14 w-auto object-contain mx-auto mb-4" onerror="this.style.display='none'">
        <h1 class="text-2xl font-bold text-gray-900">@yield('event-name', 'SRI 2026')</h1>
        <p class="text-gray-500 text-sm mt-1">@yield('event-subtitle', 'Université Cheikh Anta Diop de Dakar')</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm mb-6">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm mb-6">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @yield('content')

    <p class="text-center text-xs text-gray-400 mt-10">© {{ date('Y') }} UCAD – Tous droits réservés</p>
</div>

</body>
</html>
