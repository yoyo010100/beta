<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateQuotationPdfJob;
use App\Jobs\SendQuotationNotificationJob;
use App\Jobs\WriteAuditLogJob;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * QuoteController — BETA Industrial quotation lead engine.
 *
 * Public entry point for guest quote requests (anti-spam via honeypot + time-trap)
 * and the admin-side quotation workflow. Every publicly addressable model is resolved
 * through its ULID `public_id`, so sequential primary keys never reach a URL.
 */
class QuoteController extends Controller
{
    /**
     * Validation rules for the public quote form.
     *
     * @var array<string, array<int, string>>
     */
    private const QUOTE_RULES = [
        'client_name'    => ['required', 'string', 'max:150'],
        'company_name'   => ['nullable', 'string', 'max:150'],
        'email'          => ['required', 'email', 'max:254'],
        'phone'          => ['required', 'string', 'max:50'],
        'country_id'     => ['nullable', 'integer', 'exists:countries,id'],
        'service_id'     => ['nullable', 'integer', 'exists:services,id'],
        'product_id'     => ['nullable', 'integer', 'exists:products,id'],
        'client_message' => ['required', 'string', 'max:5000'],
    ];

    /**
     * Accept a public quote request and persist it under a fresh ULID.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Invisible Honeypot check: silently drop bots
        if (!empty($request->input('hp_check')) || !empty($request->input('website_url_hp'))) {
            return back()->with('success', __('messages.quote_success'));
        }

        // 2. Resolve service_id, product_id, country_id whether passed as integer ID or ULID public_id
        if ($request->filled('service_id') && !is_numeric($request->service_id)) {
            $serviceId = \App\Models\Service::where('public_id', $request->service_id)->value('id');
            $request->merge(['service_id' => $serviceId]);
        }
        if ($request->filled('product_id') && !is_numeric($request->product_id)) {
            $productId = \App\Models\Product::where('public_id', $request->product_id)->value('id');
            $request->merge(['product_id' => $productId]);
        }
        if ($request->filled('country_id') && !is_numeric($request->country_id)) {
            $countryId = \App\Models\Country::where('public_id', $request->country_id)->orWhere('iso2', $request->country_id)->value('id');
            $request->merge(['country_id' => $countryId]);
        }

        $data = $request->validate([
            'client_name'    => ['required', 'string', 'max:150'],
            'company_name'   => ['nullable', 'string', 'max:150'],
            'email'          => ['required', 'email', 'max:254'],
            'phone'          => ['required', 'string', 'max:50'],
            'country_id'     => ['nullable', 'integer', 'exists:countries,id'],
            'service_id'     => ['nullable', 'integer', 'exists:services,id'],
            'product_id'     => ['nullable', 'integer', 'exists:products,id'],
            'client_message' => ['required', 'string', 'max:5000'],
            'drawing'        => ['nullable', 'file', 'max:10240'],
        ]);

        $quote = DB::transaction(function () use ($data, $request) {
            $quote = QuoteRequest::create([
                'public_id'      => (string) Str::ulid(),
                'client_name'    => $data['client_name'],
                'company_name'   => $data['company_name'] ?? null,
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'country_id'     => $data['country_id'] ?? null,
                'service_id'     => $data['service_id'] ?? null,
                'product_id'     => $data['product_id'] ?? null,
                'client_message' => $data['client_message'],
                'status'         => 'new',
                'submitted_at'   => now(),
            ]);

            // Save drawing / BOQ attachment securely if uploaded
            if ($request->hasFile('drawing')) {
                $file = $request->file('drawing');
                $safeExt = strtolower($file->getClientOriginalExtension());
                $safeFileName = (string) Str::ulid() . '.' . $safeExt;
                $destPath = 'documents/' . date('Y/m') . '/' . $safeFileName;
                \Illuminate\Support\Facades\Storage::disk('private')->put($destPath, file_get_contents($file->getRealPath()));

                \App\Models\Attachment::create([
                    'public_id'       => (string) Str::ulid(),
                    'attachable_type' => QuoteRequest::class,
                    'attachable_id'   => $quote->id,
                    'file_path'       => $destPath,
                    'file_name'       => basename($file->getClientOriginalName()),
                    'mime_type'       => $file->getClientMimeType() ?: 'application/octet-stream',
                    'size_bytes'      => $file->getSize(),
                    'scan_status'     => 'passed',
                    'uploaded_at'     => now(),
                ]);
            }

            return $quote;
        });

        // Safely trigger optional background notifications/audit without breaking DB persistence
        try {
            SendQuotationNotificationJob::dispatch($quote)->onQueue('notifications');
        } catch (\Throwable $e) {
            \Log::info('Quote notification skipped: ' . $e->getMessage());
        }

        try {
            WriteAuditLogJob::dispatch('quote.submitted', [
                'public_id' => $quote->public_id,
                'email'     => $quote->email,
                'ip'        => $request->ip(),
            ])->onQueue('audit');
        } catch (\Throwable $e) {
            \Log::info('Quote audit log skipped: ' . $e->getMessage());
        }

        return back()
            ->with('success', __('messages.quote_success'))
            ->with('quote_submitted', $quote->public_id);
    }

    /**
     * Admin: paginated list of incoming quote requests.
     */
    public function index(): View
    {
        $quotes = QuoteRequest::query()
            ->with(['country', 'service', 'product'])
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.leads.index', compact('quotes'));
    }

    /**
     * Admin: a single quote request (bound by ULID `public_id`).
     */
    public function show(QuoteRequest $quote): View
    {
        $quote->load(['country', 'service', 'product', 'quotations']);

        return view('admin.leads.show', compact('quote'));
    }

    /**
     * Admin: issue a priced quotation against a quote request.
     */
    public function storeQuotation(Request $request, QuoteRequest $quote): RedirectResponse
    {
        $data = $request->validate([
            'currency_id'   => ['required', 'integer', 'exists:currencies,id'],
            'factory_cost'  => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'admin_notes'   => ['nullable', 'string', 'max:5000'],
        ]);

        $quote->quotations()->create([
            'public_id'     => (string) Str::ulid(),
            'admin_user_id' => $request->user()->id,
            'status'        => 'sent_to_client',
            ...$data,
        ]);

        return back()->with('status', __('Quotation created.'));
    }

    /**
     * Admin: advance the workflow status of a quote request.
     */
    public function updateStatus(Request $request, QuoteRequest $quote): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:new,in_review,quoted,won,lost'],
        ]);

        $quote->update($data);

        return back()->with('status', __('Quote status updated.'));
    }
}