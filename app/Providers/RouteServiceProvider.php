<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $domainName = config('app.url');

        if (App::environment("local")) {
            Route::domain("api.$domainName")
                ->prefix('v1')
                ->as('v1::')
                ->middleware(['mina'])
                ->namespace('App\Http\Controllers\Api\v1')
                ->group(app_path('Http/Routes/ApiV1.php'));
            Route::domain("api.$domainName")
                ->prefix('lift')
                ->as('lift::')
                ->middleware(['mina'])
                ->namespace('App\Http\Controllers\Api\lift')
                ->group(app_path('Http/Routes/Lift.php'));
            Route::domain("api.$domainName")
                ->prefix('suining')
                ->as('suining::')
                ->middleware(['mina'])
                ->namespace('App\Http\Controllers\Api\suining')
                ->group(app_path('Http/Routes/Suining.php'));
        } else {
            Route::domain("api.$domainName")
                ->prefix('v1')
                ->as('v1::')
                ->middleware(['api', 'auth:api'])
                ->namespace('App\Http\Controllers\Api\v1')
                ->group(app_path('Http/Routes/ApiV1.php'));
            Route::domain("api.$domainName")
                ->prefix('lift')
                ->as('lift::')
                ->middleware(['mina'])
                ->namespace('App\Http\Controllers\Api\lift')
                ->group(app_path('Http/Routes/Lift.php'));
        }

        Route::domain("admin.$domainName")
//            ->middleware(['ajax', 'balancer.db:read_admin'])
            ->namespace('App\Http\Controllers\Platform')
            ->group(app_path('Http/Routes/Platform.php'));

//        $this->mapApiRoutes();

//        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
