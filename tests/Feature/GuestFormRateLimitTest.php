<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class GuestFormRateLimitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The rate limiter uses the cache store, which is the array driver in tests.
        Cache::flush();

        // Keep the probe guard from opening a real Redis connection per request.
        Redis::shouldReceive('exists')->andReturn(false);
    }

    public function test_contact_and_careers_forms_use_independent_rate_limit_buckets(): void
    {
        // Empty submissions fail validation and redirect; they are enough to
        // exercise the throttle middleware without writing to the database.
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $this->post('/contact/quote')->assertRedirect();
        }

        $this->post('/contact/quote')->assertStatus(429);

        // Exhausting the quote form must not consume the careers allowance.
        $this->post('/careers/apply')->assertRedirect();
    }
}
