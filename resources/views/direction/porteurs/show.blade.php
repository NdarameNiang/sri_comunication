@extends('layouts.app')
@section('title', $porteur->name)
@section('page-title', $porteur->name)
@section('page-subtitle', 'Détails du porteur de projet')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex gap-3">
        <a href="{{ route('direction.porteurs.index') }}" class="btn-secondary text-sm">
            @include('components.icon', ['name' => 'arrow-left'])
            Retour
        </a>
        <a href="{{ route('direction.porteurs.edit', $porteur) }}" class="btn-primary text-sm">
            @include('components.icon', ['name' => 'pencil'])
            Modifier
        </a>
    </div>

    {{-- Infos porteur --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations personnelles</h3>
            <span class="{{ $porteur->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $porteur->is_active ? 'Compte actif' : 'Compte inactif' }}
            </span>
        </div>
        <div class="card-body grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-500 mb-1">Nom complet</p>
                <p class="font-semibold text-gray-900">{{ $porteur->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="font-semibold text-gray-900">{{ $porteur->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                <p class="font-semibold text-gray-900">{{ $porteur->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Structure</p>
                @if($porteur->structure)
                    <span class="badge-blue">{{ $porteur->structure->acronym }}</span>
                    <p class="text-sm text-gray-700 mt-1">{{ $porteur->structure->name }}</p>
                @else
                    <p class="text-gray-400">—</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Projets assignés --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Projets assignés</h3>
            <span class="badge-gray">{{ $porteur->projectAssignments->count() }} projet(s)</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($porteur->projectAssignments as $assignment)
            <div class="px-6 py-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full {{ $assignment->status === 'submitted' ? 'bg-emerald-100' : 'bg-amber-100' }} flex items-center justify-center shrink-0 mt-0.5">
                        <span class="text-xs font-bold {{ $assignment->status === 'submitted' ? 'text-emerald-700' : 'text-amber-700' }}">P{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ $assignment->title }}</p>
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="{{ $assignment->status === 'submitted' ? 'badge-green' : 'badge-yellow' }}">
                                {{ $assignment->status === 'submitted' ? 'Soumis' : 'En attente' }}
                            </span>
                            @if($assignment->project)
                                <span class="{{ $assignment->project->status === 'submitted' ? 'badge-green' : 'badge-yellow' }}">
                                    Formulaire : {{ $assignment->project->status === 'submitted' ? 'soumis' : 'brouillon' }}
                                </span>
                            @else
                                <span class="badge-gray">Formulaire non rempli</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">Aucun projet assigné</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
