<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnicalDownloadRequest;
use App\Models\TechnicalDownload;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TechnicalDownloadController extends Controller
{
    public function index()
    {
        $downloads = TechnicalDownload::orderBy('sort_order', 'asc')
            ->orderByDesc('issuance_date')
            ->paginate(15);
        return view('admin.technical-downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('admin.technical-downloads.form');
    }

    public function store(TechnicalDownloadRequest $request)
    {
        $validated = $request->validated();

        // Auto-assign sort_order: max + 1, defaulting to 1 when none exist.
        $nextSort = (int) TechnicalDownload::max('sort_order') + 1;
        if ($nextSort < 1) {
            $nextSort = 1;
        }

        $download = TechnicalDownload::create([
            'title_en' => $validated['title_en'],
            'title_ar' => $validated['title_ar'],
            'description_en' => $validated['description_en'] ?? '',
            'description_ar' => $validated['description_ar'] ?? '',
            'preview_image' => $this->storePreviewImage($request),
            'file_path' => $this->storeDownloadFile($request),
            'issuance_date' => $validated['issuance_date'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $nextSort,
        ]);

        AuditLogger::log('technical_download.created', $download, null, $download->toArray());

        return redirect()->route('admin.technical-downloads.index')
            ->with('success', 'Technical download created successfully.');
    }

    public function edit(TechnicalDownload $technical_download)
    {
        return view('admin.technical-downloads.form', compact('technical_download'));
    }

    public function update(TechnicalDownloadRequest $request, TechnicalDownload $technical_download)
    {
        $validated = $request->validated();
        $old = $technical_download->toArray();

        // Remove old preview image when a new one is uploaded.
        if ($request->hasFile('preview_image') && $technical_download->preview_image) {
            $oldPath = public_path($technical_download->preview_image);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // Remove old private file when a new one is uploaded.
        if ($request->hasFile('file') && $technical_download->file_path) {
            Storage::disk('private')->delete($technical_download->file_path);
        }

        $technical_download->update([
            'title_en' => $validated['title_en'],
            'title_ar' => $validated['title_ar'],
            'description_en' => $validated['description_en'] ?? '',
            'description_ar' => $validated['description_ar'] ?? '',
            'preview_image' => $request->hasFile('preview_image')
                ? $this->storePreviewImage($request)
                : $technical_download->preview_image,
            'file_path' => $request->hasFile('file')
                ? $this->storeDownloadFile($request)
                : $technical_download->file_path,
            'issuance_date' => $validated['issuance_date'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            // sort_order is managed via drag-and-drop reordering, not the form.
        ]);

        AuditLogger::log('technical_download.updated', $technical_download, $old, $technical_download->fresh()->toArray());

        return redirect()->route('admin.technical-downloads.index')
            ->with('success', 'Technical download updated.');
    }

    public function destroy(TechnicalDownload $technical_download)
    {
        // Remove files from storage before deleting the record.
        if ($technical_download->preview_image) {
            $oldPath = public_path($technical_download->preview_image);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
        if ($technical_download->file_path) {
            Storage::disk('private')->delete($technical_download->file_path);
        }

        AuditLogger::log('technical_download.deleted', $technical_download, $technical_download->toArray(), null);
        $technical_download->delete();

        return redirect()->route('admin.technical-downloads.index')
            ->with('success', 'Technical download deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:technical_downloads,id'],
        ]);

        // Re-index sequentially starting from 1 based on drag order.
        foreach ($request->order as $index => $id) {
            TechnicalDownload::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function move(Request $request, TechnicalDownload $technical_download)
    {
        $request->validate([
            'direction' => ['required', 'in:up,down'],
        ]);

        $direction = $request->direction;

        // Fetch all items ordered by sort_order ASC, id ASC, then re-index sequentially (1, 2, 3...).
        $items = TechnicalDownload::orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Re-index sequentially.
        $positionMap = [];
        $newSort = 1;
        foreach ($items as $item) {
            if ($item->sort_order !== $newSort) {
                $item->update(['sort_order' => $newSort]);
            }
            $positionMap[$item->id] = $newSort;
            $newSort++;
        }

        // Find the target item's current position.
        $currentPosition = $positionMap[$technical_download->id] ?? null;
        if ($currentPosition === null) {
            return response()->json(['success' => false, 'message' => 'Item not found.']);
        }

        $targetPosition = $direction === 'up' ? $currentPosition - 1 : $currentPosition + 1;
        $total = $items->count();

        if ($targetPosition < 1 || $targetPosition > $total) {
            return response()->json(['success' => false, 'message' => 'No neighbor to swap with.']);
        }

        // Find the neighbor at targetPosition.
        $neighbor = $items->firstWhere('sort_order', $targetPosition);
        if (!$neighbor) {
            return response()->json(['success' => false, 'message' => 'No neighbor to swap with.']);
        }

        // Swap sort_order values.
        $temp = $technical_download->sort_order;
        $technical_download->update(['sort_order' => $neighbor->sort_order]);
        $neighbor->update(['sort_order' => $temp]);

        return response()->json(['success' => true]);
    }

    /**
     * Serve the private downloadable file to authenticated admins.
     * Streams with a clean filename and security headers.
     * Falls back to the public disk for existing records stored there.
     */
    public function download(Request $request, TechnicalDownload $technical_download)
    {
        $filePath = $technical_download->file_path;

        if (!$filePath) {
            abort(404, 'The requested file could not be found.');
        }

        // Try private disk first, then public disk as fallback for legacy records.
        $disk = null;
        if (Storage::disk('private')->exists($filePath)) {
            $disk = 'private';
        } elseif (Storage::disk('public')->exists($filePath)) {
            $disk = 'public';
        }

        if (!$disk) {
            abort(404, 'The requested file could not be found in secure storage.');
        }

        AuditLogger::log('technical_download.downloaded', $technical_download, null, [
            'file_path' => $filePath,
            'disk' => $disk,
        ]);

        $fullPath = Storage::disk($disk)->path($filePath);
        $fileName = basename($filePath);
        $mime = \Illuminate\Support\Facades\File::mimeType($fullPath);

        return response()->download($fullPath, $fileName, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // ─── HELPERS ──────────────────────────────────────────────────────────────

    private function storePreviewImage(Request $request): ?string
    {
        if (!$request->hasFile('preview_image')) {
            return null;
        }

        $file = $request->file('preview_image');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::slug($request->input('title_en')) . '_' . time() . '.' . $ext;

        // Store directly under public/uploads/previews/ so asset() works without storage:link.
        $dir = public_path('uploads/previews');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);

        return 'uploads/previews/' . $filename;
    }

    private function storeDownloadFile(Request $request): ?string
    {
        if (!$request->hasFile('file')) {
            return null;
        }

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::slug($request->input('title_en')) . '_' . time() . '.' . $ext;

        return $file->storeAs('downloads/files', $filename, 'private');
    }
}
