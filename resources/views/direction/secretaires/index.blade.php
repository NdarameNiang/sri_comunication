@extends('layouts.app')
@section('title', 'Secrétaires')
@section('page-title', 'Espace Secrétariat')
@section('page-subtitle', $secretaires->total() . ' secrétaire(s)')

@section('content')
<div class="space-y-4">
    <div class="page-header">
        <div></div>
        <a href="{{ route('direction.secretaires.create') }}" class="btn-primary text-sm flex items-center gap-1.5">
            @include('components.icon', ['name' => 'plus'])
            Nouveau secrétaire
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($secretaires as $s)
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <span class="text-slate-600 text-xs font-semibold">{{ strtoupper(substr($s->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-900">{{ $s->name }}</span>
                        </div>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $s->email }}</td>
                    <td class="text-gray-500 text-sm">{{ $s->phone ?? '—' }}</td>
                    <td>
                        <span class="{{ $s->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $s->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('direction.secretaires.edit', $s) }}" class="btn-secondary text-xs px-3 py-1.5">
                                @include('components.icon', ['name' => 'pencil'])
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('direction.secretaires.destroy', $s) }}"
                                  data-confirm="Supprimer le compte de {{ $s->name }} ?"
                                  data-confirm-type="danger">
                                @csrf @method('DELETE')
                                <button class="btn-danger text-xs px-3 py-1.5">
                                    @include('components.icon', ['name' => 'trash'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">Aucun secrétaire enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $secretaires->links() }}
</div>
@endsection
