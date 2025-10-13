<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for
            ('api', function (Request $request) 
                {
                    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
                }
            );

        $this->routes(function () 
        
            {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(function()
                    {
                        require base_path('routes/dashboard/admin.php');
                        require base_path('routes/dashboard/auth.php');
                        require base_path('routes/dashboard/Apps/docs.php');
                        require base_path('routes/dashboard/Apps/certificados.php');
                        require base_path('routes/dashboard/Apps/coquillas.php');
                        require base_path('routes/dashboard/Admin/users.php');      
                    });
            });
    }
}