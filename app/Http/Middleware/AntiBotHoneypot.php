<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AntiBotHoneypot
{
    /**
     * Inspect incoming public form submissions for honeypot traps and math challenge verification.
     */
    public function handle(Request $request, Closure $next): Response
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

            // 2. Human Math Challenge verification
            $userAnswer = (int) $request->input('captcha_answer');
            $expectedAnswer = (int) $request->session()->get('math_captcha_result');

            if ($request->has('captcha_answer') && $expectedAnswer > 0) {
                if ($userAnswer !== $expectedAnswer) {
                    Log::info("Anti-Bot Defense: Math challenge failed by IP [{$request->ip()}]. Submitted: {$userAnswer}, Expected: {$expectedAnswer}");
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['captcha_answer' => __('Verification answer is incorrect. Please try again.')]);
                }
            }
        }

        return $next($request);
    }
}
