@extends('layouts.app')
@section('title', 'Sections — ' . $event->event_name)
@section('page-title', 'Sections de la page publique')
@section('page-subtitle', $event->event_name)

@section('content')
<div class="space-y-4">

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-end justify-between gap-3">
        <div class="flex-1 min-w-48">
            <label class="block text-xs text-gray-500 mb-1">Événement</label>
            <form method="GET" action="{{ route('admin.content-blocks.sections') }}">
                <select name="event_config_id" onchange="this.form.submit()" class="input-field text-sm">
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}" {{ $ev->id === $event->id ? 'selected' : '' }}>{{ $ev->event_name }}{{ $ev->is_active ? ' (actif)' : '' }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.content-blocks.index') }}" class="btn-secondary text-sm">Champs structurels</a>
            <a href="{{ route('admin.content-blocks.sections.create', ['event_config_id' => $event->id]) }}" class="btn-primary text-sm">+ Ajouter une section</a>
        </div>
    </div>

    <p class="text-xs text-gray-400">Glissez-déposez pour réordonner. Chaque section apparaît sur la page publique de cet événement uniquement.</p>

    <div id="sections-list" class="space-y-2" data-event-id="{{ $event->id }}">
        @forelse($sections as $section)
        <div class="section-row bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3 {{ !$section->is_active ? 'opacity-50' : '' }}" data-id="{{ $section->id }}">
            <span class="cursor-move text-gray-300 hover:text-gray-500 shrink-0" title="Glisser pour réordonner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/></svg>
            </span>
            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">{{ $loop->iteration }}</span>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm truncate">{{ $section->title }}</p>
                <p class="text-xs text-gray-400">{{ $section->type === 'list' ? count($section->content_json ?? []) . ' élément(s)' : 'Texte libre' }}</p>
            </div>
            <form method="POST" action="{{ route('admin.content-blocks.sections.toggle', $section) }}">
                @csrf @method('PATCH')
                <button type="submit" class="text-xs px-2 py-1 rounded-full font-medium
                    {{ $section->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                    {{ $section->is_active ? 'Visible' : 'Masquée' }}
                </button>
            </form>
            <a href="{{ route('admin.content-blocks.sections.edit', $section) }}" class="text-blue-600 text-xs hover:text-blue-800 shrink-0">Modifier</a>
            <form method="POST" action="{{ route('admin.content-blocks.sections.destroy', $section) }}"
                  data-confirm="Supprimer cette section ?" data-confirm-type="danger" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-red-500 text-xs hover:text-red-700">Supprimer</button>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400 text-sm">
            Aucune section pour cet événement. La page publique n'affiche pas de zone "détails de l'appel".
        </div>
        @endforelse
    </div>
</div>

<script>
(function () {
    const list = document.getElementById('sections-list');
    if (!list) return;
    let dragged = null;

    list.querySelectorAll('.section-row').forEach(row => {
        row.draggable = true;
        row.addEventListener('dragstart', () => { dragged = row; row.classList.add('opacity-40'); });
        row.addEventListener('dragend', () => { row.classList.remove('opacity-40'); save(); });
        row.addEventListener('dragover', (e) => {
            e.preventDefault();
            const after = getRowAfter(e.clientY);
            if (!after) list.appendChild(dragged);
            else list.insertBefore(dragged, after);
        });
    });

    function getRowAfter(y) {
        const rows = [...list.querySelectorAll('.section-row:not(.opacity-40)')];
        return rows.reduce((closest, row) => {
            const box = row.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) return { offset, element: row };
            return closest;
        }, { offset: -Infinity, element: null }).element;
    }

    function save() {
        const ids = [...list.querySelectorAll('.section-row')].map(r => r.dataset.id);
        fetch('{{ route('admin.content-blocks.sections.reorder') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ event_config_id: list.dataset.eventId, ids }),
        });
    }
})();
</script>
@endsection
