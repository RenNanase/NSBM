<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

class TestRouteAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-route-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all routes with their middleware';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();

        $this->info("Routes with Middleware:");
        $this->table(
            ['Method', 'URI', 'Name', 'Middleware'],
            collect($routes)->map(function ($route) {
                $middleware = implode(', ', $route->middleware());
                return [
                    implode('|', $route->methods()),
                    $route->uri(),
                    $route->getName(),
                    $middleware ?: 'none'
                ];
            })->filter(function ($route) {
                // Only show routes with meaningful middleware
                return str_contains($route[3], 'auth') ||
                       str_contains($route[3], 'admin') ||
                       str_contains($route[3], 'maternity') ||
                       str_contains($route[3], 'ward');
            })->toArray()
        );

        // Show specific routes of interest
        $this->info("\nDelivery Routes:");
        $this->table(
            ['Method', 'URI', 'Name', 'Middleware'],
            collect($routes)->map(function ($route) {
                $middleware = implode(', ', $route->middleware());
                return [
                    implode('|', $route->methods()),
                    $route->uri(),
                    $route->getName(),
                    $middleware ?: 'none'
                ];
            })->filter(function ($route) {
                return str_contains($route[1], 'delivery');
            })->toArray()
        );

        // Show admin routes
        $this->info("\nAdmin-Only Routes:");
        $this->table(
            ['Method', 'URI', 'Name', 'Middleware'],
            collect($routes)->map(function ($route) {
                $middleware = implode(', ', $route->middleware());
                return [
                    implode('|', $route->methods()),
                    $route->uri(),
                    $route->getName(),
                    $middleware ?: 'none'
                ];
            })->filter(function ($route) {
                return str_contains($route[3], 'admin');
            })->toArray()
        );

        $this->info("\nTest the middleware with a specific user: 'php artisan app:test-maternity-access [username]'");

        return 0;
    }
}
