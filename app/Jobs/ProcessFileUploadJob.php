<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Services\Security\MalwareScannerService;
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

    public function __construct(
        public string $tempFilePath,
        public string $originalFileName,
        public string $attachableType,
        public int $attachableId
    ) {}

    public function handle(MalwareScannerService $scanner): void
    {
        if (!file_exists($this->tempFilePath)) {
            Log::error("FileUploadJob: Temporary file not found at {$this->tempFilePath}");
            return;
        }

        $scanResult = $scanner->scan($this->tempFilePath);
        $fileSize = filesize($this->tempFilePath);

        $safeExtension = strtolower(pathinfo($this->originalFileName, PATHINFO_EXTENSION));
        $secureFileName = (string) Str::ulid() . '.' . $safeExtension;
        $destinationPath = 'documents/' . date('Y/m') . '/' . $secureFileName;

        if ($scanResult['passed']) {
            Storage::disk('private')->put($destinationPath, file_get_contents($this->tempFilePath));
            $scanStatus = 'passed';
        } else {
            // Quarantine malicious upload
            $destinationPath = 'quarantine/' . date('Y/m') . '/' . $secureFileName;
            Storage::disk('private')->put($destinationPath, file_get_contents($this->tempFilePath));
            $scanStatus = 'quarantined';
            Log::warning("File quarantined: {$this->originalFileName} reason: {$scanResult['reason']}");
        }

        // Create polymorphic attachment
        Attachment::create([
            'public_id' => (string) Str::ulid(),
            'attachable_type' => $this->attachableType,
            'attachable_id' => $this->attachableId,
            'file_path' => $destinationPath,
            'file_name' => basename($this->originalFileName),
            'mime_type' => $scanResult['mime'],
            'size_bytes' => $fileSize,
            'scan_status' => $scanStatus,
            'uploaded_at' => now(),
        ]);

        // Cleanup temporary file
        @unlink($this->tempFilePath);
    }
}
