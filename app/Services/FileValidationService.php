<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * FileValidationService — server-side upload gate.
 *
 * Enforces an extension allow-list per upload profile, rejects executable
 * payloads, then cross-checks the real MIME type (finfo) against the declared
 * extension and a magic-byte signature. Callers must store the file on the
 * `private` disk (storage/app/private) only after validate()/assertValid() pass.
 */
class FileValidationService
{
    public const PROFILE_CV = 'cv';

    public const PROFILE_BOQ = 'boq';

    public const PROFILE_DRAWING = 'drawing';

    public const PROFILE_ATTACHMENT = 'attachment';

    /** Extension allow-list per upload profile. */
    private const PROFILES = [
        self::PROFILE_CV => ['pdf'],
        self::PROFILE_BOQ => ['pdf', 'dwg', 'dxf', 'jpg', 'jpeg', 'png'],
        self::PROFILE_DRAWING => ['pdf', 'dwg', 'dxf', 'jpg', 'jpeg', 'png'],
        self::PROFILE_ATTACHMENT => ['pdf', 'dwg', 'dxf', 'jpg', 'jpeg', 'png', 'webp'],
    ];

    /** Canonical MIME types accepted per extension. */
    private const MIMES = [
        'pdf' => ['application/pdf'],
        'dwg' => ['image/vnd.dwg', 'image/x-dwg', 'application/acad', 'application/x-acad', 'application/dwg', 'application/x-dwg', 'application/octet-stream'],
        'dxf' => ['image/vnd.dxf', 'application/dxf', 'application/x-dxf', 'text/plain', 'application/octet-stream'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'webp' => ['image/webp'],
    ];

    /** Magic-byte signatures (binary vs. text headers handled in magicMatches()). */
    private const MAGIC_BYTES = [
        'pdf' => ['%PDF-'],
        'png' => ["\x89PNG\r\n\x1a\n"],
        'jpg' => ["\xFF\xD8\xFF"],
        'jpeg' => ["\xFF\xD8\xFF"],
        'dwg' => ['AC10'],
        'dxf' => ['SECTION', 'EOF'],
    ];

    /** Executable / script entrypoints — rejected regardless of extension. */
    private const EXECUTABLE_MAGIC = ['MZ', "\x7FELF", "\xCA\xFE\xBA\xBE", "\xCF\xFA\xED\xFE", '#!/'];

    /**
     * @return array{ok: bool, reason: string|null, mime: string, extension: string}
     */
    public function validate(UploadedFile $file, string $profile): array
    {
        return $this->validatePath(
            (string) $file->getRealPath(),
            $file->getClientOriginalName(),
            $profile
        );
    }

    /**
     * Validate a file on disk (used by queued jobs that only hold a path).
     *
     * @return array{ok: bool, reason: string|null, mime: string, extension: string}
     */
    public function validatePath(string $path, string $originalName, string $profile): array
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (! in_array($extension, self::PROFILES[$profile] ?? [], true)) {
            return $this->result(false, "File type .{$extension} is not permitted.", 'application/octet-stream', $extension);
        }

        if (! is_file($path) || ! is_readable($path)) {
            return $this->result(false, 'Uploaded file could not be read.', 'unknown', $extension);
        }

        $header = (string) file_get_contents($path, false, null, 0, 1024);
        $mime = $this->detectMime($path);

        foreach (self::EXECUTABLE_MAGIC as $signature) {
            if (str_starts_with($header, $signature)) {
                return $this->result(false, 'Executable payload detected.', $mime, $extension);
            }
        }

        if (! $this->magicMatches($extension, $header)) {
            return $this->result(false, "File content does not match the expected .{$extension} signature.", $mime, $extension);
        }

        $allowedMimes = self::MIMES[$extension] ?? [];

        if ($allowedMimes !== [] && ! in_array($mime, $allowedMimes, true)) {
            return $this->result(false, "Detected MIME [{$mime}] does not match the .{$extension} extension.", $mime, $extension);
        }

        return $this->result(true, null, $mime, $extension);
    }

    /**
     * @throws ValidationException
     */
    public function assertValid(UploadedFile $file, string $profile, string $field): void
    {
        $result = $this->validate($file, $profile);

        if (! $result['ok']) {
            throw ValidationException::withMessages([
                $field => $result['reason'] ?? 'The uploaded file is not valid.',
            ]);
        }
    }

    /**
     * Persist an upload to private storage (outside the web root).
     */
    public function storePrivately(UploadedFile $file, string $directory = 'documents'): string
    {
        return (string) $file->store($directory . '/' . date('Y/m'), 'private');
    }

    /**
     * @return array<int, string>
     */
    public function extensionsFor(string $profile): array
    {
        return self::PROFILES[$profile] ?? [];
    }

    public function detectMime(string $path): string
    {
        if (! function_exists('finfo_open')) {
            return 'application/octet-stream';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            return 'application/octet-stream';
        }

        $mime = (string) finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mime !== '' ? $mime : 'application/octet-stream';
    }

    private function magicMatches(string $extension, string $header): bool
    {
        if ($extension === 'webp') {
            return str_starts_with($header, 'RIFF') && substr($header, 8, 4) === 'WEBP';
        }

        $signatures = self::MAGIC_BYTES[$extension] ?? null;

        if ($signatures === null) {
            return true;
        }

        // PDF/DXF carry text signatures that may sit slightly after byte 0.
        $textual = in_array($extension, ['pdf', 'dxf'], true);

        foreach ($signatures as $signature) {
            if ($textual ? str_contains($header, $signature) : str_starts_with($header, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{ok: bool, reason: string|null, mime: string, extension: string}
     */
    private function result(bool $ok, ?string $reason, string $mime, string $extension): array
    {
        return ['ok' => $ok, 'reason' => $reason, 'mime' => $mime, 'extension' => $extension];
    }
}
