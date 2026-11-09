<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    use HasPublicId;

    // Never mass-assign the DB-generated column — MySQL computes it.
    protected $guarded = ['id', 'profit_amount'];

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
        'factory_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_amount' => 'decimal:2',
    ];

    // ─── CANONICAL QUOTE MATH (single source of truth for frontend + backend) ──
    /**
     * Gross Profit = Selling Price - Factory Cost.
     * Accepts decimal-cast strings or numbers; always computed as plain floats.
     */
    public static function grossProfit(mixed $sellingPrice, mixed $factoryCost): float
    {
        return (float) ($sellingPrice ?? 0) - (float) ($factoryCost ?? 0);
    }

    /**
     * Gross Margin % = (Gross Profit / Selling Price) * 100.
     * Guarded against a zero selling price.
     */
    public static function grossMarginPercent(mixed $sellingPrice, mixed $factoryCost): float
    {
        $selling = (float) ($sellingPrice ?? 0);
        if ($selling == 0.0) {
            return 0.0;
        }
        return (self::grossProfit($sellingPrice, $factoryCost) / $selling) * 100;
    }

    // NOTE: `profit_amount` is a STORED GENERATED column (`selling_price - factory_cost`)
    // computed by MySQL. It must NEVER be written by the application — any INSERT/UPDATE
    // that includes it fails with SQLSTATE[HY000]. Reads are fine.
    // booted(): no mutations — Profit = Selling Price - Factory Cost handled by the DB.

    // ─── RELATIONS ────────────────────────────────────────────────────────────
    public function quoteRequest(): BelongsTo { return $this->belongsTo(QuoteRequest::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function adminUser(): BelongsTo { return $this->belongsTo(User::class, 'admin_user_id'); }

    // ─── ACCESSORS ────────────────────────────────────────────────────────────
    public function getMarginPercentAttribute(): float
    {
        if (!$this->selling_price || $this->selling_price == 0) return 0;
        return round(static::grossMarginPercent($this->selling_price, $this->factory_cost), 2);
    }
}
