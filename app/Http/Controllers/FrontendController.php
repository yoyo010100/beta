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
        $announcements = Announcement::published()
            ->with('attachments')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.announcements', [
            'announcements' => $announcements,
            'activeType' => null,
            'types' => ['news', 'press_release', 'event'],
        ]);
    }

    public function announcementShow(Announcement $announcement)
    {
        if (!$announcement->is_active || !$announcement->published_at || $announcement->published_at->isFuture()) {
            abort(404);
        }

        $announcement->load('attachments');

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

            // 2. Extension allow-list (mirrors JobApplicationRequest + FileValidationService):
            //    executable/script types can never be stored.
            $safeExt = strtolower($file->getClientOriginalExtension());
            if (!in_array($safeExt, ['pdf', 'doc', 'docx'], true)) {
                $safeExt = 'pdf';
            }

            // 1. Clean, ASCII-safe download filename: CV_<applicant-name-slug>_<timestamp>.ext
            //    Converts Arabic/special characters to an ASCII slug so downloaded CVs never
            //    carry corrupted names like "______-_________.pdf".
            $nameSlug = Str::slug($request->applicant_name ?: 'candidate');
            $nameSlug = $nameSlug ?: 'candidate';
            $fileName = 'CV_' . $nameSlug . '_' . time() . '.' . $safeExt;

            // 3. Store under a randomized ULID name on the 'private' disk
            //    (storage/app/private/cvs — outside the web root, not publicly reachable).
            $destinationPath = 'cvs/' . date('Y/m') . '/' . (string) Str::ulid() . '.' . $safeExt;
            \Illuminate\Support\Facades\Storage::disk('private')->putFileAs(
                dirname($destinationPath),
                $file,
                basename($destinationPath)
            );

            // 4. Server-side MIME detection (finfo) — never trust the client-supplied type.
            $mimeType = app(\App\Services\FileValidationService::class)->detectMime($file->getRealPath());

            // 5. Create Attachment record with scan_status = 'passed' (content validated
            //    by magic-byte checks in JobApplicationRequest) so admins can review/download it.
            \App\Models\Attachment::create([
                'public_id'       => (string) Str::ulid(),
                'attachable_type' => JobApplication::class,
                'attachable_id'   => $application->id,
                'file_path'       => $destinationPath,
                'file_name'       => $fileName,
                'mime_type'       => $mimeType,
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

        return redirect()->back()->with('success', __('messages.application_success'));
    }

    public function switchLocale(Request $request, string $locale)
    {
        if (!in_array($locale, \App\Http\Middleware\SetLocale::SUPPORTED, true)) {
            abort(400);
        }

        // One global switch: session for this visit + 1-year cookie so it is
        // remembered for every page on the next visit (read by SetLocale).
        $request->session()->put('locale', $locale);
        \Illuminate\Support\Facades\App::setLocale($locale);

        return redirect()->back()->cookie(cookie()->forever('locale', $locale));
    }
}
