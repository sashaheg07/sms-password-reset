<?php

namespace ItTop\SmsPasswordReset;

use Illuminate\Support\ServiceProvider;

class SmsPasswordResetServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/smsc.php' => config_path('smsc.php'),
        ], 'config');
    }
}
