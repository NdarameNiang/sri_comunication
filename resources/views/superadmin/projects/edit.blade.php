@extends('layouts.app')
@section('title', 'Modifier le projet – Admin')
@section('page-title', 'Modifier le projet')
@section('page-subtitle', $project->assignment?->title)

@section('content')
@if($project->isApprofondi())
    @include('porteur.projects._form_body_approfondi', ['adminMode' => true])
@else
    @include('porteur.projects._form_body', ['adminMode' => true])
@endif
@endsection
