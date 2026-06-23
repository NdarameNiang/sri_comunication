@extends('layouts.app')
@section('title', 'Options de formulaire')
@section('page-title', 'Options de formulaire')
@section('page-subtitle', 'Gérer les choix des menus déroulants')

@section('content')
<div class="space-y-4">

    {{-- Sélecteur de groupe --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Groupe</label>
                <select name="group" onchange="this.form.submit()" class="input-field text-sm">
                    @foreach($groups as $key => $label)
                        <option value="{{ $key }}" {{ $group === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.form-options.create') }}" class="btn-primary text-sm">+ Ajouter une option</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-700">{{ $groups[$group] ?? $group }} – {{ $options->count() }} option(s)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Libellé</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Valeur</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Ordre</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Actif</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($options as $opt)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 {{ !$opt->is_active ? 'opacity-50' : '' }}">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $opt->label }}</td>
                        <td class="py-3 px-4 font-mono text-xs text-gray-500">{{ $opt->value }}</td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $opt->sort_order }}</td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.form-options.toggle', $opt) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-2 py-1 rounded-full font-medium
                                    {{ $opt->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    {{ $opt->is_active ? 'Oui' : 'Non' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.form-options.edit', $opt) }}" class="text-blue-600 text-xs hover:text-blue-800">Modifier</a>
                                <form method="POST" action="{{ route('admin.form-options.destroy', $opt) }}"
                                      data-confirm="Supprimer cette option ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs hover:text-red-700">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-400 text-sm">Aucune option dans ce groupe.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
