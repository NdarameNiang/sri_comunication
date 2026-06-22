@extends('layouts.app')
@section('title', 'Changer mon mot de passe')
@section('page-title', 'Sécurité du compte')
@section('page-subtitle', 'Modifier votre mot de passe de connexion')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <h3 class="section-title text-base">Changer le mot de passe</h3>
            </div>
        </div>

        <div class="card-body">

            {{-- Info utilisateur --}}
            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 mb-6">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                    <span class="text-blue-700 font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Mot de passe actuel --}}
                <div>
                    <label class="form-label">Mot de passe actuel</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="current_password"
                               placeholder="Votre mot de passe actuel"
                               class="form-input pl-10 @error('current_password') border-red-400 bg-red-50 @enderror"
                               autocomplete="current-password">
                    </div>
                    @error('current_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <p class="text-xs text-gray-400 mb-4 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Le nouveau mot de passe doit contenir au moins 8 caractères.
                    </p>

                    {{-- Nouveau mot de passe --}}
                    <div class="mb-4">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                   placeholder="Nouveau mot de passe"
                                   class="form-input pl-10 @error('password') border-red-400 bg-red-50 @enderror"
                                   autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                        </div>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror

                        {{-- Barre de force --}}
                        <div class="mt-2 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                            <div id="strength-bar" class="h-full rounded-full transition-all duration-300 w-0 bg-gray-300"></div>
                        </div>
                        <p id="strength-label" class="text-xs mt-1 text-gray-400"></p>
                    </div>

                    {{-- Confirmation --}}
                    <div>
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation"
                                   placeholder="Retapez le nouveau mot de passe"
                                   class="form-input pl-10"
                                   autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ url()->previous() }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function checkStrength(val) {
    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    if (!val) { bar.style.width = '0'; label.textContent = ''; return; }

    let score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '20%',  color: 'bg-red-400',    text: 'Très faible', cls: 'text-red-500'    },
        { w: '40%',  color: 'bg-orange-400',  text: 'Faible',      cls: 'text-orange-500' },
        { w: '60%',  color: 'bg-yellow-400',  text: 'Moyen',       cls: 'text-yellow-600' },
        { w: '80%',  color: 'bg-blue-400',    text: 'Fort',        cls: 'text-blue-600'   },
        { w: '100%', color: 'bg-emerald-500', text: 'Très fort',   cls: 'text-emerald-600'},
    ];
    const lvl = levels[Math.min(score - 1, 4)] ?? levels[0];

    bar.className = `h-full rounded-full transition-all duration-300 ${lvl.color}`;
    bar.style.width = lvl.w;
    label.className = `text-xs mt-1 ${lvl.cls}`;
    label.textContent = lvl.text;
}
</script>
@endsection
