@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Vue globale de la plateforme SRI 2026')

@section('content')
<div class="space-y-6">

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">

        @php
        $cards = [
            [
                'label' => 'Utilisateurs',
                'value' => $stats['total_users'],
                'icon'  => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'bg'    => 'bg-blue-100', 'fg' => 'text-blue-600',
            ],
            [
                'label' => 'Porteurs',
                'value' => $stats['porteurs'],
                'icon'  => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z',
                'bg'    => 'bg-emerald-100', 'fg' => 'text-emerald-600',
            ],
            [
                'label' => 'Structures',
                'value' => $stats['structures'],
                'icon'  => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
                'bg'    => 'bg-amber-100', 'fg' => 'text-amber-600',
            ],
            [
                'label' => 'Projets',
                'value' => $stats['projects'],
                'icon'  => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                'bg'    => 'bg-purple-100', 'fg' => 'text-purple-600',
            ],
            [
                'label' => 'Soumis',
                'value' => $stats['submitted'],
                'icon'  => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg'    => 'bg-cyan-100', 'fg' => 'text-cyan-600',
            ],
            [
                'label' => 'Sélectionnés',
                'value' => $stats['selected'],
                'icon'  => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
                'bg'    => 'bg-rose-100', 'fg' => 'text-rose-600',
            ],
        ];
        @endphp

        @foreach($cards as $card)
        <div class="stat-card flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div class="stat-icon {{ $card['bg'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $card['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $card['value'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Derniers utilisateurs --}}
        <div class="card">
            <div class="card-header">
                <h3 class="section-title text-base">Derniers utilisateurs</h3>
                <a href="{{ route('superadmin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tout →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentUsers as $user)
                <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50/50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shrink-0 shadow-sm">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                    <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }} shrink-0">
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-gray-400 text-sm">Aucun utilisateur</div>
                @endforelse
            </div>
        </div>

        {{-- Accès rapides --}}
        <div class="card">
            <div class="card-header">
                <h3 class="section-title text-base">Accès rapides</h3>
            </div>
            <div class="card-body grid grid-cols-2 gap-3">
                @foreach([
                    ['route' => 'superadmin.users.create',       'label' => 'Nouvel utilisateur', 'sub' => 'Créer un compte',         'bg' => 'bg-blue-100',   'fg' => 'text-blue-600',   'hbg' => 'group-hover:bg-blue-200'],
                    ['route' => 'direction.porteurs.create',     'label' => 'Nouveau porteur',    'sub' => 'Compte porteur de projet', 'bg' => 'bg-emerald-100','fg' => 'text-emerald-600','hbg' => 'group-hover:bg-emerald-200'],
                    ['route' => 'direction.point-focaux.create', 'label' => 'Observateur',        'sub' => 'Affecter un observateur',  'bg' => 'bg-amber-100',  'fg' => 'text-amber-600',  'hbg' => 'group-hover:bg-amber-200'],
                    ['route' => 'direction.comite.create',       'label' => 'Membre comité',      'sub' => 'Ajouter un évaluateur',   'bg' => 'bg-purple-100', 'fg' => 'text-purple-600', 'hbg' => 'group-hover:bg-purple-200'],
                ] as $action)
                <a href="{{ route($action['route']) }}"
                   class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all text-center group">
                    <div class="w-11 h-11 {{ $action['bg'] }} {{ $action['hbg'] }} rounded-xl flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $action['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $action['label'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $action['sub'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
