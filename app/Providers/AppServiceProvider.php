<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Public GET rate limiting: Max 180 requests per minute per IP
        RateLimiter::for('public-get', function (Request $request) {
            return Limit::perMinute(180)->by($request->ip());
        });

        // Public Form submission rate limiting: Max 15 submissions per hour per IP
        RateLimiter::for('public-forms', function (Request $request) {
            return Limit::perHour(15)->by($request->ip());
        });
    }
}
