<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ComiteScientifique\DashboardController as ComiteDashboard;
use App\Http\Controllers\ComiteScientifique\SelectionController;
use App\Http\Controllers\ComiteScientifique\PorteurController as ComitePorteurController;
use App\Http\Controllers\ComiteScientifique\EventConfigController as ComiteEventController;
use App\Http\Controllers\ComiteScientifique\ProjectController as ComiteProjectController;
use App\Http\Controllers\DirectionRecherche\ComiteScientifiqueController;
use App\Http\Controllers\DirectionRecherche\DashboardController as DirectionDashboard;
use App\Http\Controllers\DirectionRecherche\PointFocalController;
use App\Http\Controllers\DirectionRecherche\PorteurProjetController;
use App\Http\Controllers\DirectionRecherche\SecretaireController as DirectionSecretaireController;
use App\Http\Controllers\PointFocal\DashboardController as PointFocalDashboard;
use App\Http\Controllers\PorteurProjet\DashboardController as PorteurDashboard;
use App\Http\Controllers\PorteurProjet\ProjectController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Profile\PasswordController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\SuperAdmin\ProjectController as SuperAdminProjectController;
use App\Http\Controllers\SuperAdmin\ImpersonateController;
use App\Http\Controllers\Admin\FormOptionController;
use App\Http\Controllers\Admin\EventConfigController as AdminEventConfigController;
use App\Http\Controllers\Secretaire\DashboardController as SecretaireDashboard;
use App\Http\Controllers\Secretaire\RegistrationController as SecretaireRegistrationController;
use App\Http\Controllers\Secretaire\QuestionnaireController as SecretaireQuestionnaireController;
use App\Http\Controllers\Secretaire\ProjectController as SecretaireProjectController;
use App\Http\Controllers\Public\RegistrationController as PublicRegistrationController;
use App\Http\Controllers\Public\QuestionnaireController as PublicQuestionnaireController;
use App\Http\Controllers\Public\LandingController as PublicLandingController;
use Illuminate\Support\Facades\Route;

// ─── Routes publiques (sans auth) ────────────────────────────────────────────
Route::get('/', function () {
    $event = \App\Models\EventConfig::where('is_active', true)->first();
    if ($event) {
        return redirect()->route('public.landing', $event->event_slug);
    }
    return redirect()->route('login');
});
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Formulaires publics (inscription + questionnaire)
Route::prefix('event/{eventSlug}')->group(function () {
    Route::get('/',                [PublicLandingController::class, 'show'])->name('public.landing');
    Route::get('/inscription',     [PublicRegistrationController::class, 'show'])->name('public.registration.show');
    Route::post('/inscription',    [PublicRegistrationController::class, 'store'])->name('public.registration.store');
    Route::get('/questionnaire',   [PublicQuestionnaireController::class, 'show'])->name('public.questionnaire.show');
    Route::post('/questionnaire',  [PublicQuestionnaireController::class, 'store'])->name('public.questionnaire.store');
    Route::get('/confirmation/{token}', [PublicRegistrationController::class, 'confirmation'])->name('public.registration.confirmation');
});

// ─── Routes authentifiées ─────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Profil – accessible à tous
    Route::get('/profile/password', [PasswordController::class, 'edit'])->name('profile.password');
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');

    // ── Super Admin ──────────────────────────────────────────────────────────
    Route::prefix('superadmin')->name('superadmin.')
        ->middleware('role:superadmin')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::get('/roles-permissions', [UserController::class, 'rolesPermissions'])->name('roles.overview');
            // CRUD Rôles
            Route::resource('roles', RoleController::class)->except(['show']);
            // CRUD Permissions
            Route::resource('permissions', PermissionController::class)->except(['show']);

            // Gestion des projets (superadmin)
            Route::get('/projects', [SuperAdminProjectController::class, 'index'])->name('projects.index');
            Route::get('/projects/create', [SuperAdminProjectController::class, 'create'])->name('projects.create');
            Route::post('/projects', [SuperAdminProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}/edit', [SuperAdminProjectController::class, 'edit'])->name('projects.edit');
            Route::put('/projects/{project}', [SuperAdminProjectController::class, 'update'])->name('projects.update');
            Route::post('/projects/{project}/submit', [SuperAdminProjectController::class, 'submit'])->name('projects.submit');
            Route::get('/assignments/{assignment}/fill', [SuperAdminProjectController::class, 'fill'])->name('assignments.fill');
            Route::post('/assignments/{assignment}/fill', [SuperAdminProjectController::class, 'storeFill'])->name('assignments.store-fill');

            // Impersonation porteur (start uniquement — stop est hors groupe pour rester accessible en tant que porteur)
            Route::get('/impersonate/{user}', [ImpersonateController::class, 'start'])->name('impersonate.start')->whereNumber('user');
        });

    // Stop impersonation — hors du groupe role:superadmin car l'user courant est le porteur
    Route::get('/superadmin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('superadmin.impersonate.stop');

    // ── Admin (superadmin + direction) ─────────────────────────────────────
    Route::prefix('admin')->name('admin.')
        ->middleware('role:superadmin,direction_recherche')
        ->group(function () {
            // CRUD options formulaire
            Route::resource('form-options', FormOptionController::class);
            Route::patch('form-options/{formOption}/toggle', [FormOptionController::class, 'toggle'])->name('form-options.toggle');

            // CRUD configuration événement
            Route::resource('event-configs', AdminEventConfigController::class);
            Route::patch('event-configs/{eventConfig}/activate', [AdminEventConfigController::class, 'activate'])->name('event-configs.activate');
        });

    // ── Direction de la Recherche ───────────────────────────────────────────
    Route::prefix('direction')->name('direction.')
        ->middleware('role:direction_recherche,superadmin')
        ->group(function () {
            Route::get('/dashboard', [DirectionDashboard::class, 'index'])->name('dashboard');

            Route::resource('porteurs', PorteurProjetController::class)->except(['show']);
            Route::get('porteurs/{porteur}/show', [PorteurProjetController::class, 'show'])->name('porteurs.show');
            Route::post('porteurs/{porteur}/send-credentials', [PorteurProjetController::class, 'sendCredentials'])->name('porteurs.send-credentials');

            Route::resource('point-focaux', PointFocalController::class)
                ->parameters(['point-focaux' => 'pointFocal'])
                ->except(['show']);

            Route::resource('comite', ComiteScientifiqueController::class)
                ->parameters(['comite' => 'membre'])
                ->except(['show']);

            Route::resource('secretaires', DirectionSecretaireController::class)
                ->parameters(['secretaires' => 'secretaire'])
                ->except(['show']);
        });

    // ── Porteur de Projet ───────────────────────────────────────────────────
    Route::prefix('porteur')->name('porteur.')
        ->middleware('role:porteur_projet,superadmin')
        ->group(function () {
            Route::get('/dashboard', [PorteurDashboard::class, 'index'])->name('dashboard');
            Route::get('/projects/{assignment}/fill', [ProjectController::class, 'create'])->name('projects.create');
            Route::post('/projects/{assignment}', [ProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
            Route::post('/projects/{project}/submit', [ProjectController::class, 'submit'])->name('projects.submit');
            Route::get('/projects/{project}/show', [ProjectController::class, 'show'])->name('projects.show');
        });

    // ── Point Focal (Observateur) ───────────────────────────────────────────
    Route::prefix('point-focal')->name('point-focal.')
        ->middleware('role:point_focal,superadmin')
        ->group(function () {
            Route::get('/dashboard', [PointFocalDashboard::class, 'index'])->name('dashboard');
            Route::get('/projects/{project}', [PointFocalDashboard::class, 'showProject'])->name('projects.show');
        });

    // ── Comité Scientifique ─────────────────────────────────────────────────
    Route::prefix('comite')->name('comite.')
        ->middleware('role:comite_scientifique,superadmin')
        ->group(function () {
            Route::get('/dashboard', [ComiteDashboard::class, 'index'])->name('dashboard');
            Route::get('/projects/{project}', [ComiteDashboard::class, 'show'])->name('projects.show');
            Route::post('/projects/{project}/toggle', [SelectionController::class, 'toggle'])->name('projects.toggle');
            Route::post('/send-emails', [SelectionController::class, 'sendEmails'])->name('send-emails');
            Route::get('/projects/export', [ComiteProjectController::class, 'export'])->name('projects.export');

            // Gestion porteurs par le comité
            Route::resource('porteurs', ComitePorteurController::class)->except(['show']);
            Route::post('porteurs/{porteur}/send-credentials', [ComitePorteurController::class, 'sendCredentials'])->name('porteurs.send-credentials');

            // Période de soumission
            Route::get('/submission-period', [ComiteEventController::class, 'edit'])->name('submission-period.edit');
            Route::put('/submission-period', [ComiteEventController::class, 'update'])->name('submission-period.update');
        });

    // ── Secrétaire ──────────────────────────────────────────────────────────
    Route::prefix('secretaire')->name('secretaire.')
        ->middleware('role:secretaire,superadmin')
        ->group(function () {
            Route::get('/dashboard', [SecretaireDashboard::class, 'index'])->name('dashboard');
            Route::get('/qr/{type}', [SecretaireDashboard::class, 'downloadQr'])->name('qr.download');

            Route::get('inscriptions/export', [SecretaireRegistrationController::class, 'export'])->name('inscriptions.export');
            Route::post('inscriptions/import', [SecretaireRegistrationController::class, 'import'])->name('inscriptions.import');
            Route::resource('inscriptions', SecretaireRegistrationController::class)
                ->only(['index', 'show', 'destroy'])
                ->parameters(['inscriptions' => 'registration']);
            Route::patch('inscriptions/{registration}/presence', [SecretaireRegistrationController::class, 'togglePresence'])->name('inscriptions.presence');

            Route::get('questionnaires/export', [SecretaireQuestionnaireController::class, 'export'])->name('questionnaires.export');
            Route::post('questionnaires/import', [SecretaireQuestionnaireController::class, 'import'])->name('questionnaires.import');
            Route::resource('questionnaires', SecretaireQuestionnaireController::class)->only(['index','show','destroy']);

            Route::get('projets', [SecretaireProjectController::class, 'index'])->name('projets.index');
            Route::get('projets/export', [SecretaireProjectController::class, 'export'])->name('projets.export');
            Route::get('projets/{project}', [SecretaireProjectController::class, 'show'])->name('projets.show');
        });
});
