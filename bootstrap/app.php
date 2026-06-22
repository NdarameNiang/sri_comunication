<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'   => \App\Http\Middleware\CheckRole::class,
            'active' => \App\Http\Middleware\CheckActive::class,
        ]);

        // Login et logout exemptés du CSRF — évite les 419 sur ces deux routes
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Session expirée → redirection login avec message
        $exceptions->render(function (
            \Illuminate\Auth\AuthenticationException $e,
            \Illuminate\Http\Request $request
        ) {
            if (! $request->expectsJson()) {
                return redirect()->route('login')
                    ->with('warning', 'Votre session a expiré. Veuillez vous reconnecter.');
            }
        });

        // CSRF expiré uniquement sur les pages authentifiées (pas sur le formulaire de login)
        $exceptions->render(function (
            \Illuminate\Session\TokenMismatchException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->is('login') || $request->is('logout')) {
                return redirect()->route('login');
            }
            return redirect()->route('login')
                ->with('warning', 'Votre session a expiré. Merci de vous reconnecter et de soumettre à nouveau.');
        });

    })->create();
