<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnnouncementRequest;
use App\Http\Requests\Admin\AttachmentUploadRequest;
use App\Jobs\ProcessFileUploadJob;
use App\Models\Announcement;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest('published_at')->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.form');
    }

    public function store(AnnouncementRequest $request)
    {
        $validated = $request->validated();

        $announcement = Announcement::create([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'excerpt' => ['ar' => $validated['excerpt_ar'] ?? '', 'en' => $validated['excerpt_en'] ?? ''],
            'content' => ['ar' => $validated['content_ar'], 'en' => $validated['content_en']],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'published_at' => $validated['published_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $announcement);
        $this->flushAnnouncementCache();

        AuditLogger::log('announcement.created', $announcement, null, $announcement->toArray());

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $announcement->load('attachments');
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $validated = $request->validated();

        $old = $announcement->toArray();
        $announcement->update([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'excerpt' => ['ar' => $validated['excerpt_ar'] ?? '', 'en' => $validated['excerpt_en'] ?? ''],
            'content' => ['ar' => $validated['content_ar'], 'en' => $validated['content_en']],
            'type' => $validated['type'],
            'published_at' => $validated['published_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $announcement);
        $this->flushAnnouncementCache();

        AuditLogger::log('announcement.updated', $announcement, $old, $announcement->fresh()->toArray());

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        AuditLogger::log('announcement.deleted', $announcement, $announcement->toArray(), null);
        $announcement->delete();
        $this->flushAnnouncementCache();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }

    public function attachmentStore(AttachmentUploadRequest $request, Announcement $announcement)
    {
        $this->queueAttachment($request, $announcement);
        return redirect()->back()->with('success', 'Attachment upload queued for scanning.');
    }

    // ─── HELPERS ──────────────────────────────────────────────────────────────

    private function queueAttachment(Request $request, Announcement $announcement): void
    {
        if (!$request->hasFile('attachment')) {
            return;
        }

        $file = $request->file('attachment');
        $tempPath = $file->store('temp', 'private');

        ProcessFileUploadJob::dispatch(
            storage_path('app/private/' . $tempPath),
            $file->getClientOriginalName(),
            Announcement::class,
            $announcement->getKey()
        );
    }

    private function flushAnnouncementCache(): void
    {
        Cache::forget('public.announcements.ar');
        Cache::forget('public.announcements.en');
        Cache::forget('public.news.latest');
    }
}
