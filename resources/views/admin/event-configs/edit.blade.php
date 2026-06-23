@extends('layouts.app')
@section('title', 'Modifier – ' . $eventConfig->event_name)
@section('page-title', 'Modifier l\'événement')
@section('page-subtitle', $eventConfig->event_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="section-title text-base">{{ $eventConfig->event_name }}</h3>
                @if($eventConfig->is_active)
                <span class="badge-green mt-1 inline-block">Événement actif</span>
                @endif
            </div>
            <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.event-configs.update', $eventConfig) }}" class="space-y-6">
                @csrf @method('PUT')
                @include('admin.event-configs._form')
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
