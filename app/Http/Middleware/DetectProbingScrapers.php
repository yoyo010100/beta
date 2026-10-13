<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * DetectProbingScrapers — Redis-backed abuse guard.
 *
 * Blocks IPs already serving a ban, and terminates with a rolling counter that
 * watches for bursts of 404/405 (invalid-route) probes. Crossing the threshold
 * bans the offending IP in Redis for 24 hours at `banned_ip:{ip}`.
 *
 * Fails open: if Redis is unavailable the request is allowed rather than 500ing.
 */
class DetectProbingScrapers
{
    /** Redis key prefix holding an active ban. */
    private const BAN_PREFIX = 'banned_ip:';

    /** Redis key prefix holding the rolling probe counter. */
    private const PROBE_PREFIX = 'probe_count:';

    /** Ban duration: 24 hours (seconds). */
    private const BAN_TTL = 86400;

    /** Sliding window used to measure probe velocity (seconds). */
    private const PROBE_WINDOW = 60;

    /** Suspicious responses within the window before the IP is banned. */
    private const PROBE_THRESHOLD = 20;

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $banned = false;

        try {
            $banned = $ip !== null && (bool) Redis::exists(self::BAN_PREFIX.$ip);
        } catch (Throwable) {
            // Fail open: never let a Redis outage take the site down.
        }

        if ($banned) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }

    /**
     * Inspect the final response (including rendered 404s) after it is sent.
     */
    public function terminate(Request $request, Response $response): void
    {
        $ip = $request->ip();

        if ($ip === null || ! $this->isProbe($response)) {
            return;
        }

        try {
            $key   = self::PROBE_PREFIX.$ip;
            $count = (int) Redis::incr($key);

            if ($count === 1) {
                Redis::expire($key, self::PROBE_WINDOW);
            }

            if ($count >= self::PROBE_THRESHOLD) {
                Redis::setex(self::BAN_PREFIX.$ip, self::BAN_TTL, 1);
                Redis::del($key);
            }
        } catch (Throwable) {
            // Fail open on Redis errors.
        }
    }

    /**
     * A probe is any "route does not exist / method not allowed" response.
     */
    private function isProbe(Response $response): bool
    {
        return in_array($response->getStatusCode(), [404, 405], true);
    }
}
