<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Global middleware (runs on every request)
        $middleware->append([
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Throttle rate limiters
        $middleware->throttleWithRedis();

        // Middleware aliases
        $middleware->alias([
            'admin'   => \App\Http\Middleware\AdminMiddleware::class,
            'antibot' => \App\Http\Middleware\AntiBotHoneypot::class,
        ]);

        // Trusted proxies (for XAMPP behind nginx/load balancer)
        $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (HttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
            }

            $code = $e->getStatusCode();
            if (in_array($code, [403, 404, 429, 500, 503])) {
                return response()->view("errors.{$code}", ['message' => $e->getMessage()], $code);
            }
        });
    })
    ->create();
