@extends('layouts.app')
@section('title', 'Comité Scientifique')
@section('page-title', 'Comité Scientifique')
@section('page-subtitle', 'Membres évaluateurs des projets')

@section('content')
<div class="space-y-4">
    <div class="page-header">
        <div></div>
        <a href="{{ route('direction.comite.create') }}" class="btn-primary">
            @include('components.icon', ['name' => 'plus'])
            Ajouter un membre
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($membres as $membre)
                <tr>
                    <td class="font-medium text-gray-900">{{ $membre->name }}</td>
                    <td class="text-gray-500">{{ $membre->email }}</td>
                    <td class="text-gray-500">{{ $membre->phone ?? '—' }}</td>
                    <td><span class="{{ $membre->is_active ? 'badge-green' : 'badge-red' }}">{{ $membre->is_active ? 'Actif' : 'Inactif' }}</span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('direction.comite.edit', $membre) }}" class="btn-secondary text-xs px-3 py-1.5">@include('components.icon', ['name' => 'pencil'])</a>
                            <form method="POST" action="{{ route('direction.comite.destroy', $membre) }}" class="inline"
                                  data-confirm="Supprimer {{ $membre->name }} du comité scientifique ?"
                                  data-confirm-title="Supprimer le membre"
                                  data-confirm-type="danger">
                                @csrf @method('DELETE')
                                <button class="btn-danger text-xs px-3 py-1.5">@include('components.icon', ['name' => 'trash'])</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">Aucun membre du comité.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $membres->links() }}
</div>
@endsection
