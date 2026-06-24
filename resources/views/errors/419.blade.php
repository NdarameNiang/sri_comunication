@extends('errors.layout')
@section('title', '419 – Session expirée')
@section('content')
<div class="icon-wrap" style="background:#fef3c7;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
</div>
<p class="code">419</p>
<p class="title">Session expirée</p>
<p class="message">Votre session a expiré ou le formulaire a été soumis trop tard.<br>Rechargez la page et réessayez.</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-primary" onclick="window.location.reload();return false;">↺ Recharger la page</a>
</div>
@endsection
