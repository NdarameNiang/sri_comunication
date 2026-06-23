{{-- Informations générales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="form-label">Nom de l'événement <span class="text-red-500">*</span></label>
        <input type="text" name="event_name" value="{{ old('event_name', $eventConfig->event_name ?? '') }}"
               class="form-input @error('event_name') border-red-400 @enderror"
               placeholder="Ex: SRI 2026, MMA 2026…" required>
        @error('event_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Slug (identifiant URL) <span class="text-red-500">*</span></label>
        <input type="text" name="event_slug" value="{{ old('event_slug', $eventConfig->event_slug ?? '') }}"
               class="form-input font-mono @error('event_slug') border-red-400 @enderror"
               placeholder="sri-2026" pattern="[a-z0-9\-]+" required>
        <p class="text-xs text-gray-400 mt-1">Minuscules et tirets · utilisé dans les URLs publiques</p>
        @error('event_slug') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Organisateur</label>
        <input type="text" name="organizer" value="{{ old('organizer', $eventConfig->organizer ?? '') }}"
               class="form-input" placeholder="Ex : Direction de la Recherche – UCAD">
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Description</label>
        <textarea name="event_description" rows="3"
                  class="form-input resize-none"
                  placeholder="Brève description de l'événement…">{{ old('event_description', $eventConfig->event_description ?? '') }}</textarea>
    </div>
</div>

{{-- Dates de l'événement --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Dates de l'événement</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Date de début</label>
            <input type="date" name="event_start_date"
                   value="{{ old('event_start_date', isset($eventConfig) ? $eventConfig->event_start_date?->format('Y-m-d') : '') }}"
                   class="form-input">
        </div>
        <div>
            <label class="form-label">Date de fin</label>
            <input type="date" name="event_end_date"
                   value="{{ old('event_end_date', isset($eventConfig) ? $eventConfig->event_end_date?->format('Y-m-d') : '') }}"
                   class="form-input">
        </div>
    </div>
</div>

{{-- Période de soumission --}}
<div>
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Période de soumission des projets</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Ouverture</label>
            <input type="datetime-local" name="submission_open_at"
                   value="{{ old('submission_open_at', isset($eventConfig) ? $eventConfig->submission_open_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Les porteurs pourront soumettre à partir de cette date</p>
        </div>
        <div>
            <label class="form-label">Clôture</label>
            <input type="datetime-local" name="submission_close_at"
                   value="{{ old('submission_close_at', isset($eventConfig) ? $eventConfig->submission_close_at?->format('Y-m-d\TH:i') : '') }}"
                   class="form-input">
            <p class="text-xs text-gray-400 mt-1">Plus aucune soumission après cette date</p>
        </div>
    </div>
</div>
