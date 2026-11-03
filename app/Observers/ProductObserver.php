<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $model): void
    {
        $this->clearProductCaches($model);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $model): void
    {
        $this->clearProductCaches($model);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $model): void
    {
        $this->clearProductCaches($model);
    }

    /**
     * Clear all product-related caches.
     */
    protected function clearProductCaches(Product $model): void
    {
        $locales = ['en', 'ar'];
        foreach ($locales as $locale) {
            Cache::forget("public_products_{$locale}");
            foreach (Product::cacheKeys() as $key) {
                Cache::forget($key);
            }
        }
    }
}