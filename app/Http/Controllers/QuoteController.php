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
 * Public entry point for guest quote requests (math CAPTCHA enforced) and the
 * admin-side quotation workflow. Every publicly addressable model is resolved
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
        'captcha_answer' => ['required', 'string', 'max:16'],
    ];

    /**
     * Accept a public quote request and persist it under a fresh ULID.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->enforceCaptcha($request);

        $data = $request->validate(self::QUOTE_RULES);
        unset($data['captcha_answer']);

        $quote = DB::transaction(fn () => QuoteRequest::create([
            'public_id' => (string) Str::ulid(),
            'status'    => 'new',
            ...$data,
        ]));

        // Everything heavy (mail, PDF, audit) is queued on Redis.
        SendQuotationNotificationJob::dispatch($quote)
            ->onConnection('redis')->onQueue('notifications');

        GenerateQuotationPdfJob::dispatch($quote)
            ->onConnection('redis')->onQueue('media');

        WriteAuditLogJob::dispatch('quote.submitted', [
            'public_id' => $quote->public_id,
            'email'     => $quote->email,
            'ip'        => $request->ip(),
        ])->onConnection('redis')->onQueue('audit');

        return back()->with('quote_submitted', $quote->public_id);
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

    /**
     * Verify the submitted math CAPTCHA against the pending challenge.
     *
     * The expected answer is stored in the session when the contact form is
     * rendered; a correct submission consumes it (single use).
     *
     * @throws ValidationException
     */
    private function enforceCaptcha(Request $request): void
    {
        $expected = $request->session()->pull('captcha_answer');
        $given    = trim((string) $request->input('captcha_answer'));

        if ($expected === null || $given === '' || ! hash_equals((string) $expected, $given)) {
            throw ValidationException::withMessages([
                'captcha_answer' => __('Incorrect answer, please solve the challenge again.'),
            ]);
        }
    }
}
