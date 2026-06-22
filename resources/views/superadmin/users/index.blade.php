@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('page-subtitle', 'Tous les comptes de la plateforme')

@section('content')
<div class="space-y-4">
    <div class="page-header">
        <div></div>
        <a href="{{ route('superadmin.users.create') }}" class="btn-primary">
            @include('components.icon', ['name' => 'plus'])
            Nouvel utilisateur
        </a>
    </div>

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
                    <td class="text-gray-500">{{ $user->email }}</td>
                    <td>
                        @php
                        $roleColors = ['superadmin'=>'badge-purple','direction_recherche'=>'badge-blue','point_focal'=>'badge-yellow','porteur_projet'=>'badge-green','comite_scientifique'=>'badge-red'];
                        @endphp
                        <span class="{{ $roleColors[$user->role] ?? 'badge-gray' }}">{{ \App\Models\User::roleLabel($user->role) }}</span>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $user->structure?->acronym ?? '—' }}</td>
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
                                  data-confirm="Supprimer le compte de {{ $user->name }} ? Cette action est irréversible."
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
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
