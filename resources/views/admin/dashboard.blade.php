@extends('layouts.admin')
@section('title', 'Executive Dashboard')
@section('page_title', 'Executive Dashboard')

@section('content')

{{-- KPI METRICS --}}
<div class="metric-grid">
    <div class="metric-card">
        <h4>Pipeline Value</h4>
        <div class="value">{{ number_format($totalPipelineValue / 1000, 0) }}K</div>
        <div class="sub" style="color:var(--green)">EGP + SAR Combined</div>
    </div>
    <div class="metric-card">
        <h4>Active RFQs</h4>
        <div class="value">{{ $activeRfqs }}</div>
        <div class="sub" style="color:var(--muted)">Awaiting quotation</div>
    </div>
    <div class="metric-card">
        <h4>New Leads</h4>
        <div class="value" style="color:var(--ocean)">{{ $newLeads }}</div>
        <div class="sub" style="color:var(--muted)">Need first response</div>
    </div>
    <div class="metric-card">
        <h4>Open Positions</h4>
        <div class="value">{{ $openJobs }}</div>
        <div class="sub" style="color:var(--muted)">{{ $newApplicants }} new applicants</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">

    {{-- REVENUE CHART --}}
    <div class="chart-card">
        <h4>Monthly Revenue vs Profit (Last 6 Months)</h4>
        <div id="revenueChart"></div>
    </div>

    {{-- REGIONAL DEMAND --}}
    <div class="chart-card">
        <h4>Regional Demand Split</h4>
        <div id="regionalChart"></div>
        <div style="display:flex;gap:20px;justify-content:center;margin-top:16px;font-size:12px">
            <span style="display:flex;align-items:center;gap:6px"><span style="width:12px;height:12px;background:#003087;border-radius:50%;display:inline-block"></span>🇪🇬 Egypt ({{ $egyptCount }})</span>
            <span style="display:flex;align-items:center;gap:6px"><span style="width:12px;height:12px;background:#F2A900;border-radius:50%;display:inline-block"></span>🇸🇦 KSA ({{ $ksaCount }})</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:28px">

    {{-- LEAD FUNNEL --}}
    <div class="chart-card">
        <h4>Sales Lead Funnel</h4>
        <div id="funnelChart"></div>
    </div>

    {{-- HR FUNNEL --}}
    <div class="chart-card">
        <h4>HR Hiring Pipeline</h4>
        @foreach(['new'=>'New','reviewed'=>'Reviewed','interviewing'=>'Interview','offered'=>'Offered','hired'=>'Hired'] as $k=>$label)
        <div style="margin-bottom:14px">
            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px">
                <span>{{ $label }}</span><span style="font-weight:700">{{ $hrFunnel[$k] ?? 0 }}</span>
            </div>
            <div style="background:#eee;border-radius:2px;height:6px;overflow:hidden">
                <div style="height:100%;background:var(--ocean);width:{{ $hrFunnel->sum() > 0 ? (($hrFunnel[$k] ?? 0) / $hrFunnel->sum() * 100) : 0 }}%;border-radius:2px;transition:.5s"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- RECENT LEADS --}}
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">Recent Leads</h3>
        <a href="{{ route('admin.leads.index') }}" class="btn btn-outline btn-sm">View All →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Company</th>
                <th>Country</th>
                <th>Service</th>
                <th>Status</th>
                <th>Received</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentLeads as $lead)
            <tr>
                <td style="font-weight:700">{{ $lead->client_name }}</td>
                <td style="color:var(--muted)">{{ $lead->company_name ?: '—' }}</td>
                <td>{{ $lead->country?->name ?? '—' }}</td>
                <td>{{ $lead->service?->getTitle() ?? '—' }}</td>
                <td><span class="badge badge-{{ $lead->status }}">{{ $lead->status }}</span></td>
                <td style="color:var(--muted);font-size:12px">{{ $lead->submitted_at?->diffForHumans() }}</td>
                <td><a href="{{ route('admin.leads.show', $lead->public_id) }}" class="btn btn-outline btn-sm">Open</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:32px">No leads yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Revenue & Profit Chart
    const months = @json($monthlyData->pluck('month'));
    const revenue = @json($monthlyData->pluck('revenue'));
    const profit = @json($monthlyData->pluck('profit'));

    new ApexCharts(document.getElementById('revenueChart'), {
        chart: { type: 'area', height: 240, toolbar: { show: false }, fontFamily: 'DM Sans, sans-serif' },
        series: [
            { name: 'Revenue', data: revenue.map(Number) },
            { name: 'Profit', data: profit.map(Number) }
        ],
        xaxis: { categories: months, labels: { style: { fontSize: '11px' } } },
        colors: ['#003087', '#F2A900'],
        fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.0 } },
        stroke: { width: 2 },
        tooltip: { y: { formatter: v => 'EGP ' + Number(v).toLocaleString() } },
        grid: { borderColor: '#f0f0f0' },
        legend: { fontSize: '11px' }
    }).render();

    // Regional Donut
    new ApexCharts(document.getElementById('regionalChart'), {
        chart: { type: 'donut', height: 200, fontFamily: 'DM Sans, sans-serif' },
        series: [{{ $egyptCount }}, {{ $ksaCount }}],
        labels: ['Egypt', 'Saudi Arabia'],
        colors: ['#003087', '#F2A900'],
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: { pie: { donut: { size: '70%' } } }
    }).render();

    // Lead Funnel Bar
    new ApexCharts(document.getElementById('funnelChart'), {
        chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'DM Sans, sans-serif' },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        series: [{ name: 'Leads', data: Object.values(@json($funnelData)) }],
        xaxis: { categories: Object.keys(@json($funnelData)).map(s => s.charAt(0).toUpperCase() + s.slice(1)) },
        colors: ['#003087'],
        dataLabels: { style: { fontSize: '11px' } },
        grid: { borderColor: '#f0f0f0' },
        tooltip: { y: { formatter: v => v + ' leads' } }
    }).render();
});
</script>
@endpush
