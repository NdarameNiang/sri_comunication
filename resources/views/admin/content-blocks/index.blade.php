@extends('layouts.app')
@section('title', 'Contenu page publique')
@section('page-title', 'Contenu de la page publique')
@section('page-subtitle', 'Éditer les textes affichés sur la page d\'accueil sans déploiement de code')

@section('content')
<div class="space-y-4">

    {{-- Sélecteurs --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Bloc de contenu</label>
                <select name="key" onchange="this.form.submit()" class="input-field text-sm">
                    @foreach($keys as $k => $meta)
                        <option value="{{ $k }}" {{ $key === $k ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Événement</label>
                <select name="event_config_id" onchange="this.form.submit()" class="input-field text-sm">
                    <option value="" {{ is_null($eventId) ? 'selected' : '' }}>Texte par défaut (tous les événements)</option>
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}" {{ $eventId === $ev->id ? 'selected' : '' }}>{{ $ev->event_name }}{{ $ev->is_active ? ' (actif)' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.content-blocks.edit', ['key' => $key, 'event_config_id' => $eventId]) }}" class="btn-primary text-sm">
                Modifier
            </a>
        </form>
    </div>

    {{-- Aperçu --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">{{ $keys[$key]['label'] }}</p>
            @if($block)
                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-medium">Personnalisé</span>
            @else
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500 font-medium">Texte par défaut (codé en dur)</span>
            @endif
        </div>
        <div class="p-5">
            @if($block)
                @if($block->type === 'list')
                    <div class="space-y-3">
                        @forelse($block->content_json ?? [] as $item)
                        <div class="flex items-start gap-3">
                            <div class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></div>
                            <p class="text-sm text-gray-700"><span class="font-semibold">{{ $item['title'] }}</span> {{ $item['description'] }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">Aucun élément.</p>
                        @endforelse
                    </div>
                @else
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $block->content }}</p>
                @endif

                <form method="POST" action="{{ route('admin.content-blocks.destroy') }}" class="mt-4"
                      data-confirm="Revenir au texte par défaut ?" data-confirm-type="warning">
                    @csrf @method('DELETE')
                    <input type="hidden" name="key" value="{{ $key }}">
                    <input type="hidden" name="event_config_id" value="{{ $eventId }}">
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">Réinitialiser (revenir au texte par défaut)</button>
                </form>
            @else
                <p class="text-sm text-gray-400 italic">Aucune personnalisation — la page publique affiche son texte par défaut codé en dur pour ce bloc.</p>
            @endif
        </div>
    </div>
</div>
@endsection
