<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ComiteScientifique\DashboardController as ComiteDashboard;
use App\Http\Controllers\ComiteScientifique\SelectionController;
use App\Http\Controllers\DirectionRecherche\ComiteScientifiqueController;
use App\Http\Controllers\DirectionRecherche\DashboardController as DirectionDashboard;
use App\Http\Controllers\DirectionRecherche\PointFocalController;
use App\Http\Controllers\DirectionRecherche\PorteurProjetController;
use App\Http\Controllers\PointFocal\DashboardController as PointFocalDashboard;
use App\Http\Controllers\PorteurProjet\DashboardController as PorteurDashboard;
use App\Http\Controllers\PorteurProjet\ProjectController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Profile\PasswordController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {

    // Profil – accessible à tous les rôles
    Route::get('/profile/password', [PasswordController::class, 'edit'])->name('profile.password');
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');


    // Super Admin
    Route::prefix('superadmin')->name('superadmin.')
        ->middleware('role:superadmin')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        });

    // Direction de la Recherche
    Route::prefix('direction')->name('direction.')
        ->middleware('role:direction_recherche,superadmin')
        ->group(function () {
            Route::get('/dashboard', [DirectionDashboard::class, 'index'])->name('dashboard');

            // Porteurs de projet
            Route::resource('porteurs', PorteurProjetController::class)->except(['show']);
            Route::get('porteurs/{porteur}/show', [PorteurProjetController::class, 'show'])->name('porteurs.show');
            Route::post('porteurs/{porteur}/send-credentials', [PorteurProjetController::class, 'sendCredentials'])->name('porteurs.send-credentials');

            // Points Focaux
            Route::resource('point-focaux', PointFocalController::class)
                ->parameters(['point-focaux' => 'pointFocal'])
                ->except(['show']);

            // Comité Scientifique (membres)
            Route::resource('comite', ComiteScientifiqueController::class)
                ->parameters(['comite' => 'membre'])
                ->except(['show']);
        });

    // Porteur de Projet
    Route::prefix('porteur')->name('porteur.')
        ->middleware('role:porteur_projet,superadmin')
        ->group(function () {
            Route::get('/dashboard', [PorteurDashboard::class, 'index'])->name('dashboard');
            Route::get('/projects/{assignment}/fill', [ProjectController::class, 'create'])->name('projects.create');
            Route::post('/projects/{assignment}', [ProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
            Route::post('/projects/{project}/submit', [ProjectController::class, 'submit'])->name('projects.submit');
        });

    // Point Focal
    Route::prefix('point-focal')->name('point-focal.')
        ->middleware('role:point_focal,superadmin')
        ->group(function () {
            Route::get('/dashboard', [PointFocalDashboard::class, 'index'])->name('dashboard');
        });

    // Comité Scientifique
    Route::prefix('comite')->name('comite.')
        ->middleware('role:comite_scientifique,superadmin')
        ->group(function () {
            Route::get('/dashboard', [ComiteDashboard::class, 'index'])->name('dashboard');
            Route::get('/projects/{project}', [ComiteDashboard::class, 'show'])->name('projects.show');
            Route::post('/projects/{project}/toggle', [SelectionController::class, 'toggle'])->name('projects.toggle');
            Route::post('/send-emails', [SelectionController::class, 'sendEmails'])->name('send-emails');
        });
});
