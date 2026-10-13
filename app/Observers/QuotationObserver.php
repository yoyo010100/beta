<?php

namespace App\Observers;

use App\Models\Quotation;
use App\Services\AuditLogService;

/**
 * Audits quotation pricing changes (factory_cost / selling_price) and lifecycle.
 */
class QuotationObserver
{
    /** Pricing fields whose changes must be audited. */
    private const TRACKED = ['factory_cost', 'selling_price', 'profit_amount', 'currency_id', 'admin_notes'];

    public function __construct(private AuditLogService $audit) {}

    public function created(Quotation $quotation): void
    {
        $this->audit->logModelEvent($quotation, 'quotation.created', self::TRACKED);
    }

    public function updated(Quotation $quotation): void
    {
        if (! $this->hasTrackedChange($quotation)) {
            return;
        }

        $this->audit->logModelEvent($quotation, 'quotation.pricing_updated', self::TRACKED);
    }

    public function deleted(Quotation $quotation): void
    {
        $this->audit->logModelEvent($quotation, 'quotation.deleted');
    }

    private function hasTrackedChange(Quotation $quotation): bool
    {
        return (bool) array_intersect(array_keys($quotation->getChanges()), self::TRACKED);
    }
}
