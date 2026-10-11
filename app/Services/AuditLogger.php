<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Log an administrative or critical security action.
     */
    public static function log(
        string $eventType,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        // Redact sensitive keys
        $redactKeys = ['password', 'password_confirmation', 'remember_token', '_token'];
        if ($oldValues) {
            foreach ($redactKeys as $k) {
                if (isset($oldValues[$k])) {
                    $oldValues[$k] = '[REDACTED]';
                }
            }
        }
        if ($newValues) {
            foreach ($redactKeys as $k) {
                if (isset($newValues[$k])) {
                    $newValues[$k] = '[REDACTED]';
                }
            }
        }

        return AuditLog::create([
            'actor_user_id' => Auth::id(),
            'event_type' => $eventType,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? ($model->public_id ?? (string) $model->getKey()) : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => substr(Request::userAgent() ?? '', 0, 500),
            'created_at' => now(),
        ]);
    }
}
