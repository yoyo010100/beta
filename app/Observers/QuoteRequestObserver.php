<?php

namespace App\Observers;

use App\Models\QuoteRequest;
use App\Services\AuditLogService;

/**
 * Audits administrative status changes on quote requests.
 */
class QuoteRequestObserver
{
    public function __construct(private AuditLogService $audit) {}

    public function updated(QuoteRequest $quoteRequest): void
    {
        if (! array_key_exists('status', $quoteRequest->getChanges())) {
            return;
        }

        $this->audit->logModelEvent($quoteRequest, 'quote.status_changed', ['status']);
    }
}
