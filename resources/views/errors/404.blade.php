@extends('errors.layout')
@section('title', '404 – Page introuvable')
@section('content')
<div class="icon-wrap" style="background:#eff6ff;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/>
    </svg>
</div>
<p class="code">404</p>
<p class="title">Page introuvable</p>
<p class="message">La page que vous cherchez n'existe pas ou a été déplacée.<br>Vérifiez l'URL ou retournez à l'accueil.</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-secondary">← Retour</a>
    <a href="{{ url('/') }}" class="btn btn-primary">Accueil</a>
</div>
@endsection
