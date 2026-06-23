<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'événement <span class="text-red-500">*</span></label>
    <input type="text" name="event_name" value="{{ old('event_name', $eventConfig->event_name ?? '') }}" required class="input-field"
           placeholder="Ex: SRI 2026, MMA 2026…">
    @error('event_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (identifiant URL) <span class="text-red-500">*</span></label>
    <input type="text" name="event_slug" value="{{ old('event_slug', $eventConfig->event_slug ?? '') }}" required class="input-field font-mono"
           placeholder="Ex: sri-2026" pattern="[a-z0-9\-]+">
    <p class="text-xs text-gray-400 mt-1">Minuscules et tirets. Utilisé dans l'URL des formulaires publics.</p>
    @error('event_slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="event_description" rows="3" class="input-field resize-none">{{ old('event_description', $eventConfig->event_description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
    <input type="text" name="organizer" value="{{ old('organizer', $eventConfig->organizer ?? '') }}" class="input-field">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
        <input type="date" name="event_start_date" value="{{ old('event_start_date', isset($eventConfig) ? $eventConfig->event_start_date?->format('Y-m-d') : '') }}" class="input-field">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
        <input type="date" name="event_end_date" value="{{ old('event_end_date', isset($eventConfig) ? $eventConfig->event_end_date?->format('Y-m-d') : '') }}" class="input-field">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ouverture soumissions</label>
        <input type="datetime-local" name="submission_open_at"
               value="{{ old('submission_open_at', isset($eventConfig) ? $eventConfig->submission_open_at?->format('Y-m-d\TH:i') : '') }}"
               class="input-field">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Clôture soumissions</label>
        <input type="datetime-local" name="submission_close_at"
               value="{{ old('submission_close_at', isset($eventConfig) ? $eventConfig->submission_close_at?->format('Y-m-d\TH:i') : '') }}"
               class="input-field">
    </div>
</div>
