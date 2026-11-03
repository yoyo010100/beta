<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, HasPublicId, TranslatableJson;

    protected $guarded = ['id'];

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'is_active' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Bind routes by the 26-character ULID `public_id`, never the integer id.
     */
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}
