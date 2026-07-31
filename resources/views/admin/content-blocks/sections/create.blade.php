@extends('layouts.app')
@section('title', 'Nouvelle section')
@section('page-title', 'Nouvelle section')
@section('page-subtitle', $event->event_name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.content-blocks.sections.store') }}" class="space-y-5" id="section-form">
            @csrf
            <input type="hidden" name="event_config_id" value="{{ $event->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la section <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="input-field" placeholder="Ex : Critères de sélection">
                @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de contenu <span class="text-red-500">*</span></label>
                <select name="type" id="type-select" class="input-field" onchange="toggleType()">
                    <option value="list" {{ old('type') === 'list' ? 'selected' : '' }}>Liste à puces</option>
                    <option value="richtext" {{ old('type') === 'richtext' ? 'selected' : '' }}>Paragraphe de texte</option>
                </select>
            </div>

            <div id="richtext-field" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Texte</label>
                <textarea name="content" rows="6" class="input-field">{{ old('content') }}</textarea>
            </div>

            <div id="list-field">
                <label class="block text-sm font-medium text-gray-700 mb-2">Éléments de la liste</label>
                <div id="items-list" class="space-y-3">
                    <div class="item-row border border-gray-200 rounded-xl p-4 space-y-2">
                        <p class="text-xs font-medium text-gray-500">Élément 1</p>
                        <input type="text" name="items[0][title]" placeholder="Titre en gras (optionnel)" class="input-field text-sm">
                        <input type="text" name="items[0][description]" placeholder="Description / texte de la puce" class="input-field text-sm">
                    </div>
                </div>
                <button type="button" onclick="addItem()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">+ Ajouter un élément</button>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Ajouter</button>
                <a href="{{ route('admin.content-blocks.sections', ['event_config_id' => $event->id]) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    let itemCount = 1;
    function addItem() {
        const idx = itemCount++;
        const div = document.createElement('div');
        div.className = 'item-row border border-gray-200 rounded-xl p-4 space-y-2';
        div.innerHTML = `
            <p class="text-xs font-medium text-gray-500">Élément ${idx + 1}</p>
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
