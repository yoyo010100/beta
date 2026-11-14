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

    protected $fillable = [
        'public_id',
        'title',
        'slug',
        'description',
        'specifications',
        'is_active',
        'file_path',
        'technical_file',
    ];

    /** Never expose the auto-increment key in JSON/API responses. */
    protected $hidden = ['id'];

    /**
     * Bind routes by slug for public routes, or fallback to public_id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
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

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
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
