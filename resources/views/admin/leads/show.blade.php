@extends('layouts.admin')
@section('title', 'Lead: ' . $lead->client_name)
@section('page_title', 'Lead Detail — ' . $lead->client_name)

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <div>
        <a href="{{ route('admin.leads.index') }}" style="font-size:12px;color:var(--muted)">← Back to Leads</a>
        <h1 style="font-size:22px;font-weight:800;margin-top:4px">{{ $lead->client_name }}</h1>
        <div style="font-size:13px;color:var(--muted)">{{ $lead->company_name }} &bull; {{ $lead->email }} &bull; {{ $lead->phone }}</div>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <span class="badge badge-{{ $lead->status }}" style="font-size:13px;padding:8px 16px">{{ ucfirst($lead->status) }}</span>
        <form action="{{ route('admin.leads.status.update', $lead->public_id) }}" method="POST" style="display:flex;gap:8px">
            @csrf @method('PATCH')
            <select name="status" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px">
                @foreach(['new','reviewing','quoted','accepted','rejected'] as $s)
                <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Update</button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">

    {{-- LEFT: Lead Info + Message --}}
    <div>
        <div class="form-card">
            <h3>Request Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13px">
                <div><div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Service</div><strong>{{ $lead->service?->getTitle() ?? '—' }}</strong></div>
                <div><div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Product</div><strong>{{ $lead->product?->getTitle() ?? '—' }}</strong></div>
                <div><div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Country</div><strong>{{ $lead->country?->name ?? '—' }}</strong></div>
                <div><div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Submitted</div><strong>{{ $lead->submitted_at?->format('M d, Y H:i') }}</strong></div>
            </div>
            @if($lead->client_message)
            <div style="margin-top:20px;padding:16px;background:#f8fafc;border-radius:6px;border-left:3px solid var(--sun);font-size:14px;line-height:1.7;color:var(--charcoal)">
                {{ $lead->client_message }}
            </div>
            @endif
        </div>

        {{-- Attachments --}}
        @if($lead->attachments->isNotEmpty())
        <div class="form-card">
            <h3>Attachments</h3>
            @foreach($lead->attachments as $att)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--line);border-radius:6px;margin-bottom:8px">
                <div>
                    <div style="font-size:13px;font-weight:700">{{ $att->file_name }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ number_format($att->file_size / 1024, 0) }} KB &bull; <span class="badge badge-{{ $att->scan_status }}">{{ $att->scan_status }}</span></div>
                </div>
                @if($att->scan_status === 'passed')
                <button onclick="getSignedUrl('{{ $att->public_id }}')" class="btn btn-outline btn-sm">⬇ Download</button>
                @else
                <span style="font-size:11px;color:var(--red)">⚠ Quarantined</span>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- RIGHT: Quotation Engine --}}
    <div>
        <div class="form-card" style="position:sticky;top:96px">
            <h3>{{ $lead->quotation ? 'Update Quotation' : 'Create Quotation' }}</h3>
            <form action="{{ route('admin.leads.quotation.store', $lead->public_id) }}" method="POST" id="quotationForm">
                @csrf
                <div class="form-group" style="margin-bottom:14px">
                    <label>Currency</label>
                    <select name="currency_id" id="currencySelect" required>
                        @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}"
                            data-symbol="{{ $currency->symbol }}"
                            {{ $lead->quotation?->currency_id === $currency->id ? 'selected' : '' }}>
                            {{ $currency->code }} — {{ $currency->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:14px">
                    <label>Factory Cost (Internal)</label>
                    <input type="number" name="factory_cost" id="factoryCost" step="0.01" min="0" placeholder="0.00" value="{{ $lead->quotation?->factory_cost }}" required oninput="calcProfit()">
                </div>
                <div class="form-group" style="margin-bottom:14px">
                    <label>Selling Price (Client Sees)</label>
                    <input type="number" name="selling_price" id="sellingPrice" step="0.01" min="0" placeholder="0.00" value="{{ $lead->quotation?->selling_price }}" required oninput="calcProfit()">
                </div>

                {{-- Live Profit Indicator --}}
                <div class="profit-display" id="profitDisplay">
                    <div class="profit-value" id="profitValue">
                        <span id="currencySymbol">{{ $currencies->firstWhere('id', $lead->quotation?->currency_id)?->symbol ?? '' }}</span>0
                    </div>
                    <small>Gross Profit</small>
                    <div style="font-size:13px;font-weight:700;margin-top:4px;color:#276749" id="marginDisplay">— margin</div>
                </div>

                <div class="form-group" style="margin-top:14px;margin-bottom:16px">
                    <label>Internal Notes</label>
                    <textarea name="admin_notes" rows="3" placeholder="Notes visible only to admin…">{{ $lead->quotation?->admin_notes }}</textarea>
                </div>
                <button type="submit" class="btn btn-sun" style="width:100%;justify-content:center">
                    {{ $lead->quotation ? '💾 Update Quotation' : '✅ Create Quotation' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcProfit() {
    const cost = parseFloat(document.getElementById('factoryCost').value) || 0;
    const sell = parseFloat(document.getElementById('sellingPrice').value) || 0;
    const profit = sell - cost;
    const margin = sell > 0 ? ((profit / sell) * 100).toFixed(1) : 0;
    const sym = document.getElementById('currencySelect').selectedOptions[0]?.dataset?.symbol || '';
    document.getElementById('currencySymbol').textContent = sym;
    document.getElementById('profitValue').childNodes[1].textContent = profit.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    document.getElementById('marginDisplay').textContent = margin + '% margin';
    document.getElementById('profitDisplay').style.background = profit >= 0 ? 'linear-gradient(135deg,#f0fff4,#e6fffa)' : 'linear-gradient(135deg,#fff5f5,#fed7d7)';
    document.getElementById('profitDisplay').style.borderColor = profit >= 0 ? '#38a169' : '#e53e3e';
}

document.getElementById('currencySelect')?.addEventListener('change', calcProfit);
calcProfit();

function getSignedUrl(attachmentId) {
    fetch(`/admin/attachments/${attachmentId}/signed-url`)
        .then(r => r.json())
        .then(d => { if (d.url) window.open(d.url, '_blank'); });
}
</script>
@endpush
