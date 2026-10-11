<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Quotation;
use App\Models\QuoteRequest;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuotationLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = QuoteRequest::with(['country', 'service', 'product', 'quotation']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('country')) {
            $query->whereHas('country', fn($q) => $q->where('iso2', $request->country));
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $leads = $query->latest()->paginate(20);
        $currencies = Currency::where('is_active', true)->get();
        $countries = Country::where('is_active', true)->get();

        return view('admin.leads.index', compact('leads', 'currencies', 'countries'));
    }

    public function show(QuoteRequest $lead)
    {
        $lead->load(['country', 'service', 'product', 'quotation.currency', 'attachments']);
        $currencies = Currency::where('is_active', true)->get();
        return view('admin.leads.show', compact('lead', 'currencies'));
    }

    public function storeQuotation(Request $request, QuoteRequest $lead)
    {
        $validated = $request->validate([
            'currency_id' => ['required', 'exists:currencies,id'],
            'factory_cost' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldQuotation = $lead->quotation ? $lead->quotation->toArray() : null;

        $quotation = Quotation::updateOrCreate(
            ['quote_request_id' => $lead->id],
            [
                'public_id' => $lead->quotation?->public_id ?? (string) Str::ulid(),
                'admin_user_id' => Auth::id(),
                'currency_id' => $validated['currency_id'],
                'factory_cost' => $validated['factory_cost'],
                'selling_price' => $validated['selling_price'],
                'admin_notes' => $validated['admin_notes'],
                'status' => 'sent_to_client',
            ]
        );

        $lead->update(['status' => 'quoted']);

        AuditLogger::log(
            'quotation.created_or_updated',
            $quotation,
            $oldQuotation,
            ['factory_cost' => $validated['factory_cost'], 'selling_price' => $validated['selling_price'], 'profit_amount' => $validated['selling_price'] - $validated['factory_cost']]
        );

        return redirect()->route('admin.leads.show', $lead->public_id)
            ->with('success', 'Quotation saved. Profit: ' . number_format($validated['selling_price'] - $validated['factory_cost'], 2));
    }

    public function updateStatus(Request $request, QuoteRequest $lead)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,reviewing,quoted,accepted,rejected'],
        ]);

        $old = $lead->only(['status']);
        $lead->update(['status' => $validated['status']]);

        AuditLogger::log('lead.status_changed', $lead, $old, ['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Lead status updated to: ' . ucfirst($validated['status']));
    }
}
