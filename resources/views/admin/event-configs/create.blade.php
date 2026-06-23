@extends('layouts.app')
@section('title', 'Nouvel événement')
@section('page-title', 'Créer un événement')
@section('page-subtitle', 'Configurer un nouvel événement SRI / MMA / autre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Nouvel événement</h3>
            <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.event-configs.store') }}" class="space-y-6">
                @csrf
                @include('admin.event-configs._form')
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Créer l'événement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
