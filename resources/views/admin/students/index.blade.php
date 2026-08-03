@extends('layouts.app')
@section('title', 'Synchronisation')
@section('page-title', 'Synchronisation des bases externes')
@section('page-subtitle', 'Utilisées pour vérifier l\'identité des inscrits (étudiants et personnel PER/PATS)')

@section('content')
<div class="space-y-6">

    {{-- ── StudentCenter ── --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700">Étudiants — StudentCenter</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Étudiants en base locale</p>
                <p class="text-2xl font-extrabold text-gray-900" id="students-total">{{ number_format($studentStats['total'], 0, ',', ' ') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Dernière synchronisation</p>
                <p class="text-lg font-semibold text-gray-900" id="students-last-sync">
                    {{ $studentStats['last_sync'] ? \Illuminate\Support\Carbon::parse($studentStats['last_sync'])->format('d/m/Y à H:i') : 'Jamais' }}
                </p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <p class="text-sm text-blue-900 font-medium mb-1">Synchronisation StudentCenter</p>
            <p class="text-xs text-blue-700 mb-4">
                La base StudentCenter compte environ 156 000 étudiants — une synchronisation complète prend
                plusieurs dizaines de minutes.
            </p>
            <form method="POST" action="{{ route('admin.students.sync') }}" id="form-sync-students"
                  data-confirm="Lancer une synchronisation complète des étudiants maintenant ?" data-confirm-type="warning">
                @csrf
                <button type="submit" class="btn-primary text-sm" id="btn-sync-students">Lancer la synchronisation maintenant</button>
            </form>

            <div id="progress-students" class="mt-4 hidden">
                <div class="flex items-center justify-between text-xs text-blue-800 mb-1.5">
                    <span id="progress-students-label">En cours…</span>
                    <span id="progress-students-count"></span>
                </div>
                <div class="w-full h-2 bg-blue-100 rounded-full overflow-hidden">
                    <div id="progress-students-bar" class="h-full bg-blue-600 rounded-full transition-all duration-500" style="width:0%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Personnel PER/PATS ── --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-gray-700">Personnel — PER / PATS</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Personnel en base locale</p>
                <p class="text-2xl font-extrabold text-gray-900" id="personnel-total">{{ number_format($personnelStats['total'], 0, ',', ' ') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 mb-1">Dernière synchronisation</p>
                <p class="text-lg font-semibold text-gray-900" id="personnel-last-sync">
                    {{ $personnelStats['last_sync'] ? \Illuminate\Support\Carbon::parse($personnelStats['last_sync'])->format('d/m/Y à H:i') : 'Jamais' }}
                </p>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
            <p class="text-sm text-indigo-900 font-medium mb-1">Synchronisation Personnel</p>
            <p class="text-xs text-indigo-700 mb-4">
                Vérifie l'identité des inscrits déclarés « Personnel (PER/PATS) » lors de l'inscription ou du dépôt public.
            </p>
            <form method="POST" action="{{ route('admin.students.sync-personnel') }}" id="form-sync-personnel"
                  data-confirm="Lancer une synchronisation complète du personnel maintenant ?" data-confirm-type="warning">
                @csrf
                <button type="submit" class="btn-primary text-sm" id="btn-sync-personnel">Lancer la synchronisation maintenant</button>
            </form>

            <div id="progress-personnel" class="mt-4 hidden">
                <div class="flex items-center justify-between text-xs text-indigo-800 mb-1.5">
                    <span id="progress-personnel-label">En cours…</span>
                    <span id="progress-personnel-count"></span>
                </div>
                <div class="w-full h-2 bg-indigo-100 rounded-full overflow-hidden">
                    <div id="progress-personnel-bar" class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width:0%"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const statusUrl = (type) => @json(url('/admin/students/sync-status')) + '/' + type;

    function watch(type, opts) {
        const { totalLabel, countLabel, barEl, wrapEl, btnEl, totalEl, lastSyncEl } = opts;
        let timer = null;

        function poll() {
            fetch(statusUrl(type), { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'running') {
                        wrapEl.classList.remove('hidden');
                        btnEl.disabled = true;
                        btnEl.classList.add('opacity-50', 'cursor-not-allowed');
                        const page = data.page || 0;
                        const lastPage = data.last_page || null;
                        const synced = data.synced || 0;
                        countLabel.textContent = synced.toLocaleString('fr-FR') + ' synchronisé(s)' + (lastPage ? ` · page ${page}/${lastPage}` : '');
                        if (lastPage) {
                            const pct = Math.min(100, Math.round((page / lastPage) * 100));
                            barEl.style.width = pct + '%';
                        } else {
                            barEl.style.width = '8%';
                        }
                        timer = setTimeout(poll, 2000);
                    } else if (data.status === 'done') {
                        barEl.style.width = '100%';
                        countLabel.textContent = 'Terminé';
                        btnEl.disabled = false;
                        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
                        if (data.stats) {
                            const total = data.stats.students ?? data.stats.personnel ?? null;
                            if (total !== null) totalEl.textContent = total.toLocaleString('fr-FR');
                        }
                        lastSyncEl.textContent = new Date().toLocaleString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                        setTimeout(() => wrapEl.classList.add('hidden'), 4000);
                    } else if (data.status === 'failed') {
                        countLabel.textContent = 'Échec : ' + (data.message || 'erreur inconnue');
                        barEl.classList.remove('bg-blue-600', 'bg-indigo-600');
                        barEl.classList.add('bg-red-500');
                        btnEl.disabled = false;
                        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        wrapEl.classList.add('hidden');
                        btnEl.disabled = false;
                        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                })
                .catch(() => { timer = setTimeout(poll, 3000); });
        }

        poll();
    }

    watch('students', {
        wrapEl: document.getElementById('progress-students'),
        barEl: document.getElementById('progress-students-bar'),
        countLabel: document.getElementById('progress-students-count'),
        btnEl: document.getElementById('btn-sync-students'),
        totalEl: document.getElementById('students-total'),
        lastSyncEl: document.getElementById('students-last-sync'),
    });

    watch('personnel', {
        wrapEl: document.getElementById('progress-personnel'),
        barEl: document.getElementById('progress-personnel-bar'),
        countLabel: document.getElementById('progress-personnel-count'),
        btnEl: document.getElementById('btn-sync-personnel'),
        totalEl: document.getElementById('personnel-total'),
        lastSyncEl: document.getElementById('personnel-last-sync'),
    });

    // Après soumission du formulaire (lancement), afficher immédiatement la barre avant le premier poll.
    document.getElementById('form-sync-students')?.addEventListener('submit', () => {
        setTimeout(() => document.getElementById('progress-students').classList.remove('hidden'), 800);
    });
    document.getElementById('form-sync-personnel')?.addEventListener('submit', () => {
        setTimeout(() => document.getElementById('progress-personnel').classList.remove('hidden'), 800);
    });
})();
</script>
@endpush
@endsection
