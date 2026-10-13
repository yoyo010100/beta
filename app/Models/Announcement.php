<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Announcement extends Model
{
    use HasFactory, HasPublicId, TranslatableJson;

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

    public function getTable()
    {
        return 'announcements';
    }

    protected $casts = [
        'title' => 'array',
        'excerpt' => 'array',
        'content' => 'array',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now())
                     ->orderBy('published_at', 'desc');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** Catalog cache TTL (1 hour). */
    public const CACHE_TTL = 3600;

    /**
     * Cache keys invalidated whenever an announcement changes.
     *
     * @return array<int, string>
     */
    public static function cacheKeys(): array
    {
        $keys = [];

        foreach (['public.news.latest', 'public.announcements'] as $prefix) {
            foreach (['en', 'ar'] as $locale) {
                $keys[] = "{$prefix}.{$locale}";
            }
        }

        return $keys;
    }

    /**
     * Latest published announcements, cached in Redis for one hour.
     */
    public static function cachedLatest(int $limit = 3): Collection
    {
        return Cache::remember(
            'public.news.latest.' . app()->getLocale(),
            self::CACHE_TTL,
            static fn () => static::published()->limit($limit)->get()
        );
    }

    /**
     * Paginated published announcements, cached in Redis for one hour.
     */
    public static function cachedPaginated(int $perPage = 12)
    {
        return Cache::remember(
            'public.announcements.' . app()->getLocale(),
            self::CACHE_TTL,
            static fn () => static::published()->paginate($perPage)
        );
    }
}
