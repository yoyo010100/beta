<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasPublicId, TranslatableJson;

    protected $guarded = ['id'];

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
}
