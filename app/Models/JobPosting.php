<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory, HasPublicId, TranslatableJson;

    protected $guarded = ['id'];

    public function getTable()
    {
        return 'job_postings';
    }

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
        'closes_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
