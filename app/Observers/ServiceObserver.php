<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $model): void
    {
        $this->clearServiceCaches($model);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $model): void
    {
        $this->clearServiceCaches($model);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $model): void
    {
        $this->clearServiceCaches($model);
    }

    /**
     * Clear all service-related caches.
     */
    protected function clearServiceCaches(Service $model): void
    {
        $locales = ['en', 'ar'];
        foreach ($locales as $locale) {
            Cache::forget("public.services.{$locale}");
            Cache::forget("public.services.all.{$locale}");
            foreach (Service::cacheKeys() as $key) {
                Cache::forget($key);
            }
        }
    }
}