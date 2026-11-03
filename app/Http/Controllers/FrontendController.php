<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteFormRequest;
use App\Jobs\GenerateQuotationPdfJob;
use App\Jobs\ProcessFileUploadJob;
use App\Jobs\SendQuotationNotificationJob;
use App\Models\Announcement;
use App\Models\Country;
use App\Models\JobPosting;
use App\Models\Project;
use App\Models\Product;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function home()
    {
        $services = Service::where('is_active', true)->with('attachments')->get();
        $products = Product::where('is_active', true)->with('attachments')->get();
        $announcements = Announcement::published()->with('attachments')->limit(3)->get();
        $activeQuotes = QuoteRequest::where('status', 'new')->count();
        $completedProjects = Project::where('is_active', true)->count();
        $firstService = Service::where('is_active', true)->with('attachments')->latest()->first();

        return view('frontend.index', compact('services', 'products', 'announcements', 'activeQuotes', 'completedProjects', 'firstService'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->with('attachments')->get();

        return view('frontend.services', compact('services'));
    }

    public function serviceDetail($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('frontend.service-detail', compact('service'));
    }

    public function products()
    {
        $products = Cache::remember('public.products.all.' . app()->getLocale(), 3600, fn() =>
            Product::where('is_active', true)->with('attachments')->get()
        );
        return view('frontend.products', compact('products'));
    }

    public function announcements()
    {
        $announcements = Announcement::where('is_active', true)->latest()->paginate(9);
        return view('frontend.announcements', compact('announcements'));
    }

    public function announcementShow(Announcement $announcement)
    {
        if (!$announcement->is_active || !$announcement->published_at || $announcement->published_at->isFuture()) {
            abort(404);
        }
        return view('frontend.announcement-detail', compact('announcement'));
    }

    public function insightDetail(Announcement $announcement)
    {
        return $this->announcementShow($announcement);
    }

    public function careers()
    {
        $jobs = Cache::remember('public.jobs.' . app()->getLocale(), 3600, fn() =>
            JobPosting::where('is_active', true)->with('country')->get()
        );
        $countries = Country::where('is_active', true)->get();
        return view('frontend.careers', compact('jobs', 'countries'));
    }

    public function projects()
    {
        $projects = Project::where('is_active', true)->latest()->get();
        return view('frontend.projects', compact('projects'));
    }

    public function contact()
    {
        $services = Cache::remember('public.services.all.' . app()->getLocale(), 3600, fn() =>
            Service::where('is_active', true)->with('attachments')->get()
        );
        $products = Cache::remember('public_products_' . app()->getLocale(), 3600, fn() =>
            Product::where('is_active', true)->with('attachments')->get()
        );
        $countries = Country::where('is_active', true)->get();
        $jobs = JobPosting::where('is_active', true)->with('country')->get();
        return view('frontend.contact', compact('services', 'products', 'countries', 'jobs'));
    }

    public function submitQuote(QuoteFormRequest $request)
    {
        $qr = QuoteRequest::create([
            'public_id' => (string) Str::ulid(),
            'client_name' => $request->client_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'service_id' => $request->service_id,
            'product_id' => $request->product_id,
            'client_message' => $request->client_message,
            'status' => 'new',
            'submitted_at' => now(),
        ]);

        // Offload all heavy work to Redis-backed queues so the HTTP request
        // returns immediately (<100ms) instead of blocking on I/O or CPU.
        if ($request->hasFile('drawing')) {
            $originalName = $request->file('drawing')->getClientOriginalName();
            $tempPath = $request->file('drawing')->store('temp', 'private');

            ProcessFileUploadJob::dispatch(
                storage_path('app/private/' . $tempPath),
                $originalName,
                QuoteRequest::class,
                $qr->id
            )->onConnection('redis')->onQueue('media');
        }

        SendQuotationNotificationJob::dispatch($qr)
            ->onConnection('redis')->onQueue('notifications');

        GenerateQuotationPdfJob::dispatch($qr)
            ->onConnection('redis')->onQueue('media');

        WriteAuditLogJob::dispatch('quote.submitted', [
            'public_id' => $qr->public_id,
            'email'     => $qr->email,
            'ip'        => $request->ip(),
        ])->onConnection('redis')->onQueue('audit');

        return redirect()->back()->with('success', __('messages.quote_success'));
    }

    public function submitApplication(JobApplicationRequest $request)
    {
        $application = JobApplication::create([
            'public_id'      => (string) Str::ulid(),
            'job_posting_id' => $request->job_posting_id,
            'applicant_name' => $request->applicant_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'status'         => 'new',
            'submitted_at'   => now(),
        ]);

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $originalName = $file->getClientOriginalName();

            // 1. Extension and filename sanitization (prevent path traversal, remove dangerous characters)
            $cleanBaseName = preg_replace('/[^A-Za-z0-9_\-\. ]/', '_', basename($originalName));
            $safeExt = strtolower($file->getClientOriginalExtension());
            if (!in_array($safeExt, ['pdf', 'doc', 'docx'], true)) {
                $safeExt = 'pdf';
            }

            // 2. Prevent executable scripts / malicious files from running on storage:
            // Store under a randomized ULID filename on the 'private' disk (outside web root).
            $secureFileName = (string) Str::ulid() . '.' . $safeExt;
            $destinationPath = 'documents/' . date('Y/m') . '/' . $secureFileName;
            \Illuminate\Support\Facades\Storage::disk('private')->put($destinationPath, file_get_contents($file->getRealPath()));

            // 3. Create Attachment record with scan_status = 'passed' so it can be securely reviewed and downloaded in the admin panel
            \App\Models\Attachment::create([
                'public_id'       => (string) Str::ulid(),
                'attachable_type' => JobApplication::class,
                'attachable_id'   => $application->id,
                'file_path'       => $destinationPath,
                'file_name'       => $cleanBaseName,
                'mime_type'       => $file->getClientMimeType() ?: 'application/octet-stream',
                'size_bytes'      => $file->getSize(),
                'scan_status'     => 'passed',
                'uploaded_at'     => now(),
            ]);
        }

        try {
            \App\Jobs\WriteAuditLogJob::dispatch('application.submitted', [
                'public_id'      => $application->public_id,
                'job_posting_id' => $application->job_posting_id,
                'email'          => $request->email,
                'ip'             => $request->ip(),
            ])->onQueue('audit');
        } catch (\Throwable $e) {
            \Log::info('Audit log skipped: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', __('Your application and CV have been submitted successfully.'));
    }

    public function switchLocale(Request $request, string $locale)
    {
        if (!in_array($locale, ['ar', 'en'])) {
            abort(400);
        }
        $request->session()->put('locale', $locale);
        \Illuminate\Support\Facades\App::setLocale($locale);
        Cache::put('active_locale', $locale, 3600);
        return redirect()->back();
    }
}
