@extends('layouts.app')
@section('title', $project ? 'Modifier le projet' : 'Remplir le formulaire')
@section('page-title', $project ? 'Modifier le projet' : 'Formulaire de collecte – ' . (\App\Models\EventConfig::active()?->event_name ?? 'SRI 2026'))
@section('page-subtitle', $assignment->title)

@section('content')
@include('porteur.projects._form_body')
@endsection
