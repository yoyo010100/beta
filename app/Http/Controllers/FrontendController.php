<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteFormRequest;
use App\Jobs\GenerateQuotationPdfJob;
use App\Jobs\ProcessFileUploadJob;
use App\Jobs\SendQuotationNotificationJob;
use App\Jobs\WriteAuditLogJob;
use App\Models\Announcement;
use App\Models\Country;
use App\Models\JobPosting;
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
        $services = Cache::remember('public.services.' . app()->getLocale(), 3600, fn() =>
            Service::where('is_active', true)->get()
        );
        $latestNews = Cache::remember('public.news.latest.' . app()->getLocale(), 3600, fn() =>
            Announcement::published()->limit(3)->get()
        );
        $products = Product::where('is_active', true)->get();
        return view('frontend.index', compact('services', 'products', 'latestNews'));
    }

    public function services()
    {
        $services = Cache::remember('public.services.all.' . app()->getLocale(), 3600, fn() =>
            Service::where('is_active', true)->get()
        );
        return view('frontend.services', compact('services'));
    }

    public function products()
    {
        $products = Cache::remember('public.products.all.' . app()->getLocale(), 3600, fn() =>
            Product::where('is_active', true)->get()
        );
        return view('frontend.products', compact('products'));
    }

    public function announcements()
    {
        $announcements = Cache::remember('public.announcements.' . app()->getLocale(), 3600, fn() =>
            Announcement::published()->paginate(12)
        );
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
        $this->generateMathCaptcha(request());
        return view('frontend.careers', compact('jobs', 'countries'));
    }

    public function contact()
    {
        $services = Service::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $countries = Country::where('is_active', true)->get();
        $this->generateMathCaptcha(request());
        $jobs = JobPosting::where('is_active', true)->get();
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
            'public_id' => (string) Str::ulid(),
            'job_posting_id' => $request->job_posting_id,
            'applicant_name' => $request->applicant_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'new',
            'submitted_at' => now(),
        ]);

        if ($request->hasFile('cv')) {
            $originalName = $request->file('cv')->getClientOriginalName();
            $tempPath = $request->file('cv')->store('temp', 'private');

            ProcessFileUploadJob::dispatch(
                storage_path('app/private/' . $tempPath),
                $originalName,
                JobApplication::class,
                $application->id
            )->onConnection('redis')->onQueue('media');
        }

        WriteAuditLogJob::dispatch('application.submitted', [
            'public_id'       => $application->public_id,
            'job_posting_id'  => $application->job_posting_id,
            'email'           => $application->email,
            'ip'              => $request->ip(),
        ])->onConnection('redis')->onQueue('audit');

        return redirect()->back()->with('success', __('messages.application_success'));
    }

    public function switchLocale(Request $request, string $locale)
    {
        if (!in_array($locale, ['ar', 'en'])) {
            abort(400);
        }
        $request->session()->put('locale', $locale);
        \Illuminate\Support\Facades\App::setLocale($locale);
        return redirect()->back();
    }

    protected function generateMathCaptcha(Request $request): void
    {
        $a = rand(2, 12);
        $b = rand(1, 9);
        $request->session()->put('math_captcha_a', $a);
        $request->session()->put('math_captcha_b', $b);
        $request->session()->put('math_captcha_result', $a + $b);
    }
}
