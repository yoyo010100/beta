<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    /** Never expose the auto-increment key in JSON/API responses. */
    protected $hidden = ['id'];

    // ─── CANONICAL EVENT TYPES ────────────────────────────────────────────────
    public const EVENT_LOGIN = 'auth.login';
    public const EVENT_LOGIN_FAILED = 'auth.login_failed';
    public const EVENT_LOGOUT = 'auth.logout';
    public const EVENT_QUOTE_STATUS = 'quote.status_changed';
    public const EVENT_APPLICATION_STATUS = 'application.status_changed';
    public const EVENT_QUOTATION_PRICING = 'quotation.pricing_updated';
    public const EVENT_CATALOG = 'catalog.updated';

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function scopeEvent($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeForModel($query, Model|string $model)
    {
        $type = $model instanceof Model ? $model->getMorphClass() : $model;

        return $query->where('model_type', $type);
    }
}
