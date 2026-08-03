@extends('layouts.public')
@section('title', ($project ? 'Modifier mon dossier' : 'Remplir mon dossier') . ' – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', $assignment->title)
@section('event-badge', 'Étape 3 · ' . ($project ? 'Mon dossier' : 'Formulaire de dépôt'))
@section('content-width', 'max-w-7xl')

@push('scripts')
{{-- Le formulaire est partagé avec le porteur connecté et l'admin (qui gardent le bleu UCAD
     fixe) — cette surcharge scoped n'applique la couleur de marque de l'événement que dans le
     contexte du dépôt public, sans toucher au partial partagé. --}}
<style>
    .public-brand-form .btn-primary,
    .public-brand-form .bg-blue-600 {
        background-color: var(--brand-primary) !important;
    }
    .public-brand-form .btn-primary:hover {
        filter: brightness(0.92);
    }
</style>
@endpush

@section('content')
@php
    $publicAction = route('public.project-submission.save', [$event->event_slug, $assignment, $token]);
    $publicSubmitAction = $project ? route('public.project-submission.submit', [$event->event_slug, $assignment, $token]) : null;
@endphp
<div class="public-brand-form">
@if($template === 'approfondi')
    @include('porteur.projects._form_body_approfondi', [
        'publicMode' => true,
        'publicAction' => $publicAction,
        'publicSubmitAction' => $publicSubmitAction,
    ])
@else
    @include('porteur.projects._form_body', [
        'publicMode' => true,
        'publicAction' => $publicAction,
        'publicSubmitAction' => $publicSubmitAction,
    ])
@endif
</div>
@endsection
