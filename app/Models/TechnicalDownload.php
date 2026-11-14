<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalDownload extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'issuance_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Return the title in the current (or requested) locale.
     * Falls back to English when the Arabic value is missing.
     */
    public function getTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'ar' && $this->title_ar) {
            return $this->title_ar;
        }

        return $this->title_en;
    }

    /**
     * Return the description in the current (or requested) locale.
     * Falls back to English when the Arabic value is missing.
     */
    public function getDescription(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === 'ar' && $this->description_ar) {
            return $this->description_ar;
        }

        return $this->description_en;
    }
}
