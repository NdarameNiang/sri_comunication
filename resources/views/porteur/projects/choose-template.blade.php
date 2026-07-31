@extends('layouts.app')
@section('title', 'Choisir le format de soumission')
@section('page-title', 'Choisir le format de soumission')
@section('page-subtitle', $assignment->title)

@section('content')
@php
    $adminMode  = $adminMode ?? false;
    $createBase = $adminMode ? route('superadmin.assignments.fill', $assignment) : route('porteur.projects.create', $assignment);
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs mb-0.5">Structure</p>
                <p class="font-semibold text-gray-900">{{ $assignment->structure->name }} ({{ $assignment->structure->acronym }})</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs mb-0.5">Titre du projet</p>
                <p class="font-semibold text-gray-900">{{ $assignment->title }}</p>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-bold text-gray-900">Quel format de soumission souhaitez-vous utiliser ?</h2>
        <p class="text-sm text-gray-500 mt-1">Ce choix ne peut plus être modifié après le début de la saisie. Sélectionnez le format le plus adapté à votre projet.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <a href="{{ $createBase }}?template=standard" class="group block bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-400 hover:shadow-md transition-all p-6">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Format Standard</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-4">Pour la majorité des projets : résumé, problématique, solution, résultats attendus. Formulaire en 7 étapes.</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 group-hover:gap-2.5 transition-all">
                Choisir ce format
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </span>
        </a>

        <a href="{{ $createBase }}?template=approfondi" class="group block bg-white rounded-2xl border-2 border-gray-200 hover:border-indigo-400 hover:shadow-md transition-all p-6">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition-colors">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Format Approfondi</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-4">Pour les projets à fort potentiel de valorisation (prototype testé, brevet, start-up…). Dossier détaillé sur 8 étapes : identité, méthodologie, résultats, maturité TRL, impact, annexes.</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 group-hover:gap-2.5 transition-all">
                Choisir ce format
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </span>
        </a>
    </div>

    <a href="{{ $adminMode ? route('superadmin.projects.index') : route('porteur.dashboard') }}" class="btn-secondary text-sm inline-flex items-center gap-1.5">
        ← Retour
    </a>
</div>
@endsection
