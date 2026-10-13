@extends('layouts.admin')
@section('title', 'Application — ' . $application->applicant_name)
@section('page_title', 'Candidate — ' . $application->applicant_name)

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <div>
        <a href="{{ route('admin.careers.index') }}" style="font-size:12px;color:var(--muted)">← Back to Applications</a>
        <h1 style="font-size:22px;font-weight:800;margin-top:4px">{{ $application->applicant_name }}</h1>
        <div style="font-size:13px;color:var(--muted)">{{ $application->email }} &bull; {{ $application->phone }}</div>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <span class="badge badge-{{ $application->status }}" style="font-size:13px;padding:8px 16px">{{ ucfirst($application->status) }}</span>
        <form action="{{ route('admin.careers.status', $application->public_id) }}" method="POST" style="display:flex;gap:8px">
            @csrf
            @method('PATCH')
            <select name="status" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px">
                @foreach(['new','reviewed','interviewing','offered','hired','rejected'] as $s)
                <option value="{{ $s }}" {{ $application->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Update</button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">

    {{-- LEFT: Candidate Details + CV --}}
    <div>
        <div class="form-card">
            <h3>Candidate Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13px">
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Full Name</div>
                    <strong>{{ $application->applicant_name }}</strong>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Email</div>
                    <strong><a href="mailto:{{ $application->email }}" style="color:var(--ocean)">{{ $application->email }}</a></strong>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Phone</div>
                    <strong>{{ $application->phone }}</strong>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Applied</div>
                    <strong>{{ $application->submitted_at?->format('M d, Y H:i') }}</strong>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Position</div>
                    <strong>{{ $application->jobPosting?->getTitle() ?? '—' }}</strong>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Country</div>
                    <strong>{{ $application->jobPosting?->country?->name ?? '—' }}</strong>
                </div>
            </div>
        </div>

        {{-- CV / Attachments --}}
        @if($application->attachments->isNotEmpty())
        <div class="form-card">
            <h3>CV / Attachments</h3>
            @foreach($application->attachments as $att)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--line);border-radius:6px;margin-bottom:8px">
                <div>
                    <div style="font-size:13px;font-weight:700">{{ $att->file_name }}</div>
                    <div style="font-size:11px;color:var(--muted)">
                        {{ number_format(($att->size_bytes ?? 0) / 1024, 0) }} KB &bull;
                        <span class="badge badge-{{ $att->scan_status }}">{{ $att->scan_status }}</span>
                    </div>
                </div>
                @if($att->scan_status === 'passed')
                <button type="button" onclick="getSignedUrl('{{ $att->public_id }}')" class="btn btn-outline btn-sm">⬇ Download</button>
                @else
                <span style="font-size:11px;color:var(--red)">⚠ Quarantined</span>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- RIGHT: Rating Engine --}}
    <div>
        <div class="form-card" style="position:sticky;top:96px">
            <h3>Candidate Rating</h3>
            <p style="font-size:12px;color:var(--muted);margin-bottom:14px">Click a star to rate this candidate (1–5). Saved instantly.</p>
            <div class="stars" id="ratingStars" style="text-align:center;margin-bottom:14px">
                @for($i = 1; $i <= 5; $i++)
                <span data-value="{{ $i }}" class="{{ $application->rating >= $i ? 'filled' : '' }}">★</span>
                @endfor
            </div>
            <div style="text-align:center;font-size:13px;font-weight:700;color:var(--muted)" id="ratingLabel">
                {{ $application->rating > 0 ? $application->rating . ' / 5' : 'Not rated yet' }}
            </div>
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--line)">
                <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Current Status</div>
                <div style="font-size:15px;font-weight:700">{{ ucfirst($application->status) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge-reviewed{background:#e6fffa;color:#234e52}
    .badge-offered{background:#faf5ff;color:#6b46c1}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Star rating — PATCH to admin.careers.rating, expects JSON { success, rating }.
    var stars = document.querySelectorAll('#ratingStars span');
    var label = document.getElementById('ratingLabel');
    var endpoint = @json(route('admin.careers.rating', $application->public_id));
    var token = @json(csrf_token());

    stars.forEach(function (star) {
        star.addEventListener('click', function () {
            var value = parseInt(star.dataset.value, 10);
            fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ rating: value })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    stars.forEach(function (s) {
                        s.classList.toggle('filled', parseInt(s.dataset.value, 10) <= data.rating);
                    });
                    label.textContent = data.rating + ' / 5';
                }
            })
            .catch(function () { label.textContent = 'Save failed — try again.'; });
        });
    });

    // Secure attachment download via short-lived signed URL.
    var signedEndpoint = @json(route('admin.attachments.signed-url', '__ID__'));
    window.getSignedUrl = function (attachmentId) {
        fetch(signedEndpoint.replace('__ID__', attachmentId))
            .then(function (r) { return r.json(); })
            .then(function (d) { if (d.url) window.open(d.url, '_blank'); });
    };
});
</script>
@endpush
