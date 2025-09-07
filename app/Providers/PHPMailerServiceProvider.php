<?php

namespace App\Providers;

use App\Services\PHPMailerService;
use Illuminate\Support\ServiceProvider;

class PHPMailerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PHPMailerService::class, function ($app) {
            return new PHPMailerService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}