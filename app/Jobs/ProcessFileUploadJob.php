<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Models\JobApplication;
use App\Services\FileValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessFileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public int $timeout = 300;

    public function __construct(
        public string $tempFilePath,
        public string $originalFileName,
        public string $attachableType,
        public int $attachableId
    ) {
        $this->onQueue('media');
    }

    public function handle(FileValidationService $validator): void
    {
        if (! file_exists($this->tempFilePath)) {
            Log::error("FileUploadJob: Temporary file not found at {$this->tempFilePath}");

            return;
        }

        $fileSize = filesize($this->tempFilePath);

        // CVs are PDF-only; BOQ/drawing uploads share the broader allow-list.
        $profile = $this->attachableType === JobApplication::class
            ? FileValidationService::PROFILE_CV
            : FileValidationService::PROFILE_DRAWING;

        // Fast gate before anything touches the private disk.
        $gate = $validator->validatePath($this->tempFilePath, $this->originalFileName, $profile);

        $safeExtension  = strtolower(pathinfo($this->originalFileName, PATHINFO_EXTENSION));
        $secureFileName = (string) Str::ulid() . '.' . $safeExtension;
        $destinationPath = ($gate['ok'] ? 'documents/' : 'quarantine/') . date('Y/m') . '/' . $secureFileName;

        // Private storage: outside the web root.
        Storage::disk('private')->put($destinationPath, file_get_contents($this->tempFilePath));

        $attachment = Attachment::create([
            'public_id' => (string) Str::ulid(),
            'attachable_type' => $this->attachableType,
            'attachable_id' => $this->attachableId,
            'file_path' => $destinationPath,
            'file_name' => basename($this->originalFileName),
            'mime_type' => $gate['mime'],
            'size_bytes' => $fileSize,
            // Pre-gate failures are quarantined at once; clean files await the scan.
            'scan_status' => $gate['ok'] ? 'pending' : 'quarantined',
            'uploaded_at' => now(),
        ]);

        // Cleanup temporary file
        @unlink($this->tempFilePath);

        if ($gate['ok']) {
            ScanAttachmentMalwareJob::dispatch($attachment)
                ->onConnection('redis')->onQueue('media');
        } else {
            Log::warning("FileUploadJob: pre-storage validation failed for {$this->originalFileName}: {$gate['reason']}");
        }
    }
}
