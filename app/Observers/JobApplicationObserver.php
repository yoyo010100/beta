<?php

namespace App\Observers;

use App\Models\JobApplication;
use App\Services\AuditLogService;

/**
 * Audits administrative status and rating changes on job applications.
 */
class JobApplicationObserver
{
    public function __construct(private AuditLogService $audit) {}

    public function updated(JobApplication $application): void
    {
        $changes = $application->getChanges();

        if (array_key_exists('status', $changes)) {
            $this->audit->logModelEvent($application, 'application.status_changed', ['status']);
        }

        if (array_key_exists('rating', $changes)) {
            $this->audit->logModelEvent($application, 'application.rating_changed', ['rating']);
        }
    }
}
