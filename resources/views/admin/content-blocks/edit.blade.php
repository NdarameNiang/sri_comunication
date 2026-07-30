@extends('layouts.app')
@section('title', 'Modifier le contenu')
@section('page-title', 'Modifier : ' . $keys[$key]['label'])

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">

        <div class="mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg text-xs text-blue-700">
            @if($eventId)
                Ce texte s'appliquera uniquement à l'événement sélectionné.
            @else
                Ce texte par défaut s'appliquera à tous les événements qui n'ont pas de version spécifique.
            @endif
        </div>

        <form method="POST" action="{{ route('admin.content-blocks.update') }}" class="space-y-5">
            @csrf @method('PUT')
            <input type="hidden" name="key" value="{{ $key }}">
            <input type="hidden" name="event_config_id" value="{{ $eventId }}">

            @if($keys[$key]['type'] === 'list')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Éléments de la liste</label>
                    <div id="items-list" class="space-y-3">
                        @php $items = $block->content_json ?? []; if (empty($items)) { $items = [['title' => '', 'description' => '']]; } @endphp
                        @foreach($items as $idx => $item)
                        <div class="item-row border border-gray-200 rounded-xl p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-medium text-gray-500">Élément {{ $idx + 1 }}</p>
                                @if($idx > 0)
                                <button type="button" onclick="removeItem(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
                                @endif
                            </div>
                            <input type="text" name="items[{{ $idx }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Mot-clé (ex : Renforcer)" class="input-field text-sm">
                            <input type="text" name="items[{{ $idx }}][description]" value="{{ $item['description'] ?? '' }}" placeholder="Description" class="input-field text-sm">
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addItem()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Ajouter un élément</button>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Texte</label>
                    <textarea name="content" rows="{{ $keys[$key]['type'] === 'richtext' ? 6 : 2 }}" class="input-field">{{ $block->content ?? '' }}</textarea>
                    @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.content-blocks.index', ['key' => $key, 'event_config_id' => $eventId]) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

@if($keys[$key]['type'] === 'list')
<script>
    let itemCount = document.querySelectorAll('.item-row').length;
    function addItem() {
        const idx = itemCount++;
        const div = document.createElement('div');
        div.className = 'item-row border border-gray-200 rounded-xl p-4 space-y-2';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500">Élément ${idx + 1}</p>
                <button type="button" onclick="removeItem(this)" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
            </div>
            <input type="text" name="items[${idx}][title]" placeholder="Mot-clé (ex : Renforcer)" class="input-field text-sm">
            <input type="text" name="items[${idx}][description]" placeholder="Description" class="input-field text-sm">
        `;
        document.getElementById('items-list').appendChild(div);
    }
    function removeItem(btn) {
        btn.closest('.item-row').remove();
    }
</script>
@endif
@endsection
