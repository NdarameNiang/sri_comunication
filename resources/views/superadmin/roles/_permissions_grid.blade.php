{{-- Grille de permissions groupées par catégorie --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <label class="form-label mb-0">Permissions</label>
        <div class="flex gap-2 text-xs">
            <button type="button" onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(c=>c.checked=true)" class="text-blue-600 hover:underline">Tout cocher</button>
            <span class="text-gray-300">|</span>
            <button type="button" onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(c=>c.checked=false)" class="text-gray-400 hover:underline">Tout décocher</button>
        </div>
    </div>

    @foreach($permissions as $group => $perms)
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-4 py-2.5 flex items-center justify-between">
            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $group }}</h4>
            <button type="button" class="text-xs text-blue-500 hover:underline"
                    onclick="this.closest('.border').querySelectorAll('input[type=checkbox]').forEach(c=>c.checked=!c.checked)">
                Inverser
            </button>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($perms as $perm)
            @php $checked = in_array($perm->name, (array)$selectedPermissions); @endphp
            <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50/70 cursor-pointer transition-colors">
                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                       {{ $checked ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-blue-600">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $perm->label ?: $perm->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $perm->name }}</p>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
