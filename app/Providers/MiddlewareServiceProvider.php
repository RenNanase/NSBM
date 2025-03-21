<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\MaternityWardAccessMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\WardAccessMiddleware;
use App\Http\Middleware\EmergencyDepartmentMiddleware;
use App\Http\Middleware\NonEmergencyDepartmentMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Http\Response;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MaternityWardAccessMiddleware::class);
        $this->app->singleton(AdminMiddleware::class);
        $this->app->singleton(WardAccessMiddleware::class);
        $this->app->singleton(EmergencyDepartmentMiddleware::class);
        $this->app->singleton(NonEmergencyDepartmentMiddleware::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('maternity.access', MaternityWardAccessMiddleware::class);
        $router->aliasMiddleware('admin', AdminMiddleware::class);
        $router->aliasMiddleware('ward.access', WardAccessMiddleware::class);
        $router->aliasMiddleware('emergency.department', EmergencyDepartmentMiddleware::class);
        $router->aliasMiddleware('non.emergency', NonEmergencyDepartmentMiddleware::class);
    }
}
