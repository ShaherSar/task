<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->singleton(ImageManager::class, function(){
            return new ImageManager(Driver::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        RateLimiter::for('api', function (Request $request) {
//            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
//        });
    }
}
