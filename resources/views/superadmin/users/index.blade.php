@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('page-subtitle', $users->total() . ' compte(s)')

@section('content')
<div class="space-y-4">

    {{-- Actions + filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end justify-between">
        <form method="GET" class="flex flex-wrap gap-3 items-end flex-1">
            <div class="flex-1 min-w-44">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom, email…"
                           class="input-field pl-9 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Rôle</label>
                <select name="role" class="input-field text-sm">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Structure</label>
                <select name="structure" class="input-field text-sm">
                    <option value="">Toutes</option>
                    @foreach($structures as $s)
                        <option value="{{ $s->id }}" {{ request('structure') == $s->id ? 'selected' : '' }}>{{ $s->acronym ?? $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['search','role','structure']))
                <a href="{{ route('superadmin.users.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
            @endif
        </form>
        <a href="{{ route('superadmin.users.create') }}" class="btn-primary text-sm flex items-center gap-1.5 shrink-0">
            @include('components.icon', ['name' => 'plus'])
            Nouvel utilisateur
        </a>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Structure</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $roleColors = [
                    'superadmin'          => 'badge-purple',
                    'direction_recherche' => 'badge-blue',
                    'comite_scientifique' => 'badge-red',
                    'secretaire'          => 'badge-gray',
                    'point_focal'         => 'badge-yellow',
                    'porteur_projet'      => 'badge-green',
                ];
                @endphp
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                <span class="text-blue-700 text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $user->email }}</td>
                    <td>
                        <span class="{{ $roleColors[$user->role] ?? 'badge-gray' }}">
                            {{ \App\Models\User::roleLabel($user->role) }}
                        </span>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $user->structure?->acronym ?? $user->structure?->name ?? '—' }}</td>
                    <td>
                        <span class="{{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn-secondary text-xs px-3 py-1.5">
                                @include('components.icon', ['name' => 'pencil'])
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('superadmin.users.toggle-active', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="{{ $user->is_active ? 'btn-danger' : 'btn-success' }} text-xs px-3 py-1.5">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline"
                                  data-confirm="Supprimer le compte de {{ $user->name }} ?"
                                  data-confirm-title="Supprimer l'utilisateur"
                                  data-confirm-type="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger text-xs px-3 py-1.5">
                                    @include('components.icon', ['name' => 'trash'])
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-400">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
