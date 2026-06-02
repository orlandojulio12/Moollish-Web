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
    ->withMiddleware(function (\Illuminate\Foundation\Configuration\Middleware $middleware) {
        $middleware->alias([
            'role'             => \App\Http\Middleware\CheckRole::class,
            'active.membership' => \App\Http\Middleware\EnsureActiveMembership::class,
            'marketplace'      => \App\Http\Middleware\CheckMarketplaceAccess::class,
            'puede.vender'     => \App\Http\Middleware\CheckPuedeVender::class,
            'solo.moollish'    => \App\Http\Middleware\CheckSoloMoollish::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
