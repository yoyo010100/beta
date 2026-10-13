<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplicationRatingRequest;
use App\Http\Requests\Admin\ApplicationStatusRequest;
use App\Http\Requests\Admin\JobPostingRequest;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class CareersController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with(['jobPosting.country'])->latest('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        $applications = $query->paginate(20);
        $jobPostings = JobPosting::where('is_active', true)->get();

        // HR Hiring Funnel counts
        $funnelCounts = [
            'new' => JobApplication::where('status', 'new')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'interviewing' => JobApplication::where('status', 'interviewing')->count(),
            'offered' => JobApplication::where('status', 'offered')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        return view('admin.careers.index', compact('applications', 'jobPostings', 'funnelCounts'));
    }

    public function show(JobApplication $application)
    {
        $application->load(['jobPosting.country', 'attachments']);
        return view('admin.careers.show', compact('application'));
    }

    public function updateRating(ApplicationRatingRequest $request, JobApplication $application)
    {
        $validated = $request->validated();

        $old = $application->only(['rating', 'status']);
        $application->update(['rating' => $validated['rating']]);

        AuditLogger::log('application.rating_updated', $application, $old, ['rating' => $validated['rating']]);

        return response()->json(['success' => true, 'rating' => $validated['rating']]);
    }

    public function updateStatus(ApplicationStatusRequest $request, JobApplication $application)
    {
        $validated = $request->validated();

        $old = $application->only(['status']);
        $application->update(['status' => $validated['status']]);

        AuditLogger::log('application.status_changed', $application, $old, ['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Candidate status updated to: ' . ucfirst($validated['status']));
    }

    // ─── JOB POSTINGS ─────────────────────────────────────────────────────────

    public function postingsIndex()
    {
        $postings = JobPosting::with('country')->latest()->paginate(15);
        $countries = \App\Models\Country::where('is_active', true)->get();
        return view('admin.careers.postings', compact('postings', 'countries'));
    }

    public function postingStore(JobPostingRequest $request)
    {
        $validated = $request->validated();

        $posting = JobPosting::create([
            'public_id' => (string) \Illuminate\Support\Str::ulid(),
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'], 'en' => $validated['description_en']],
            'country_id' => $validated['country_id'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        \Illuminate\Support\Facades\Cache::forget('public.jobs.ar');
        \Illuminate\Support\Facades\Cache::forget('public.jobs.en');

        return redirect()->route('admin.careers.postings')->with('success', 'Job posting created.');
    }

    public function postingToggle(JobPosting $posting)
    {
        $posting->update(['is_active' => !$posting->is_active]);
        return redirect()->back()->with('success', 'Job posting status toggled.');
    }
}
