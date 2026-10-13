<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory, HasPublicId;

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

    protected $casts = [
        'rating' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
