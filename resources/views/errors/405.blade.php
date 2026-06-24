@extends('errors.layout')
@section('title', '405 – Méthode non autorisée')
@section('content')
<div class="icon-wrap" style="background:#fef3c7;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    </svg>
</div>
<p class="code">405</p>
<p class="title">Méthode non autorisée</p>
<p class="message">Cette action n'est pas permise sur cette URL.<br>Essayez de recharger la page ou de naviguer autrement.</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-secondary">← Retour</a>
    <a href="{{ url('/') }}" class="btn btn-primary">Accueil</a>
</div>
@endsection
