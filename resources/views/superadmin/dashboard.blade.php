@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue globale de la plateforme SRI 2026')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
        $cards = [
            ['label' => 'Utilisateurs',    'value' => $stats['total_users'],  'color' => 'bg-blue-500',    'icon' => 'users'],
            ['label' => 'Porteurs',        'value' => $stats['porteurs'],     'color' => 'bg-emerald-500', 'icon' => 'briefcase'],
            ['label' => 'Structures',      'value' => $stats['structures'],   'color' => 'bg-amber-500',   'icon' => 'office'],
            ['label' => 'Projets',         'value' => $stats['projects'],     'color' => 'bg-purple-500',  'icon' => 'document'],
            ['label' => 'Soumis',          'value' => $stats['submitted'],    'color' => 'bg-cyan-500',    'icon' => 'check-circle'],
            ['label' => 'Sélectionnés',    'value' => $stats['selected'],     'color' => 'bg-rose-500',    'icon' => 'star'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 {{ $card['color'] }} rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        @include('components.icon', ['name' => $card['icon']])
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Derniers utilisateurs --}}
        <div class="card">
            <div class="card-header">
                <h3 class="section-title text-base">Derniers utilisateurs</h3>
                <a href="{{ route('superadmin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir tout →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentUsers as $user)
                <div class="px-6 py-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                        <span class="text-blue-700 text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                    <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }} shrink-0">
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Aucun utilisateur</div>
                @endforelse
            </div>
        </div>

        {{-- Accès rapides --}}
        <div class="card">
            <div class="card-header">
                <h3 class="section-title text-base">Accès rapides</h3>
            </div>
            <div class="card-body grid grid-cols-2 gap-3">
                <a href="{{ route('superadmin.users.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all text-center group">
                    <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ 'M12 4.5v15m7.5-7.5h-15' }}"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Nouvel utilisateur</span>
                </a>
                <a href="{{ route('direction.porteurs.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all text-center group">
                    <div class="w-10 h-10 bg-emerald-100 group-hover:bg-emerald-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ 'M12 4.5v15m7.5-7.5h-15' }}"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Nouveau porteur</span>
                </a>
                <a href="{{ route('direction.point-focaux.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 hover:border-amber-300 hover:bg-amber-50 transition-all text-center group">
                    <div class="w-10 h-10 bg-amber-100 group-hover:bg-amber-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ 'M12 4.5v15m7.5-7.5h-15' }}"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Nouveau point focal</span>
                </a>
                <a href="{{ route('direction.comite.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all text-center group">
                    <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ 'M12 4.5v15m7.5-7.5h-15' }}"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Membre comité</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
