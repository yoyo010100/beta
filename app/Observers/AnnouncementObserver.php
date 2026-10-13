<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Cache;

/**
 * Flushes the public announcements/news cache and audits catalog edits.
 */
class AnnouncementObserver
{
    public function __construct(private AuditLogService $audit) {}

    public function saved(Announcement $announcement): void
    {
        $this->flush();
    }

    public function created(Announcement $announcement): void
    {
        $this->audit->logModelEvent($announcement, 'catalog.announcement.created');
    }

    public function updated(Announcement $announcement): void
    {
        $this->flush();
        $this->audit->logModelEvent($announcement, 'catalog.announcement.updated');
    }

    public function deleted(Announcement $announcement): void
    {
        $this->flush();
        $this->audit->logModelEvent($announcement, 'catalog.announcement.deleted');
    }

    public function restored(Announcement $announcement): void
    {
        $this->flush();
        $this->audit->logModelEvent($announcement, 'catalog.announcement.restored');
    }

    public function forceDeleted(Announcement $announcement): void
    {
        $this->flush();
        $this->audit->logModelEvent($announcement, 'catalog.announcement.force_deleted');
    }

    private function flush(): void
    {
        foreach (Announcement::cacheKeys() as $key) {
            Cache::forget($key);
        }
    }
}
