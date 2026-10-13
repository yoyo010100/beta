@extends('layouts.admin')
@section('title', 'Job Applications')
@section('page_title', 'Careers ATS — Applications')

@section('content')
{{-- HR HIRING FUNNEL --}}
<div class="metric-grid">
    @foreach(['new' => 'New', 'reviewed' => 'Reviewed', 'interviewing' => 'Interviewing', 'offered' => 'Offered', 'hired' => 'Hired'] as $key => $label)
    <div class="metric-card">
        <h4>{{ $label }}</h4>
        <div class="value">{{ $funnelCounts[$key] ?? 0 }}</div>
        <div class="sub" style="color:var(--muted)">candidates</div>
    </div>
    @endforeach
</div>

<div class="table-wrap">
    <div class="table-toolbar">
        <form style="display:flex;gap:10px;flex:1;flex-wrap:wrap" method="GET">
            <select name="status">
                <option value="">All Statuses</option>
                @foreach(['new','reviewed','interviewing','offered','hired','rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="job_id">
                <option value="">All Positions</option>
                @foreach($jobPostings as $posting)
                    <option value="{{ $posting->id }}" {{ request('job_id') == $posting->id ? 'selected' : '' }}>{{ $posting->getTitle() }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.careers.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
        <div style="font-size:12px;color:var(--muted)">{{ $applications->total() }} total applications</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Candidate</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Country</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Applied</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr>
                <td>
                    <div style="font-weight:700">{{ $application->applicant_name }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $application->email }}</div>
                </td>
                <td style="font-size:12px;color:var(--charcoal)">{{ $application->phone }}</td>
                <td style="font-size:12px">{{ $application->jobPosting?->getTitle() ?? '—' }}</td>
                <td style="font-size:12px">{{ $application->jobPosting?->country?->name ?? '—' }}</td>
                <td>
                    @if($application->rating > 0)
                        <span style="color:var(--sun);font-weight:700">{{ str_repeat('★', $application->rating) }}</span><span style="color:#ddd">{{ str_repeat('★', 5 - $application->rating) }}</span>
                    @else
                        <span style="color:#ddd">★★★★★</span>
                    @endif
                </td>
                <td><span class="badge badge-{{ $application->status }}">{{ ucfirst($application->status) }}</span></td>
                <td style="color:var(--muted);font-size:12px">{{ $application->submitted_at?->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('admin.careers.show', $application->public_id) }}" class="btn btn-sun btn-sm">Review →</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No applications found matching your criteria.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($applications->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $applications->currentPage() }} of {{ $applications->lastPage() }}</span>
        {{ $applications->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Layout ships badge-new/reviewing/quoted/accepted/rejected/hired/interviewing —
       these two ATS statuses are missing, so define them here. */
    .badge-reviewed{background:#e6fffa;color:#234e52}
    .badge-offered{background:#faf5ff;color:#6b46c1}
</style>
@endpush
