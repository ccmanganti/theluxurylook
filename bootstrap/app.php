<?php

use App\Http\Middleware\PreviewSearchGuard;
use App\Http\Middleware\LegacyUrlDecision;
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
        $middleware->append(PreviewSearchGuard::class);
        $middleware->web(append: [LegacyUrlDecision::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Laravel's exception handler handles 404s and validation errors.
    })->create();
