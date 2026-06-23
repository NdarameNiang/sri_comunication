{{-- Grille de permissions groupées, libellés lisibles --}}
<div class="space-y-3">
    <div class="flex items-center justify-between">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Permissions du rôle</p>
        <div class="flex gap-3 text-xs">
            <button type="button"
                    onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(c=>c.checked=true)"
                    class="text-blue-600 hover:text-blue-800 font-medium">
                Tout accorder
            </button>
            <span class="text-gray-200">|</span>
            <button type="button"
                    onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(c=>c.checked=false)"
                    class="text-gray-400 hover:text-gray-600 font-medium">
                Tout retirer
            </button>
        </div>
    </div>

    @foreach($permissions as $group => $perms)
    <div class="rounded-xl border border-gray-200 overflow-hidden">
        {{-- En-tête groupe --}}
        <div class="flex items-center justify-between bg-gray-50 px-4 py-2.5 border-b border-gray-200">
            <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $group }}</span>
            <button type="button"
                    class="text-xs text-gray-400 hover:text-gray-600"
                    onclick="
                        var boxes = this.closest('.rounded-xl').querySelectorAll('input[type=checkbox]');
                        var allChecked = Array.from(boxes).every(b => b.checked);
                        boxes.forEach(b => b.checked = !allChecked);
                    ">
                Inverser
            </button>
        </div>

        {{-- Permissions du groupe --}}
        <div class="divide-y divide-gray-100">
            @foreach($perms as $perm)
            @php $checked = in_array($perm->name, (array)$selectedPermissions); @endphp
            <label class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors group">
                <div class="relative flex-shrink-0">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                           {{ $checked ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                </div>
                <span class="text-sm text-gray-800 group-hover:text-gray-900 font-medium leading-snug select-none">
                    {{ $perm->label ?: $perm->name }}
                </span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
