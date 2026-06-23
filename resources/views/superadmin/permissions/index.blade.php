@extends('layouts.app')
@section('title', 'Permissions')
@section('page-title', 'Permissions')
@section('page-subtitle', 'Liste de toutes les permissions disponibles')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between gap-4 flex-wrap">
        <a href="{{ route('superadmin.roles.index') }}" class="btn-secondary text-sm">
            @include('components.icon', ['name' => 'arrow-left'])
            Retour aux rôles
        </a>
        <a href="{{ route('superadmin.permissions.create') }}" class="btn-primary text-sm flex items-center gap-2">
            @include('components.icon', ['name' => 'plus'])
            Nouvelle permission
        </a>
    </div>

    @foreach($permissions as $group => $perms)
    <div class="card overflow-hidden">
        <div class="card-header bg-gray-50">
            <h3 class="section-title text-sm uppercase tracking-wide">{{ $group }}</h3>
            <span class="text-xs text-gray-400">{{ $perms->count() }} permission{{ $perms->count() !== 1 ? 's' : '' }}</span>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($perms as $perm)
            <div class="flex items-center justify-between gap-4 px-5 py-3 hover:bg-gray-50/50 transition-colors">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $perm->label ?: $perm->name }}</p>
                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $perm->name }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('superadmin.permissions.edit', $perm) }}"
                       class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                        @include('components.icon', ['name' => 'pencil'])
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('superadmin.permissions.destroy', $perm) }}"
                          onsubmit="return confirm('Supprimer cette permission ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 flex items-center gap-1">
                            @include('components.icon', ['name' => 'trash'])
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

</div>
@endsection
