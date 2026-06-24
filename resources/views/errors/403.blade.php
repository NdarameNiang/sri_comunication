@extends('errors.layout')
@section('title', '403 – Accès refusé')
@section('content')
<div class="icon-wrap" style="background:#fef3c7;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
    </svg>
</div>
<p class="code">403</p>
<p class="title">Accès refusé</p>
<p class="message">{{ $exception->getMessage() ?: "Vous n'avez pas les droits nécessaires pour accéder à cette page." }}</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-secondary">← Retour</a>
    <a href="{{ url('/') }}" class="btn btn-primary">Accueil</a>
</div>
@endsection
