<?php

namespace App\Observers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Cache;

class AnnouncementObserver
{
    /**
     * Handle the Announcement "created" event.
     */
    public function created(Announcement $model): void
    {
        $this->clearAnnouncementCaches($model);
    }

    /**
     * Handle the Announcement "updated" event.
     */
    public function updated(Announcement $model): void
    {
        $this->clearAnnouncementCaches($model);
    }

    /**
     * Handle the Announcement "deleted" event.
     */
    public function deleted(Announcement $model): void
    {
        $this->clearAnnouncementCaches($model);
    }

    /**
     * Clear all announcement-related caches.
     */
    protected function clearAnnouncementCaches(Announcement $model): void
    {
        $locales = ['en', 'ar'];
        foreach ($locales as $locale) {
            Cache::forget("public.announcements.{$locale}");
            Cache::forget("public.news.latest.{$locale}");
            foreach (Announcement::cacheKeys() as $key) {
                Cache::forget($key);
            }
        }
    }
}