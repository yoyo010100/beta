<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Serve private file via short-lived (5-min) signed URL — admin only.
     */
    public function download(Request $request, Attachment $attachment)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This secure download link has expired. Please generate a new one.');
        }

        if ($attachment->scan_status === 'quarantined') {
            abort(403, 'This file is quarantined due to a failed security scan.');
        }

        if (!Storage::disk('private')->exists($attachment->file_path)) {
            abort(404, 'The requested file could not be found in secure storage.');
        }

        AuditLogger::log('attachment.downloaded', $attachment, null, [
            'file_name' => $attachment->file_name,
            'scan_status' => $attachment->scan_status,
        ]);

        return Storage::disk('private')->download(
            $attachment->file_path,
            $attachment->file_name,
            [
                'Content-Disposition' => 'attachment; filename="' . $attachment->file_name . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    /**
     * Regenerate a fresh 5-minute signed download URL (AJAX).
     */
    public function getSignedUrl(Attachment $attachment)
    {
        $url = $attachment->getTemporarySignedUrl(5);
        return response()->json(['url' => $url, 'expires_in' => 300]);
    }
}
