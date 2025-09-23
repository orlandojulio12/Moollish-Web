<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function () {
        // No se registra aquí el middleware personalizado
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configura excepciones personalizadas si lo deseas
    })
    ->booted(function ($app) {
        // Una vez que la aplicación se ha iniciado, registra tu middleware
        $app->router->aliasMiddleware('role', App\Http\Middleware\CheckRole::class);
        $app->router->aliasMiddleware('active.membership', \App\Http\Middleware\EnsureActiveMembership::class);

    })
    ->create();
