@extends('layouts.app')
@section('title', 'Synchronisation')
@section('page-title', 'Synchronisation des bases externes')
@section('page-subtitle', 'Utilisées pour vérifier l\'identité des inscrits (étudiants et personnel PER/PATS)')

@section('content')
<div class="space-y-6">

    {{-- ── StudentCenter ── --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700">Étudiants — StudentCenter</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Étudiants en base locale</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($studentStats['total'], 0, ',', ' ') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Dernière synchronisation</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $studentStats['last_sync'] ? \Illuminate\Support\Carbon::parse($studentStats['last_sync'])->format('d/m/Y à H:i') : 'Jamais' }}
                </p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <p class="text-sm text-blue-900 font-medium mb-1">Synchronisation StudentCenter</p>
            <p class="text-xs text-blue-700 mb-4">
                La base StudentCenter compte environ 156 000 étudiants — une synchronisation complète prend
                plusieurs dizaines de minutes. Le déclenchement manuel s'exécute en tâche de fond.
            </p>
            <form method="POST" action="{{ route('admin.students.sync') }}"
                  data-confirm="Lancer une synchronisation complète des étudiants maintenant ?" data-confirm-type="warning">
                @csrf
                <button type="submit" class="btn-primary text-sm">Lancer la synchronisation maintenant</button>
            </form>
        </div>
    </div>

    {{-- ── Personnel PER/PATS ── --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700">Personnel — PER / PATS</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Personnel en base locale</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($personnelStats['total'], 0, ',', ' ') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Dernière synchronisation</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $personnelStats['last_sync'] ? \Illuminate\Support\Carbon::parse($personnelStats['last_sync'])->format('d/m/Y à H:i') : 'Jamais' }}
                </p>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
            <p class="text-sm text-indigo-900 font-medium mb-1">Synchronisation Personnel</p>
            <p class="text-xs text-indigo-700 mb-4">
                Vérifie l'identité des inscrits déclarés « Personnel (PER/PATS) » lors de l'inscription ou du dépôt public.
            </p>
            <form method="POST" action="{{ route('admin.students.sync-personnel') }}"
                  data-confirm="Lancer une synchronisation complète du personnel maintenant ?" data-confirm-type="warning">
                @csrf
                <button type="submit" class="btn-primary text-sm">Lancer la synchronisation maintenant</button>
            </form>
        </div>
    </div>

</div>
@endsection
