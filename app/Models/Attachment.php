<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Attachment extends Model
{
    use HasFactory, HasPublicId;

    protected $guarded = ['id'];

    public function getTable()
    {
        return 'attachments';
    }

    protected $casts = [
        'size_bytes' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Generate short-lived (5-minute) signed temporary download URL.
     */
    public function getTemporarySignedUrl(int $minutes = 5): string
    {
        return URL::temporarySignedRoute(
            'admin.attachments.download',
            now()->addMinutes($minutes),
            ['attachment' => $this->public_id]
        );
    }
}
