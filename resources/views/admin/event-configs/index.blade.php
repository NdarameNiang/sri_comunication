@extends('layouts.app')
@section('title', 'Événements')
@section('page-title', 'Gestion des événements')
@section('page-subtitle', 'Configurer les éditions (SRI 2026, MMA 2026, etc.)')

@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('admin.event-configs.create') }}" class="btn-primary text-sm">+ Nouvel événement</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Nom</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Slug</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Soumissions</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Statut</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($configs as $config)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold text-gray-900">
                            {{ $config->event_name }}
                            @if($config->is_active)
                            <span class="ml-2 text-xs bg-green-100 text-green-700 rounded-full px-2 py-0.5 font-normal">Actif</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-xs text-gray-500">{{ $config->event_slug }}</td>
                        <td class="py-3 px-4 text-xs text-gray-600">
                            @if($config->submission_open_at)
                            {{ $config->submission_open_at->format('d/m/Y') }} →
                            {{ $config->submission_close_at?->format('d/m/Y') ?? '–' }}
                            @else
                            <span class="text-gray-400">Non configuré</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if(!$config->is_active)
                            <form method="POST" action="{{ route('admin.event-configs.activate', $config) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Activer</button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400">Événement courant</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.event-configs.edit', $config) }}" class="text-blue-600 text-xs">Modifier</a>
                                @if(!$config->is_active)
                                <form method="POST" action="{{ route('admin.event-configs.destroy', $config) }}"
                                      data-confirm="Supprimer cet événement ?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs">Supprimer</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-400 text-sm">Aucun événement configuré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($configs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $configs->links() }}</div>
        @endif
    </div>
</div>
@endsection
