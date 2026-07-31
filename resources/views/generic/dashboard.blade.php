@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Votre espace')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-600">
            Bienvenue, {{ auth()->user()->name }}. Voici les fonctionnalités accessibles avec votre rôle
            (<strong>{{ \App\Models\User::roleLabel(auth()->user()->role) }}</strong>).
        </p>
    </div>

    @if(count($navItems))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-sm transition-all flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                @include('components.icon', ['name' => $item['icon']])
            </div>
            <span class="font-semibold text-gray-800 text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-400 text-sm">
        Aucune fonctionnalité n'est encore associée aux capacités de ce rôle.
    </div>
    @endif
</div>
@endsection
