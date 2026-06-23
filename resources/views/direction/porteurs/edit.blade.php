@extends('layouts.app')
@section('title', 'Modifier ' . $porteur->name)
@section('page-title', 'Modifier le porteur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="section-title text-base">{{ $porteur->name }}</h3>
                <p class="text-xs text-gray-500">{{ $porteur->structure?->name }}</p>
            </div>
            <a href="{{ route('direction.porteurs.index') }}" class="btn-secondary text-xs">
                @include('components.icon', ['name' => 'arrow-left'])
                Retour
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('direction.porteurs.update', $porteur) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $porteur->name) }}" class="form-input" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Téléphone <span class="text-xs text-gray-400">(7X XXX XX XX)</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $porteur->phone) }}" class="form-input @error('phone') border-red-400 @enderror" placeholder="77 000 00 00" maxlength="9">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email institutionnel <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-400 font-normal">(connexion)</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $porteur->email) }}" class="form-input @error('email') border-red-400 @enderror" required>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email personnel
                            <span class="text-xs text-gray-400 font-normal">(optionnel)</span>
                        </label>
                        <input type="email" name="email_personnel" value="{{ old('email_personnel', $porteur->email_personnel) }}" class="form-input @error('email_personnel') border-red-400 @enderror" placeholder="gmail, yahoo...">
                        @error('email_personnel') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Structure <span class="text-red-500">*</span></label>
                    <select name="structure_id" class="form-select" required>
                        <option value="">-- Sélectionner une structure --</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}"
                                {{ old('structure_id', $porteur->structure_id) == $structure->id ? 'selected' : '' }}>
                                {{ $structure->acronym }} – {{ $structure->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('structure_id') <p class="form-error">{{ $message }}</p> @enderror
                    @if($porteur->projectAssignments->count() > 0)
                    <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Changer la structure déplacera les {{ $porteur->projectAssignments->count() }} projet(s) assigné(s) vers la nouvelle structure.
                    </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nouveau mot de passe <span class="text-gray-400 text-xs">(optionnel)</span></label>
                        <input type="password" name="password" class="form-input @error('password') border-red-400 @enderror" placeholder="Min. 8 caractères">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Confirmer le mot de passe <span class="text-gray-400 text-xs">(si changement)</span></label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Retapez le nouveau mot de passe">
                    </div>
                </div>

                <div class="flex items-center pb-1">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $porteur->is_active ? 'checked' : '' }} class="w-4 h-4 rounded">
                        <span>Compte actif</span>
                    </label>
                </div>

                {{-- Projets assignés (lecture seule) --}}
                <div>
                    <label class="form-label">Projets assignés</label>
                    <div class="space-y-2">
                        @forelse($porteur->projectAssignments as $assignment)
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <span class="{{ $assignment->status === 'submitted' ? 'badge-green' : 'badge-yellow' }}">
                                {{ $assignment->status === 'submitted' ? 'Soumis' : 'En attente' }}
                            </span>
                            <span class="text-sm text-gray-700">{{ $assignment->title }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">Aucun projet assigné</p>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Les titres de projets sont définis à la création et ne peuvent être modifiés ici.</p>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                    <a href="{{ route('direction.porteurs.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
