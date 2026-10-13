<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasPublicId, TranslatableJson;

    protected $guarded = ['id'];

    /** Never expose the auto-increment key in JSON/API responses. */
    protected $hidden = ['id'];

    /**
     * Bind routes by the 26-character ULID `public_id`, never the integer id.
     */
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function quoteRequests()
    {
        return $this->hasMany(QuoteRequest::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** Catalog cache TTL (1 hour). */
    public const CACHE_TTL = 3600;

    /**
     * Cache keys invalidated whenever a product changes.
     *
     * @return array<int, string>
     */
    public static function cacheKeys(): array
    {
        return array_map(
            static fn (string $locale): string => "public.products.all.{$locale}",
            ['en', 'ar']
        );
    }

    /**
     * Active products for the public catalog, cached in Redis for one hour.
     */
    public static function cachedActive(): Collection
    {
        return Cache::remember(
            'public.products.all.' . app()->getLocale(),
            self::CACHE_TTL,
            static fn () => static::query()->where('is_active', true)->get()
        );
    }
}
