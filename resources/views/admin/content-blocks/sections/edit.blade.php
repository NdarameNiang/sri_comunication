@extends('layouts.app')
@section('title', 'Modifier la section')
@section('page-title', 'Modifier : ' . $section->title)
@section('page-subtitle', $section->event->event_name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.content-blocks.sections.update', $section) }}" class="space-y-5" id="section-form">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la section <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $section->title) }}" required class="input-field">
                @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de contenu <span class="text-red-500">*</span></label>
                <select name="type" id="type-select" class="input-field" onchange="toggleType()">
                    <option value="list" {{ old('type', $section->type) === 'list' ? 'selected' : '' }}>Liste à puces</option>
                    <option value="richtext" {{ old('type', $section->type) === 'richtext' ? 'selected' : '' }}>Paragraphe de texte</option>
                </select>
            </div>

            <div id="richtext-field" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Texte</label>
                <textarea name="content" rows="6" class="input-field">{{ old('content', $section->content) }}</textarea>
            </div>

            <div id="list-field">
                <label class="block text-sm font-medium text-gray-700 mb-2">Éléments de la liste</label>
                @php $items = $section->content_json ?: [['title' => '', 'description' => '']]; @endphp
                <div id="items-list" class="space-y-3">
                    @foreach($items as $idx => $item)
                    <div class="item-row border border-gray-200 rounded-xl p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-500">Élément {{ $idx + 1 }}</p>
                            @if($idx > 0)<button type="button" onclick="this.closest('.item-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>@endif
                        </div>
                        <input type="text" name="items[{{ $idx }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Titre en gras (optionnel)" class="input-field text-sm">
                        <input type="text" name="items[{{ $idx }}][description]" value="{{ $item['description'] ?? '' }}" placeholder="Description / texte de la puce" class="input-field text-sm">
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addItem()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Ajouter un élément</button>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.content-blocks.sections', ['event_config_id' => $section->event_config_id]) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    let itemCount = {{ count($items) }};
    function addItem() {
        const idx = itemCount++;
        const div = document.createElement('div');
        div.className = 'item-row border border-gray-200 rounded-xl p-4 space-y-2';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500">Élément ${idx + 1}</p>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-xs text-red-500 hover:text-red-700">Supprimer</button>
            </div>
            <input type="text" name="items[${idx}][title]" placeholder="Titre en gras (optionnel)" class="input-field text-sm">
            <input type="text" name="items[${idx}][description]" placeholder="Description / texte de la puce" class="input-field text-sm">
        `;
        document.getElementById('items-list').appendChild(div);
    }
    function toggleType() {
        const isList = document.getElementById('type-select').value === 'list';
        document.getElementById('list-field').classList.toggle('hidden', !isList);
        document.getElementById('richtext-field').classList.toggle('hidden', isList);
    }
    toggleType();
</script>
@endsection
