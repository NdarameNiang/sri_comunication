@extends('layouts.public')
@section('title', ($project ? 'Modifier mon dossier' : 'Remplir mon dossier') . ' – ' . $event->event_name)
@section('event-name', $event->event_name)
@section('event-subtitle', $assignment->title)
@section('event-badge', 'Étape 3 · ' . ($project ? 'Mon dossier' : 'Formulaire de dépôt'))

@section('content')
@php
    $publicAction = route('public.project-submission.save', [$event->event_slug, $assignment, $token]);
    $publicSubmitAction = $project ? route('public.project-submission.submit', [$event->event_slug, $assignment, $token]) : null;
@endphp
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
@endsection
