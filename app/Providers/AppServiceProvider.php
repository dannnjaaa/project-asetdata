<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Router;

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
        // Register 'is_admin' middleware alias so routes can use it
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('is_admin', \App\Http\Middleware\IsAdmin::class);
    }
}
