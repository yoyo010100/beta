<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * The product that owns this attribute.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Return the attribute name in the current (or requested) locale.
     */
    public function getName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $column = $locale === 'ar' ? 'name_ar' : 'name_en';

        return $this->{$column} ?? '';
    }

    /**
     * Return the attribute value in the current (or requested) locale.
     */
    public function getValue(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $column = $locale === 'ar' ? 'value_ar' : 'value_en';

        return $this->{$column} ?? '';
    }
}
