@extends('layouts.app')
@section('title', 'Étudiants (StudentCenter)')
@section('page-title', 'Base étudiants (StudentCenter)')
@section('page-subtitle', 'Utilisée pour vérifier la catégorie des inscrits "Étudiant"')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 mb-1">Étudiants en base locale</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total'], 0, ',', ' ') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 mb-1">Dernière synchronisation</p>
            <p class="text-lg font-semibold text-gray-900">
                {{ $stats['last_sync'] ? \Illuminate\Support\Carbon::parse($stats['last_sync'])->format('d/m/Y à H:i') : 'Jamais' }}
            </p>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
        <p class="text-sm text-blue-900 font-medium mb-1">Synchronisation automatique quotidienne à 2h</p>
        <p class="text-xs text-blue-700 mb-4">
            La base StudentCenter compte environ 156 000 étudiants — une synchronisation complète prend
            plusieurs dizaines de minutes. Le déclenchement manuel s'exécute en tâche de fond.
        </p>
        <form method="POST" action="{{ route('admin.students.sync') }}"
              data-confirm="Lancer une synchronisation complète maintenant ?" data-confirm-type="warning">
            @csrf
            <button type="submit" class="btn-primary text-sm">Lancer la synchronisation maintenant</button>
        </form>
    </div>
</div>
@endsection
