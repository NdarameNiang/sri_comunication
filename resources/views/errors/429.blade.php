@extends('errors.layout')
@section('title', '429 – Trop de requêtes')
@section('content')
<div class="icon-wrap" style="background:#fef3c7;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
    </svg>
</div>
<p class="code">429</p>
<p class="title">Trop de requêtes</p>
<p class="message">Vous avez effectué trop d'actions en peu de temps.<br>Veuillez patienter quelques instants avant de réessayer.</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-secondary">← Retour</a>
</div>
@endsection
