@extends('layouts.app')
@section('title', 'Porteurs de projet')
@section('page-title', 'Porteurs de projet')
@section('page-subtitle', 'Gestion par le Comité Scientifique')

@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('comite.porteurs.create') }}" class="btn-primary text-sm">+ Nouveau porteur</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Nom</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Structure</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Projets</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porteurs as $i => $porteur)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $porteurs->firstItem() + $i }}</td>
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $porteur->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $porteur->email }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $porteur->structure?->name ?? '–' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $porteur->projectAssignments->count() }}</td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('comite.porteurs.edit', $porteur) }}" class="text-blue-600 text-xs">Modifier</a>
                                <form method="POST" action="{{ route('comite.porteurs.send-credentials', $porteur) }}">
                                    @csrf
                                    <button type="submit" class="text-green-600 text-xs">Envoyer accès</button>
                                </form>
                                <form method="POST" action="{{ route('comite.porteurs.destroy', $porteur) }}"
                                      data-confirm="Supprimer ce porteur ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-gray-400 text-sm">Aucun porteur enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($porteurs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $porteurs->links() }}</div>
        @endif
    </div>
</div>
@endsection
