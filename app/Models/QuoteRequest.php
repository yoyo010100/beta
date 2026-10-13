<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteRequest extends Model
{
    use HasPublicId, TranslatableJson, SoftDeletes;

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
        return 'quote_requests';
    }

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // ─── STATUS CONSTANTS ─────────────────────────────────────────────────────
    const STATUS_NEW = 'new';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_QUOTED = 'quoted';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    // ─── RELATIONS ────────────────────────────────────────────────────────────
    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function quotation(): HasOne { return $this->hasOne(Quotation::class); }
    public function attachments(): HasMany { return $this->hasMany(Attachment::class, 'attachable_id')->where('attachable_type', self::class); }
}
