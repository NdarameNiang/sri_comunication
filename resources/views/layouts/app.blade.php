<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SRI 2026') – SRI Appel à Communication</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        /* Intégration Select2 avec le style du projet */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            min-height: 2.625rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            color: #111827;
            background: #fff;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2rem;
            padding-left: 0.25rem;
            color: #111827;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.5rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.625rem;
            font-size: 0.875rem;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / .08);
            font-size: 0.875rem;
        }
        .select2-container { width: 100% !important; }
    </style>
</head>
<body class="h-full font-sans bg-gray-50 text-gray-900">

<div class="flex h-full">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0" style="background: #0f1629;">

        {{-- Logo --}}
        @php $activeEvent = \App\Models\EventConfig::active(); @endphp
        <div class="flex items-center gap-3 px-4 py-4 border-b border-white/10">
            <img src="{{ asset('images/logo_ucad.png') }}"
                 alt="Logo UCAD"
                 class="h-10 w-auto object-contain shrink-0 drop-shadow"
                 onerror="this.style.display='none'">
            <div class="flex-1 min-w-0 border-l border-white/15 pl-3">
                <p class="text-white font-bold text-sm leading-tight tracking-wide">{{ $activeEvent?->event_name ?? 'SRI 2026' }}</p>
                <p class="text-white/40 text-xs truncate">Appel à Communication</p>
            </div>
        </div>

        {{-- Profil utilisateur --}}
        <div class="px-4 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600/30 border border-blue-500/40 flex items-center justify-center shrink-0">
                    <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-white/40 text-xs truncate">{{ \App\Models\User::roleLabel(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            @php $role = auth()->user()->role; @endphp

            {{-- Super Admin --}}
            @if($role === 'superadmin')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Administration</p>
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('superadmin.roles.index') }}" class="sidebar-link {{ request()->routeIs('superadmin.roles.*') || request()->routeIs('superadmin.permissions.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'shield'])
                    <span>Rôles & Permissions</span>
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="sidebar-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'users'])
                    <span>Utilisateurs</span>
                </a>
                <div class="border-t border-white/10 my-3"></div>
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Paramétrage</p>
                <a href="{{ route('admin.event-configs.index') }}" class="sidebar-link {{ request()->routeIs('admin.event-configs.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'calendar'])
                    <span>Événements</span>
                </a>
                <a href="{{ route('admin.form-options.index') }}" class="sidebar-link {{ request()->routeIs('admin.form-options.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'cog'])
                    <span>Options formulaires</span>
                </a>
                <div class="border-t border-white/10 my-3"></div>
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Gestion</p>
                <a href="{{ route('direction.dashboard') }}" class="sidebar-link {{ request()->routeIs('direction.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'office'])
                    <span>Espace Organisateur</span>
                </a>
                <a href="{{ route('comite.dashboard') }}" class="sidebar-link {{ request()->routeIs('comite.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'star'])
                    <span>Espace Comité</span>
                </a>
                <a href="{{ route('secretaire.dashboard') }}" class="sidebar-link {{ request()->routeIs('secretaire.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'document'])
                    <span>Espace Secrétaire</span>
                </a>
            @endif

            {{-- Organisateur --}}
            @if($role === 'direction_recherche')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Gestion</p>
                <a href="{{ route('direction.dashboard') }}" class="sidebar-link {{ request()->routeIs('direction.dashboard') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('direction.porteurs.index') }}" class="sidebar-link {{ request()->routeIs('direction.porteurs.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'briefcase'])
                    <span>Porteurs de projet</span>
                </a>
                <a href="{{ route('direction.point-focaux.index') }}" class="sidebar-link {{ request()->routeIs('direction.point-focaux.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'map-pin'])
                    <span>Observateurs</span>
                </a>
                <a href="{{ route('direction.comite.index') }}" class="sidebar-link {{ request()->routeIs('direction.comite.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'star'])
                    <span>Comité Scientifique</span>
                </a>
                <div class="border-t border-white/10 my-3"></div>
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Paramétrage</p>
                <a href="{{ route('admin.event-configs.index') }}" class="sidebar-link {{ request()->routeIs('admin.event-configs.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'calendar'])
                    <span>Événements</span>
                </a>
                <a href="{{ route('admin.form-options.index') }}" class="sidebar-link {{ request()->routeIs('admin.form-options.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'cog'])
                    <span>Options formulaires</span>
                </a>
            @endif

            {{-- Porteur de Projet --}}
            @if($role === 'porteur_projet')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Mon espace</p>
                <a href="{{ route('porteur.dashboard') }}" class="sidebar-link {{ request()->routeIs('porteur.dashboard') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('porteur.dashboard') }}" class="sidebar-link {{ request()->routeIs('porteur.projects.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'document'])
                    <span>Mes projets</span>
                </a>
            @endif

            {{-- Observateur --}}
            @if($role === 'point_focal')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Observation</p>
                <a href="{{ route('point-focal.dashboard') }}" class="sidebar-link {{ request()->routeIs('point-focal.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
            @endif

            {{-- Comité Scientifique --}}
            @if($role === 'comite_scientifique')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Évaluation</p>
                <a href="{{ route('comite.dashboard') }}" class="sidebar-link {{ request()->routeIs('comite.dashboard') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('comite.porteurs.index') }}" class="sidebar-link {{ request()->routeIs('comite.porteurs.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'briefcase'])
                    <span>Porteurs de projet</span>
                </a>
                <a href="{{ route('comite.submission-period.edit') }}" class="sidebar-link {{ request()->routeIs('comite.submission-period.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'calendar'])
                    <span>Période de soumission</span>
                </a>
            @endif

            {{-- Secrétaire --}}
            @if($role === 'secretaire')
                <p class="text-white/30 text-xs uppercase tracking-widest px-3 mb-2">Gestion publique</p>
                <a href="{{ route('secretaire.dashboard') }}" class="sidebar-link {{ request()->routeIs('secretaire.dashboard') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'home'])
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('secretaire.inscriptions.index') }}" class="sidebar-link {{ request()->routeIs('secretaire.inscriptions.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'users'])
                    <span>Inscriptions</span>
                </a>
                <a href="{{ route('secretaire.questionnaires.index') }}" class="sidebar-link {{ request()->routeIs('secretaire.questionnaires.*') ? 'active' : '' }}">
                    @include('components.icon', ['name' => 'document'])
                    <span>Questionnaires</span>
                </a>
            @endif

        </nav>

        {{-- Changer mot de passe --}}
        <div class="px-3 pb-1 border-t border-white/10 pt-3">
            <a href="{{ route('profile.password') }}"
               class="sidebar-link {{ request()->routeIs('profile.password') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <span>Mot de passe</span>
            </a>
        </div>

        {{-- Déconnexion --}}
        <div class="px-3 py-3 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left hover:bg-red-600/20 hover:text-red-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== OVERLAY MOBILE ===== --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <div class="flex-1 flex flex-col min-h-screen lg:pl-64">

        {{-- ═══ HEADER PHOTO UCAD ════════════════════════════════════════════════ --}}
        <header class="sticky top-0 z-30 shadow-sm">

            {{-- Bandeau photo UCAD --}}
            <div class="relative overflow-hidden" style="height: 52px;">
                <img src="{{ asset('images/ucad_bg.2.jpg') }}"
                     alt=""
                     class="absolute inset-0 w-full h-full object-cover object-center scale-105"
                     onerror="this.style.display='none'">
                {{-- Overlay --}}
                <div class="absolute inset-0"
                     style="background: linear-gradient(90deg, rgba(10,16,40,0.88) 0%, rgba(15,25,60,0.72) 50%, rgba(10,16,40,0.85) 100%);"></div>

                {{-- Contenu bandeau --}}
                <div class="relative z-10 h-full flex items-center justify-between px-4 lg:px-6">

                    <div class="flex items-center gap-3">
                        {{-- Burger mobile --}}
                        <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>

                        {{-- Logo UCAD --}}
                        <img src="{{ asset('images/logo_ucad.png') }}"
                             alt="UCAD"
                             class="h-8 w-auto object-contain drop-shadow brightness-0 invert opacity-90"
                             onerror="this.style.display='none'">

                        {{-- Séparateur --}}
                        <div class="hidden sm:block h-5 w-px bg-white/20"></div>

                        {{-- Titre page --}}
                        <div class="hidden sm:block">
                            <p class="text-white font-semibold text-sm leading-none">@yield('page-title', 'Tableau de bord')</p>
                            @hasSection('page-subtitle')
                            <p class="text-white/50 text-xs mt-0.5">@yield('page-subtitle')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        {{-- Badge rôle --}}
                        @php
                            $roleColors = [
                                'superadmin'          => 'bg-purple-500/30 text-purple-200 border-purple-400/40',
                                'direction_recherche' => 'bg-blue-500/30 text-blue-200 border-blue-400/40',
                                'point_focal'         => 'bg-amber-500/30 text-amber-200 border-amber-400/40',
                                'porteur_projet'      => 'bg-emerald-500/30 text-emerald-200 border-emerald-400/40',
                                'comite_scientifique' => 'bg-rose-500/30 text-rose-200 border-rose-400/40',
                                'secretaire'          => 'bg-slate-500/30 text-slate-200 border-slate-400/40',
                            ];
                        @endphp
                        <span class="hidden sm:inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full border {{ $roleColors[auth()->user()->role] ?? 'bg-white/10 text-white/60 border-white/20' }}">
                            {{ \App\Models\User::roleLabel(auth()->user()->role) }}
                        </span>

                        {{-- Avatar + nom --}}
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-white/15 border border-white/25 flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="hidden md:block text-white/80 text-sm font-medium">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sous-barre blanche : titre page sur mobile --}}
            <div class="sm:hidden bg-white border-b border-gray-200 px-4 py-2.5">
                <p class="text-sm font-semibold text-gray-900">@yield('page-title', 'Tableau de bord')</p>
                @hasSection('page-subtitle')
                <p class="text-xs text-gray-500">@yield('page-subtitle')</p>
                @endif
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-4 lg:px-6 pt-4">
            @if(session('success'))
                <div class="alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('warning'))
                <div class="flex items-start gap-3 p-4 rounded-lg text-sm bg-amber-50 text-amber-800 border border-amber-200 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 px-4 lg:px-6 py-4 pb-8">
            @yield('content')
        </main>

    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // Applique Select2 à tous les selects de structure (simple)
        $('select[name="structure_id"]').select2({
            placeholder: '-- Sélectionner une structure --',
            allowClear: true,
            language: { noResults: () => 'Aucune structure trouvée' },
        });

        // Applique Select2 aux selects multiples (point focal)
        $('select[name="structure_ids[]"]').select2({
            placeholder: '-- Sélectionner une ou plusieurs structures --',
            language: { noResults: () => 'Aucune structure trouvée' },
            closeOnSelect: false,
        });
    });
</script>
@stack('scripts')
<script>
(function () {
    const EYE_OPEN = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>`;
    const EYE_OFF  = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.599m3.05-2.674A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.357 2.574M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/></svg>`;

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="password"]').forEach(function (input) {
            const parent = input.parentElement;
            if (getComputedStyle(parent).position === 'static') {
                parent.style.position = 'relative';
            }
            input.style.paddingRight = '2.75rem';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('tabindex', '-1');
            btn.style.cssText = 'position:absolute;top:0;right:0;height:100%;padding:0 0.65rem;display:flex;align-items:center;background:transparent;border:none;cursor:pointer;color:#9ca3af;';
            btn.innerHTML = EYE_OPEN;
            btn.addEventListener('mouseenter', () => btn.style.color = '#6b7280');
            btn.addEventListener('mouseleave', () => btn.style.color = '#9ca3af');
            btn.addEventListener('click', function () {
                const visible = input.type === 'text';
                input.type    = visible ? 'password' : 'text';
                btn.innerHTML = visible ? EYE_OPEN : EYE_OFF;
                btn.style.color = visible ? '#9ca3af' : '#3b82f6';
            });

            parent.appendChild(btn);
        });
    });
})();
</script>

{{-- ── Modal de confirmation global ──────────────────────────────── --}}
<div id="confirm-overlay"
     style="display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.5);
            backdrop-filter:blur(3px);align-items:center;justify-content:center;">
    <div id="confirm-box"
         style="background:#fff;border-radius:1rem;width:100%;max-width:400px;margin:1rem;
                box-shadow:0 20px 50px rgba(0,0,0,.25);overflow:hidden;
                transform:scale(.95);opacity:0;transition:transform .2s ease,opacity .2s ease;">
        <div style="padding:1.5rem 1.5rem 1rem;">
            <div style="display:flex;align-items:flex-start;gap:.75rem;">
                <div id="confirm-icon-wrap"
                     style="flex-shrink:0;width:2.5rem;height:2.5rem;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin-top:.1rem;">
                </div>
                <div>
                    <p id="confirm-title" style="font-weight:700;color:#111827;font-size:.95rem;margin:0 0 .35rem;"></p>
                    <p id="confirm-message" style="color:#6b7280;font-size:.85rem;line-height:1.55;margin:0;"></p>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;padding:.75rem 1.5rem 1.25rem;justify-content:flex-end;">
            <button id="confirm-cancel"
                    style="padding:.55rem 1.2rem;border-radius:.6rem;border:1px solid #e5e7eb;
                           background:#fff;color:#374151;font-size:.875rem;font-weight:500;cursor:pointer;">
                Annuler
            </button>
            <button id="confirm-ok"
                    style="padding:.55rem 1.2rem;border-radius:.6rem;border:none;
                           font-size:.875rem;font-weight:600;cursor:pointer;color:#fff;">
                Confirmer
            </button>
        </div>
    </div>
</div>
<script>
(function () {
    const overlay  = document.getElementById('confirm-overlay');
    const box      = document.getElementById('confirm-box');
    const titleEl  = document.getElementById('confirm-title');
    const msgEl    = document.getElementById('confirm-message');
    const okBtn    = document.getElementById('confirm-ok');
    const cancelBtn= document.getElementById('confirm-cancel');
    const iconWrap = document.getElementById('confirm-icon-wrap');

    let _resolve = null;

    const ICONS = {
        danger: { bg:'#fee2e2', color:'#dc2626', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>' },
        warning:{ bg:'#fef3c7', color:'#d97706', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>' },
        info:   { bg:'#dbeafe', color:'#2563eb', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
    };

    function openModal(title, message, type) {
        const icon = ICONS[type] || ICONS.warning;
        iconWrap.style.background = icon.bg;
        iconWrap.style.color      = icon.color;
        iconWrap.innerHTML        = icon.svg;
        okBtn.style.background    = icon.color;
        titleEl.textContent       = title;
        msgEl.textContent         = message;

        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            box.style.transform = 'scale(1)';
            box.style.opacity   = '1';
        });

        return new Promise(resolve => { _resolve = resolve; });
    }

    function closeModal(result) {
        box.style.transform = 'scale(.95)';
        box.style.opacity   = '0';
        setTimeout(() => { overlay.style.display = 'none'; }, 180);
        if (_resolve) { _resolve(result); _resolve = null; }
    }

    okBtn.addEventListener('click',     () => closeModal(true));
    cancelBtn.addEventListener('click', () => closeModal(false));
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(false); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(false); });

    // Intercepte les formulaires avec data-confirm
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        const msg  = form.dataset.confirm;
        const title= form.dataset.confirmTitle || 'Confirmation';
        const type = form.dataset.confirmType  || 'warning';
        if (!msg) return;
        e.preventDefault();
        const ok = await openModal(title, msg, type);
        if (ok) { form.removeAttribute('data-confirm'); form.submit(); }
    }, true);

    // Boutons standalone avec data-confirm (non-form)
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-confirm-btn]');
        if (!btn) return;
        e.preventDefault();
        const ok = await openModal(
            btn.dataset.confirmTitle || 'Confirmation',
            btn.dataset.confirmBtn,
            btn.dataset.confirmType || 'warning'
        );
        if (ok && btn.form) { btn.form.removeAttribute('data-confirm'); btn.form.submit(); }
    }, true);
})();
</script>

</body>
</html>
