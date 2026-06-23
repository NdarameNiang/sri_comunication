@extends('layouts.app')
@section('title', 'Gestion des Rôles')
@section('page-title', 'Rôles & Permissions')
@section('page-subtitle', 'Gérez les rôles et leurs droits d\'accès')

@section('content')
<div class="space-y-6">

    {{-- En-tête actions --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.roles.overview') }}" class="btn-secondary text-sm">
                Matrice des droits
            </a>
            <a href="{{ route('superadmin.permissions.index') }}" class="btn-secondary text-sm">
                Gérer les permissions
            </a>
        </div>
        <a href="{{ route('superadmin.roles.create') }}" class="btn-primary text-sm flex items-center gap-2">
            @include('components.icon', ['name' => 'plus'])
            Nouveau rôle
        </a>
    </div>

    {{-- Grille des rôles --}}
    @php
    $systemRoles = ['superadmin','direction_recherche','comite_scientifique','secretaire','point_focal','porteur_projet'];
    $colors = [
        'superadmin'          => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'badge' => 'bg-purple-600'],
        'direction_recherche' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'border' => 'border-blue-200',   'badge' => 'bg-blue-600'],
        'comite_scientifique' => ['bg' => 'bg-emerald-100','text' => 'text-emerald-700','border' => 'border-emerald-200','badge' => 'bg-emerald-600'],
        'secretaire'          => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'border' => 'border-amber-200',  'badge' => 'bg-amber-600'],
        'point_focal'         => ['bg' => 'bg-sky-100',    'text' => 'text-sky-700',    'border' => 'border-sky-200',    'badge' => 'bg-sky-600'],
        'porteur_projet'      => ['bg' => 'bg-rose-100',   'text' => 'text-rose-700',   'border' => 'border-rose-200',   'badge' => 'bg-rose-600'],
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($roles as $role)
        @php
            $c = $colors[$role->name] ?? ['bg'=>'bg-gray-100','text'=>'text-gray-700','border'=>'border-gray-200','badge'=>'bg-gray-600'];
            $isSystem = in_array($role->name, $systemRoles);
        @endphp
        <div class="bg-white rounded-2xl border {{ $c['border'] }} shadow-sm overflow-hidden flex flex-col">
            {{-- Header --}}
            <div class="{{ $c['bg'] }} px-5 py-4 flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-block w-2 h-2 rounded-full {{ $c['badge'] }}"></span>
                        <h3 class="text-sm font-bold {{ $c['text'] }}">{{ $role->label ?: $role->name }}</h3>
                    </div>
                    <p class="text-xs text-gray-400 font-mono">{{ $role->name }}</p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="{{ $c['bg'] }} {{ $c['text'] }} text-xs font-semibold px-2.5 py-1 rounded-full border {{ $c['border'] }}">
                        {{ $role->users_count }} user{{ $role->users_count !== 1 ? 's' : '' }}
                    </span>
                    @if($isSystem)
                    <span class="text-xs bg-gray-100 text-gray-400 px-2 py-1 rounded-full border border-gray-200">système</span>
                    @endif
                </div>
            </div>

            {{-- Permissions --}}
            <div class="px-5 py-4 flex-1">
                @if($role->permissions->count() > 0)
                <div class="flex flex-wrap gap-1.5">
                    @foreach($role->permissions->take(6) as $perm)
                    <span class="text-xs px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 font-mono">{{ $perm->name }}</span>
                    @endforeach
                    @if($role->permissions->count() > 6)
                    <span class="text-xs px-2 py-0.5 rounded-md bg-gray-100 text-gray-400">+{{ $role->permissions->count() - 6 }}</span>
                    @endif
                </div>
                @else
                <p class="text-xs text-gray-400 italic">Aucune permission assignée</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between gap-2">
                <span class="text-xs text-gray-400">{{ $role->permissions->count() }} permission{{ $role->permissions->count() !== 1 ? 's' : '' }}</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('superadmin.roles.edit', $role) }}"
                       class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                        @include('components.icon', ['name' => 'pencil'])
                        Modifier
                    </a>
                    @if(!$isSystem)
                    <form method="POST" action="{{ route('superadmin.roles.destroy', $role) }}" onsubmit="return confirm('Supprimer ce rôle ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 flex items-center gap-1">
                            @include('components.icon', ['name' => 'trash'])
                            Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
