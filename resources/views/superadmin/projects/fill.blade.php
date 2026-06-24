@extends('layouts.app')
@section('title', 'Remplir le projet – Admin')
@section('page-title', 'Remplir le projet pour le porteur')
@section('page-subtitle', $assignment->title)

@section('content')
@include('porteur.projects._form_body', [
    'adminMode'   => true,
    'adminAction' => route('superadmin.assignments.store-fill', $assignment),
    'adminMethod' => 'POST',
])
@endsection
