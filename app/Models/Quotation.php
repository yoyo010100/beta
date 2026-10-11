<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    use HasPublicId;

    protected $fillable = [
        'public_id', 'quote_request_id', 'admin_user_id', 'currency_id',
        'factory_cost', 'selling_price', 'profit_amount',
        'admin_notes', 'status',
    ];

    protected $casts = [
        'factory_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_amount' => 'decimal:2',
    ];

    // ─── BOOT: auto-compute profit ─────────────────────────────────────────────
    protected static function booted(): void
    {
        static::saving(function (Quotation $q) {
            $q->profit_amount = ($q->selling_price ?? 0) - ($q->factory_cost ?? 0);
        });
    }

    // ─── RELATIONS ────────────────────────────────────────────────────────────
    public function quoteRequest(): BelongsTo { return $this->belongsTo(QuoteRequest::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function adminUser(): BelongsTo { return $this->belongsTo(User::class, 'admin_user_id'); }

    // ─── ACCESSORS ────────────────────────────────────────────────────────────
    public function getMarginPercentAttribute(): float
    {
        if (!$this->selling_price || $this->selling_price == 0) return 0;
        return round(($this->profit_amount / $this->selling_price) * 100, 2);
    }
}
