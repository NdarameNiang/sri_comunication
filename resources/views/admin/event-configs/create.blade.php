@extends('layouts.app')
@section('title', 'Nouvel événement')
@section('page-title', 'Créer un événement')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.event-configs.store') }}" class="space-y-5">
            @csrf
            @include('admin.event-configs._form')
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Créer l'événement</button>
                <a href="{{ route('admin.event-configs.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
