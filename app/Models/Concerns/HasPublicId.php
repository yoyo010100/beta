<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasPublicId
{
    /**
     * Boot the trait to assign a 26-character ULID upon creation.
     */
    public static function bootHasPublicId(): void
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::ulid();
            }
        });
    }

    /**
     * Use public_id for Route Model Binding to avoid integer ID exposure.
     */
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}
