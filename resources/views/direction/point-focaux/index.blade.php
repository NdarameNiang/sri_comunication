@extends('layouts.app')
@section('title', 'Observateurs')
@section('page-title', 'Observateurs')
@section('page-subtitle', 'Suivi des projets par structure')

@section('content')
<div class="space-y-4">
    <div class="page-header">
        <div></div>
        <a href="{{ route('direction.point-focaux.create') }}" class="btn-primary">
            @include('components.icon', ['name' => 'plus'])
            Nouvel Observateur
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Structures observées</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pointFocaux as $pf)
                <tr>
                    <td class="font-medium text-gray-900">{{ $pf->name }}</td>
                    <td>
                        <p class="text-sm text-gray-600">{{ $pf->email }}</p>
                        @if($pf->phone)<p class="text-xs text-gray-400">{{ $pf->phone }}</p>@endif
                    </td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @forelse($pf->structures as $s)
                                <span class="badge-blue text-xs">{{ $s->acronym }}</span>
                            @empty
                                <span class="badge-gray">Aucune</span>
                            @endforelse
                        </div>
                    </td>
                    <td><span class="{{ $pf->is_active ? 'badge-green' : 'badge-red' }}">{{ $pf->is_active ? 'Actif' : 'Inactif' }}</span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('direction.point-focaux.edit', $pf) }}" class="btn-secondary text-xs px-3 py-1.5">
                                @include('components.icon', ['name' => 'pencil'])
                            </a>
                            <form method="POST" action="{{ route('direction.point-focaux.destroy', $pf) }}" class="inline"
                                  data-confirm="Supprimer l'observateur {{ $pf->name }} ?"
                                  data-confirm-title="Supprimer l'observateur"
                                  data-confirm-type="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger text-xs px-3 py-1.5">@include('components.icon', ['name' => 'trash'])</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">Aucun Observateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $pointFocaux->links() }}
</div>
@endsection
