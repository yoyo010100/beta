<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AntiBotHoneypot
{
    /**
     * Inspect incoming public form submissions for honeypot traps.
     *
     * Standard bots will automatically fill in all input fields,
     * including hidden honeypot fields. Human users will leave them empty.
     */
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        if ($request->isMethod('post')) {
            // 1. Invisible Honeypot field inspection
            // Standard bots will automatically fill in all input fields
            $honeypot = $request->input('website_url_hp');
            if (!empty($honeypot)) {
                Log::warning("Anti-Bot Defense: Trapped automated bot submission via honeypot from IP [{$request->ip()}]. Silently dropped.");
                // Return immediate 200/redirect to avoid tipping off bot authors
                return redirect()->back()->with('success', __('Your request has been received successfully.'));
            }
        }

        return $next($request);
    }
}