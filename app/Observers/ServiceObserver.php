<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Cache;

/**
 * Flushes the public service catalog cache and audits catalog edits.
 */
class ServiceObserver
{
    public function __construct(private AuditLogService $audit) {}

    public function saved(Service $service): void
    {
        $this->flush();
    }

    public function created(Service $service): void
    {
        $this->audit->logModelEvent($service, 'catalog.service.created');
    }

    public function updated(Service $service): void
    {
        $this->flush();
        $this->audit->logModelEvent($service, 'catalog.service.updated');
    }

    public function deleted(Service $service): void
    {
        $this->flush();
        $this->audit->logModelEvent($service, 'catalog.service.deleted');
    }

    public function restored(Service $service): void
    {
        $this->flush();
        $this->audit->logModelEvent($service, 'catalog.service.restored');
    }

    public function forceDeleted(Service $service): void
    {
        $this->flush();
        $this->audit->logModelEvent($service, 'catalog.service.force_deleted');
    }

    private function flush(): void
    {
        foreach (Service::cacheKeys() as $key) {
            Cache::forget($key);
        }
    }
}
