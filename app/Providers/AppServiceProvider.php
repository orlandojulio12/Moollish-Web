<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Este view composer se aplicará a todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Obtenemos el usuario autenticado
                $user = Auth::user();
                // Cargamos la relación de membresía con su plan para evitar consultas adicionales
                $user->load('membership.membershipPlan');
                // Compartimos la variable "user" en todas las vistas
                $view->with('user', $user);
            }
        });
    }
}
