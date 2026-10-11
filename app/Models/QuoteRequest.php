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

    protected $fillable = [
        'public_id', 'client_name', 'company_name', 'email', 'phone',
        'country_id', 'service_id', 'product_id',
        'client_message', 'status', 'submitted_at',
    ];

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
