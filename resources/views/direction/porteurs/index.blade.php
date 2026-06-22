@extends('layouts.app')
@section('title', 'Porteurs de projet')
@section('page-title', 'Porteurs de projet')
@section('page-subtitle', 'Gestion des porteurs de projets par structure')

@section('content')
<div class="space-y-4">
    <div class="page-header">
        <div></div>
        <a href="{{ route('direction.porteurs.create') }}" class="btn-primary">
            @include('components.icon', ['name' => 'plus'])
            Nouveau porteur
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Porteur</th>
                    <th>Contact</th>
                    <th>Structure</th>
                    <th>Projets assignés</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($porteurs as $porteur)
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <span class="text-emerald-700 text-xs font-semibold">{{ strtoupper(substr($porteur->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-900">{{ $porteur->name }}</span>
                        </div>
                    </td>
                    <td>
                        <p class="text-sm text-gray-600">{{ $porteur->email }}</p>
                        @if($porteur->phone)
                        <p class="text-xs text-gray-400">{{ $porteur->phone }}</p>
                        @endif
                    </td>
                    <td>
                        @if($porteur->structure)
                            <span class="badge-blue">{{ $porteur->structure->acronym }}</span>
                            <p class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ $porteur->structure->name }}</p>
                        @else
                            <span class="badge-gray">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="space-y-1">
                            @forelse($porteur->projectAssignments as $assignment)
                            <div class="flex items-center gap-1.5">
                                <span class="{{ $assignment->status === 'submitted' ? 'badge-green' : 'badge-yellow' }} text-xs">
                                    {{ $assignment->status === 'submitted' ? '✓' : '⏳' }}
                                </span>
                                <span class="text-xs text-gray-600 truncate max-w-xs">{{ $assignment->title }}</span>
                            </div>
                            @empty
                            <span class="text-xs text-gray-400">Aucun projet</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <span class="{{ $porteur->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $porteur->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('direction.porteurs.show', $porteur) }}" class="btn-secondary text-xs px-3 py-1.5" title="Voir">
                                @include('components.icon', ['name' => 'eye'])
                            </a>
                            <a href="{{ route('direction.porteurs.edit', $porteur) }}" class="btn-secondary text-xs px-3 py-1.5" title="Modifier">
                                @include('components.icon', ['name' => 'pencil'])
                            </a>
                            {{-- Envoyer les accès par mail --}}
                            <form method="POST" action="{{ route('direction.porteurs.send-credentials', $porteur) }}" class="inline"
                                  data-confirm="Générer un nouveau mot de passe temporaire et envoyer les accès à {{ $porteur->email }} ?"
                                  data-confirm-title="Envoi des identifiants"
                                  data-confirm-type="info">
                                @csrf
                                <button type="submit" class="btn-secondary text-xs px-3 py-1.5 text-blue-600 hover:bg-blue-50" title="Envoyer les accès par email">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('direction.porteurs.destroy', $porteur) }}" class="inline"
                                  data-confirm="Supprimer {{ $porteur->name }} et tous ses projets ? Cette action est irréversible."
                                  data-confirm-title="Supprimer le porteur"
                                  data-confirm-type="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger text-xs px-3 py-1.5" title="Supprimer">
                                    @include('components.icon', ['name' => 'trash'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-12 text-gray-400">
                    <p class="text-base mb-2">Aucun porteur de projet</p>
                    <a href="{{ route('direction.porteurs.create') }}" class="btn-primary text-sm">Créer le premier porteur</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $porteurs->links() }}
</div>
@endsection
