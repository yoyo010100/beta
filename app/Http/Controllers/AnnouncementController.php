<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * The announcement types exposed by the admin CMS.
     *
     * @var array<int, string>
     */
    private const TYPES = ['news', 'press_release', 'event'];

    /**
     * Display the paginated, publicly visible announcements list.
     */
    public function index(Request $request): View
    {
        $activeType = $request->query('type');

        if (! is_string($activeType) || ! in_array($activeType, self::TYPES, true)) {
            $activeType = null;
        }

        $announcements = Announcement::published()
            ->with('attachments')
            ->when($activeType, fn ($query) => $query->where('type', $activeType))
            ->paginate(9)
            ->withQueryString();

        return view('frontend.announcements', [
            'announcements' => $announcements,
            'activeType' => $activeType,
            'types' => self::TYPES,
        ]);
    }

    /**
     * Display a single published announcement.
     */
    public function show(Announcement $announcement): View
    {
        abort_unless(
            $announcement->is_active
                && $announcement->published_at !== null
                && ! $announcement->published_at->isFuture(),
            404
        );

        $announcement->load('attachments');

        return view('frontend.announcement-detail', compact('announcement'));
    }
}
