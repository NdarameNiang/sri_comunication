@extends('errors.layout')
@section('title', '500 – Erreur serveur')
@section('content')
<div class="icon-wrap" style="background:#fee2e2;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
    </svg>
</div>
<p class="code" style="background:linear-gradient(135deg,#dc2626 0%,#9f1239 100%);-webkit-background-clip:text;background-clip:text;">500</p>
<p class="title">Erreur interne du serveur</p>
<p class="message">Une erreur inattendue s'est produite de notre côté.<br>Nos équipes en sont informées. Réessayez dans quelques instants.</p>
<div class="divider"></div>
<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn btn-secondary">← Retour</a>
    <a href="{{ url('/') }}" class="btn btn-primary">Accueil</a>
</div>
@endsection
