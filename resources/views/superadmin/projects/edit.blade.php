@extends('layouts.app')
@section('title', 'Modifier le projet – Admin')
@section('page-title', 'Modifier le projet')
@section('page-subtitle', $project->assignment?->title)

@section('content')
@include('porteur.projects._form_body', ['adminMode' => true])
@endsection
