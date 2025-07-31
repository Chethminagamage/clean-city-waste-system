<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureTwoFactorCodeIsVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
            '2fa'  => EnsureTwoFactorCodeIsVerified::class, // ✅ Register 2FA middleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();