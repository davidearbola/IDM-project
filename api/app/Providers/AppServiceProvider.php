<?php

namespace App\Providers;

// 1. AGGIUNGI QUESTI IMPORT NECESSARI
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // 2. AGGIUNGI QUESTA RIGA
        // Qui diciamo esplicitamente a Laravel di collegare l'evento di registrazione
        // all'azione di invio dell'email di verifica.
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );
    }
}