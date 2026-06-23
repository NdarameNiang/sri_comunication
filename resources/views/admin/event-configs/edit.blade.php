@extends('layouts.app')
@section('title', 'Modifier – ' . $eventConfig->event_name)
@section('page-title', 'Modifier l\'événement')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.event-configs.update', $eventConfig) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('admin.event-configs._form')
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
