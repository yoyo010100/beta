<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * WriteAuditLogJob — append-only security/audit trail, kept off the hot path.
 */
class WriteAuditLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public int $backoff = 5;

    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public string $event,
        public array $context = [],
    ) {
        $this->onQueue('audit');
    }

    public function handle(): void
    {
        Log::info("[audit] {$this->event}", $this->context);

        // In production: AuditLog::create([
        //     'event' => $this->event, 'context' => $this->context,
        // ]);
    }
}
