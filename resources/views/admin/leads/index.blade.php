@extends('layouts.admin')
@section('title', 'Quotation Leads')
@section('page_title', 'Quotation Leads')

@section('content')
<div class="table-wrap">
    <div class="table-toolbar">
        <form style="display:flex;gap:10px;flex:1;flex-wrap:wrap" method="GET">
            <input name="search" placeholder="Search client, company, email…" value="{{ request('search') }}">
            <select name="status">
                <option value="">All Statuses</option>
                @foreach(['new','reviewing','quoted','accepted','rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="country">
                <option value="">All Countries</option>
                @foreach($countries as $c)
                    <option value="{{ $c->iso2 }}" {{ request('country') === $c->iso2 ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
        <div style="font-size:12px;color:var(--muted)">{{ $leads->total() }} total leads</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Company</th>
                <th>Country</th>
                <th>Service / Product</th>
                <th>Status</th>
                <th>Quotation</th>
                <th>Submitted</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($leads as $lead)
            <tr>
                <td>
                    <div style="font-weight:700">{{ $lead->client_name }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $lead->email }}</div>
                </td>
                <td style="color:var(--muted)">{{ $lead->company_name ?: '—' }}</td>
                <td>{{ $lead->country?->name ?? '—' }}</td>
                <td style="font-size:12px">
                    {{ $lead->service?->getTitle() ?? '' }}<br>
                    <span style="color:var(--muted)">{{ $lead->product?->getTitle() ?? '' }}</span>
                </td>
                <td><span class="badge badge-{{ $lead->status }}">{{ $lead->status }}</span></td>
                <td>
                    @if($lead->quotation)
                        <div style="font-size:12px;font-weight:700;color:var(--green)">
                            {{ $lead->quotation->currency->symbol ?? '' }}{{ number_format($lead->quotation->selling_price, 0) }}
                        </div>
                        <div style="font-size:10px;color:var(--muted)">
                            Profit: {{ number_format($lead->quotation->profit_amount, 0) }}
                        </div>
                    @else
                        <span style="font-size:11px;color:var(--muted)">No quotation yet</span>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--muted)">{{ $lead->submitted_at?->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('admin.leads.show', $lead->public_id) }}" class="btn btn-sun btn-sm">Open →</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No leads found matching your criteria.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($leads->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $leads->currentPage() }} of {{ $leads->lastPage() }}</span>
        {{ $leads->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
