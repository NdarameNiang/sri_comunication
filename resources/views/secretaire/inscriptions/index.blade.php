@extends('layouts.app')
@section('title', 'Inscriptions')
@section('page-title', 'Inscriptions')
@section('page-subtitle', $event?->event_name ?? '')

@section('content')
<div class="space-y-4">

    {{-- Stats rapides --}}
    @if($stats)
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500">Total inscrits</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-700">{{ $stats['presents'] }}</p>
                <p class="text-xs text-gray-500">Présents</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-amber-600">{{ $stats['absents'] }}</p>
                <p class="text-xs text-gray-500">Non confirmés</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('secretaire.inscriptions.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email, institution…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Type</label>
                <select name="type" class="input-field text-sm">
                    <option value="">Tous les types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
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

    {{-- Actions : Export / Import --}}
    <div class="flex flex-wrap gap-3 items-start">
        <a href="{{ route('secretaire.inscriptions.export') }}"
           class="btn-secondary text-sm flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Exporter Excel (CSV)
        </a>
        <button type="button" onclick="document.getElementById('import-form').classList.toggle('hidden')"
                class="btn-secondary text-sm flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/>
            </svg>
            Importer Excel (CSV)
        </button>
    </div>

    {{-- Formulaire d'import --}}
    <div id="import-form" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-blue-800 mb-1">Importer des participants depuis un fichier CSV</p>
        <p class="text-xs text-blue-600 mb-3">
            Colonnes requises (séparateur <code class="bg-blue-100 px-1 rounded">;</code>) :
            <strong>Nom ; Prénom ; Email ; Téléphone ; Institution ; Fonction ; Type participant ; Présence (Oui/Non)</strong>
        </p>
        <form method="POST" action="{{ route('secretaire.inscriptions.import') }}" enctype="multipart/form-data"
              class="flex flex-wrap gap-3 items-center">
            @csrf
            <input type="file" name="fichier" accept=".csv,.txt" required
                   class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 border border-gray-300 rounded-lg p-1">
            <button type="submit" class="btn-primary text-sm">Importer</button>
            <button type="button" onclick="document.getElementById('import-form').classList.add('hidden')"
                    class="btn-secondary text-sm">Annuler</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">
                {{ $registrations->total() }} inscription(s)
                @if(request()->hasAny(['search','type','presence']))
                    <span class="text-xs text-gray-400 ml-1">(filtrés)</span>
                @endif
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Nom complet</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Email</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Institution</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Date inscription</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-semibold">Présence</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($registrations as $i => $reg)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $registrations->firstItem() + $i }}</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-900">{{ $reg->fullName() }}</span>
                        </td>
                        <td class="py-3 px-4 text-gray-500 text-xs">{{ $reg->email ?? '–' }}</td>
                        <td class="py-3 px-4 text-gray-600 text-xs">{{ $reg->institution ?? '–' }}</td>
                        <td class="py-3 px-4">
                            @if($reg->type_participant)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-medium">{{ $reg->type_participant }}</span>
                            @else
                                <span class="text-gray-400 text-xs">–</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ $reg->created_at->format('d/m/Y') }}<br>
                            <span class="text-gray-400">{{ $reg->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($reg->presence_confirmee)
                                {{-- Déjà présent : badge vert + bouton pour marquer absent --}}
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Présent
                                    </span>
                                    <form method="POST" action="{{ route('secretaire.inscriptions.presence', $reg) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs text-gray-400 hover:text-red-500 underline transition-colors">
                                            Marquer absent
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- Absent : bouton bien visible pour marquer présent --}}
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Absent
                                    </span>
                                    <form method="POST" action="{{ route('secretaire.inscriptions.presence', $reg) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 border border-emerald-300 hover:border-emerald-500 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded-full transition-colors">
                                            ✓ Marquer présent
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2 items-center">
                                <a href="{{ route('secretaire.inscriptions.show', $reg) }}"
                                   class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Voir
                                </a>
                                <form method="POST" action="{{ route('secretaire.inscriptions.destroy', $reg) }}"
                                      data-confirm="Supprimer l'inscription de {{ $reg->fullName() }} ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 text-xs text-red-400 hover:text-red-600 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-gray-400 text-sm">Aucune inscription trouvée.</p>
                        </td>
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
