@extends('layouts.app')
@section('title', 'Inscriptions')
@section('page-title', 'Inscriptions')
@section('page-subtitle', $event?->event_name ?? '')

@section('content')
<div class="space-y-4">

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email, institution…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Présence</label>
                <select name="presence" class="input-field text-sm">
                    <option value="">Tous</option>
                    <option value="1" {{ request('presence') === '1' ? 'selected' : '' }}>Présents</option>
                    <option value="0" {{ request('presence') === '0' ? 'selected' : '' }}>Non confirmés</option>
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            <a href="{{ route('secretaire.inscriptions.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">{{ $registrations->total() }} inscription(s)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Nom</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Institution</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Type</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Présence</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $i => $reg)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $registrations->firstItem() + $i }}</td>
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $reg->fullName() }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $reg->email ?? '–' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $reg->institution ?? '–' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $reg->type_participant ?? '–' }}</td>
                        <td class="py-3 px-4">
                            <form method="POST" action="{{ route('secretaire.inscriptions.presence', $reg) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2 py-1 rounded-full font-medium transition {{ $reg->presence_confirmee ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $reg->presence_confirmee ? 'Présent' : 'Absent' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('secretaire.inscriptions.show', $reg) }}" class="text-blue-600 hover:text-blue-800 text-xs">Voir</a>
                                <form method="POST" action="{{ route('secretaire.inscriptions.destroy', $reg) }}"
                                      data-confirm="Supprimer cette inscription ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400 text-sm">Aucune inscription trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registrations->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
