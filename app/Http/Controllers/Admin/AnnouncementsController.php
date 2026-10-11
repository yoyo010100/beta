<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'content_ar' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'type' => ['required', 'in:news,press_release,event'],
            'slug' => ['required', 'string', 'max:200', 'unique:announcements,slug'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ]);

        $announcement = Announcement::create([
            'public_id' => (string) Str::ulid(),
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'excerpt' => ['ar' => $validated['excerpt_ar'] ?? '', 'en' => $validated['excerpt_en'] ?? ''],
            'content' => ['ar' => $validated['content_ar'], 'en' => $validated['content_en']],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'published_at' => $validated['published_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        Cache::forget('public.announcements.ar');
        Cache::forget('public.announcements.en');
        Cache::forget('public.news.latest');

        AuditLogger::log('announcement.created', $announcement, null, $announcement->toArray());

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'content_ar' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'type' => ['required', 'in:news,press_release,event'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ]);

        $old = $announcement->toArray();
        $announcement->update([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'excerpt' => ['ar' => $validated['excerpt_ar'] ?? '', 'en' => $validated['excerpt_en'] ?? ''],
            'content' => ['ar' => $validated['content_ar'], 'en' => $validated['content_en']],
            'type' => $validated['type'],
            'published_at' => $validated['published_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        Cache::forget('public.announcements.ar');
        Cache::forget('public.announcements.en');
        Cache::forget('public.news.latest');

        AuditLogger::log('announcement.updated', $announcement, $old, $announcement->fresh()->toArray());

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        AuditLogger::log('announcement.deleted', $announcement, $announcement->toArray(), null);
        $announcement->delete();
        Cache::forget('public.announcements.ar');
        Cache::forget('public.announcements.en');
        Cache::forget('public.news.latest');
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }
}
