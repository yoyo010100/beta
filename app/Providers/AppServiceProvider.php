<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\JobApplication;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Observers\AnnouncementObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\ProductObserver;
use App\Observers\QuotationObserver;
use App\Observers\QuoteRequestObserver;
use App\Observers\ServiceObserver;
use App\Services\AuditLogService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
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

        // Bust the Redis catalog cache whenever a catalog record changes.
        Product::observe(ProductObserver::class);
        Service::observe(ServiceObserver::class);
        Announcement::observe(AnnouncementObserver::class);

        // Automatic audit logging on critical admin modifications.
        Quotation::observe(QuotationObserver::class);
        QuoteRequest::observe(QuoteRequestObserver::class);
        JobApplication::observe(JobApplicationObserver::class);

        // Audit authentication events (login, failure, logout).
        Event::listen(Login::class, function (Login $event): void {
            app(AuditLogService::class)->logAuth('auth.login', $event->user, [
                'email' => $event->user->email ?? null,
            ]);
        });

        Event::listen(Failed::class, function (Failed $event): void {
            app(AuditLogService::class)->logAuth('auth.login_failed', $event->user, [
                'email' => $event->credentials['email'] ?? null,
            ]);
        });

        Event::listen(Logout::class, function (Logout $event): void {
            app(AuditLogService::class)->logAuth('auth.logout', $event->user, [
                'email' => $event->user->email ?? null,
            ]);
        });
    }
}
