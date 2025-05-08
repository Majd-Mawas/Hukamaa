<?php

namespace Modules\UserManagement\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            Route::middleware('web')
                ->group(module_path('UserManagement', 'routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(module_path('UserManagement', 'routes/api.php'));
        });
    }
}
