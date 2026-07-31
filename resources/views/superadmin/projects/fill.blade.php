@extends('layouts.app')
@section('title', 'Remplir le projet – Admin')
@section('page-title', 'Remplir le projet pour le porteur')
@section('page-subtitle', $assignment->title)

@section('content')
@php $template = $template ?? 'standard'; @endphp
@if($template === 'approfondi')
    @include('porteur.projects._form_body_approfondi', [
        'adminMode'   => true,
        'adminAction' => route('superadmin.assignments.store-fill', $assignment),
        'adminMethod' => 'POST',
    ])
@else
    @include('porteur.projects._form_body', [
        'adminMode'   => true,
        'adminAction' => route('superadmin.assignments.store-fill', $assignment),
        'adminMethod' => 'POST',
    ])
@endif
@endsection
