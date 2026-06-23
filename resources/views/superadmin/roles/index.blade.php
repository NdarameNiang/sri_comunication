@extends('layouts.app')
@section('title', 'Rôles & Permissions')
@section('page-title', 'Rôles & Permissions')
@section('page-subtitle', 'Gérez les rôles et les droits associés')

@section('content')
@php
$systemRoles = ['superadmin','direction_recherche','comite_scientifique','secretaire','point_focal','porteur_projet'];

$palette = [
    'superadmin'          => ['dot' => 'bg-purple-500', 'pill' => 'bg-purple-50 text-purple-700 border-purple-200',  'row' => 'border-purple-100'],
    'direction_recherche' => ['dot' => 'bg-blue-500',   'pill' => 'bg-blue-50 text-blue-700 border-blue-200',        'row' => 'border-blue-100'],
    'comite_scientifique' => ['dot' => 'bg-emerald-500','pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200','row' => 'border-emerald-100'],
    'secretaire'          => ['dot' => 'bg-amber-500',  'pill' => 'bg-amber-50 text-amber-700 border-amber-200',     'row' => 'border-amber-100'],
    'point_focal'         => ['dot' => 'bg-sky-500',    'pill' => 'bg-sky-50 text-sky-700 border-sky-200',           'row' => 'border-sky-100'],
    'porteur_projet'      => ['dot' => 'bg-rose-500',   'pill' => 'bg-rose-50 text-rose-700 border-rose-200',        'row' => 'border-rose-100'],
];
$default = ['dot' => 'bg-gray-400', 'pill' => 'bg-gray-50 text-gray-600 border-gray-200', 'row' => 'border-gray-100'];
@endphp

<div class="space-y-5">

    {{-- Barre d'actions --}}
    <div class="flex items-center justify-end">
        <a href="{{ route('superadmin.roles.create') }}" class="btn-primary flex items-center gap-2 text-sm">
            @include('components.icon', ['name' => 'plus'])
            Nouveau rôle
        </a>
    </div>

    {{-- Liste des rôles --}}
    <div class="space-y-3">
        @foreach($roles as $role)
        @php
            $c       = $palette[$role->name] ?? $default;
            $system  = in_array($role->name, $systemRoles);
            $perms   = $role->permissions->sortBy('group');
            $byGroup = $perms->groupBy('group');
        @endphp

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- En-tête du rôle --}}
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b {{ $c['row'] }}">

                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-3 h-3 rounded-full {{ $c['dot'] }} shrink-0"></span>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm font-bold text-gray-900">{{ $role->label ?: $role->name }}</h3>
                            @if($system)
                            <span class="text-[10px] font-medium bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded border border-gray-200 uppercase tracking-wide">système</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $role->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    @if($role->real_user_count > 0)
                    <span class="text-xs text-gray-500 font-medium">
                        {{ $role->real_user_count }} utilisateur{{ $role->real_user_count > 1 ? 's' : '' }}
                    </span>
                    @else
                    <span class="text-xs text-gray-300">Aucun utilisateur</span>
                    @endif
                    <a href="{{ route('superadmin.roles.edit', $role) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                        @include('components.icon', ['name' => 'pencil'])
                        Modifier
                    </a>
                    @if(!$system)
                    <form method="POST" action="{{ route('superadmin.roles.destroy', $role) }}"
                          onsubmit="return confirm('Supprimer le rôle « {{ $role->label ?: $role->name }} » ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                            @include('components.icon', ['name' => 'trash'])
                            Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Permissions par groupe --}}
            @if($perms->count() > 0)
            <div class="px-5 py-3.5 flex flex-wrap gap-x-6 gap-y-3">
                @foreach($byGroup as $group => $groupPerms)
                <div class="flex items-start gap-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap mt-0.5 min-w-[5rem]">{{ $group }}</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($groupPerms as $perm)
                        <span class="text-xs px-2.5 py-1 rounded-lg border font-medium {{ $c['pill'] }}">
                            {{ $perm->label ?: $perm->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-3 text-xs text-gray-400 italic">
                Aucune permission assignée à ce rôle.
            </div>
            @endif

        </div>
        @endforeach
    </div>

</div>
@endsection
