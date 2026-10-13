<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * AuditLogService — executive audit trail for administrative activity.
 *
 * Persists actor, event type, subject model type/id, client IP, user agent and
 * a redacted payload diff (old_values vs new_values) into `audit_logs`.
 */
class AuditLogService
{
    /** Attributes that must never be stored verbatim. */
    private const REDACTED_KEYS = [
        'password', 'password_confirmation', 'remember_token', 'token', 'secret',
        '_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    /**
     * Persist an audit entry.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function log(
        string $eventType,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'actor_user_id' => Auth::id(),
            'event_type'    => $eventType,
            'model_type'    => $model?->getMorphClass(),
            'model_id'      => $model ? ($model->getAttribute('public_id') ?? (string) $model->getKey()) : null,
            'old_values'    => $this->redact($oldValues),
            'new_values'    => $this->redact($newValues),
            'ip_address'    => $this->clientIp(),
            'user_agent'    => $this->userAgent(),
            'created_at'    => now(),
        ]);
    }

    /**
     * Log a model lifecycle event, deriving the diff from the model itself.
     *
     * @param  array<int, string>|null  $only  Restrict the diff to these attributes.
     */
    public function logModelEvent(Model $model, string $eventType, ?array $only = null): AuditLog
    {
        [$old, $new] = $this->diff($model, $eventType, $only);

        return $this->log($eventType, $model, $old, $new);
    }

    /**
     * Log an authentication event (login, failure, logout).
     *
     * @param  array<string, mixed>  $context
     */
    public function logAuth(string $eventType, ?Model $actor = null, array $context = []): AuditLog
    {
        return $this->log($eventType, $actor, null, $context ?: null);
    }

    /**
     * @param  array<int, string>|null  $only
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    private function diff(Model $model, string $eventType, ?array $only): array
    {
        if (str_ends_with($eventType, 'created')) {
            [$old, $new] = [[], $model->getAttributes()];
        } elseif (str_ends_with($eventType, 'deleted')) {
            [$old, $new] = [$model->getOriginal(), []];
        } else {
            $new = $model->getChanges();
            $old = [];

            foreach (array_keys($new) as $key) {
                $old[$key] = $model->getOriginal($key);
            }
        }

        if ($only !== null) {
            $keys = array_flip($only);
            $old  = array_intersect_key($old, $keys);
            $new  = array_intersect_key($new, $keys);
        }

        return [$old, $new];
    }

    /**
     * @param  array<string, mixed>|null  $values
     * @return array<string, mixed>|null
     */
    private function redact(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach ($values as $key => $value) {
            if (in_array($key, self::REDACTED_KEYS, true)) {
                $values[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $values[$key] = $this->redact($value);
            }
        }

        return $values;
    }

    private function clientIp(): ?string
    {
        try {
            return request()->ip();
        } catch (\Throwable) {
            return null;
        }
    }

    private function userAgent(): ?string
    {
        try {
            return substr((string) request()->userAgent(), 0, 500);
        } catch (\Throwable) {
            return null;
        }
    }
}
