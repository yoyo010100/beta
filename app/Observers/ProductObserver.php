<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Cache;

/**
 * Flushes the public product catalog cache and audits catalog edits.
 */
class ProductObserver
{
    public function __construct(private AuditLogService $audit) {}

    public function saved(Product $product): void
    {
        $this->flush();
    }

    public function created(Product $product): void
    {
        $this->audit->logModelEvent($product, 'catalog.product.created');
    }

    public function updated(Product $product): void
    {
        $this->flush();
        $this->audit->logModelEvent($product, 'catalog.product.updated');
    }

    public function deleted(Product $product): void
    {
        $this->flush();
        $this->audit->logModelEvent($product, 'catalog.product.deleted');
    }

    public function restored(Product $product): void
    {
        $this->flush();
        $this->audit->logModelEvent($product, 'catalog.product.restored');
    }

    public function forceDeleted(Product $product): void
    {
        $this->flush();
        $this->audit->logModelEvent($product, 'catalog.product.force_deleted');
    }

    private function flush(): void
    {
        foreach (Product::cacheKeys() as $key) {
            Cache::forget($key);
        }
    }
}
