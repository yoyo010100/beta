<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DynamicScraperBanning
{
    /**
     * Patterns commonly searched by vulnerability scanners and abusive bots.
     */
    protected array $probedPatterns = [
        '.env',
        'wp-login',
        'wp-admin',
        'phpinfo',
        '.git/',
        'config.php',
        'xmlrpc.php',
        'eval-stdin.php',
        'vendor/phpunit',
        'telescope',
        'actuator',
    ];

    /**
     * Inspect request path against vulnerability probes and enforce dynamic IP bans.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $banKey = "banned_ip:{$ip}";

        // 1. Check if IP is currently banned
        if (Cache::has($banKey)) {
            Log::warning("Dynamic Scraper Defense: Blocked banned IP [{$ip}] attempting request: {$request->path()}");
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Your IP address has been temporarily banned due to suspicious activity.'
            ], 403);
        }

        // 2. Check for malicious probing attempts
        $path = strtolower($request->path());
        foreach ($this->probedPatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                $strikeKey = "probe_strikes:{$ip}";
                $strikes = (int) Cache::get($strikeKey, 0) + 1;
                Cache::put($strikeKey, $strikes, 3600); // 1-hour tracking window

                Log::alert("Security Probe Detected: IP [{$ip}] probed prohibited path [{$path}] (Strike {$strikes}/3)");

                if ($strikes >= 3) {
                    // Ban IP in Cache/Redis for 24 hours (86,400 seconds)
                    Cache::put($banKey, true, 86400);
                    Log::critical("SECURITY ACTION: Dynamic Scraper Banned IP [{$ip}] for 24 hours after {$strikes} malicious probes.");
                    return response()->json([
                        'error' => 'Forbidden',
                        'message' => 'Access denied and IP banned.'
                    ], 403);
                }

                return response()->json(['error' => 'Not Found'], 404);
            }
        }

        return $next($request);
    }
}
