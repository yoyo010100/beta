@extends('layouts.admin')
@section('title', 'Job Postings')
@section('page_title', 'Careers ATS — Job Postings')

@section('content')
{{-- CREATE FORM --}}
<div class="form-card">
    <h3>+ Create New Job Posting</h3>
    <form action="{{ route('admin.careers.postings.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label for="title_ar">Title (Arabic) *</label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar') }}">
            </div>
            <div class="form-group">
                <label for="title_en">Title (English) *</label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en') }}">
            </div>
            <div class="form-group">
                <label for="description_ar">Description (Arabic) *</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="4" required>{{ old('description_ar') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">Description (English) *</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="4" required>{{ old('description_en') }}</textarea>
            </div>
            <div class="form-group">
                <label for="country_id">Country</label>
                <select id="country_id" name="country_id">
                    <option value="">— All Countries —</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label style="flex-direction:row;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:14px;color:var(--ink)">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    Active (visible on the careers page)
                </label>
            </div>
        </div>
        <div style="margin-top:18px">
            <button type="submit" class="btn btn-sun">✅ Create Posting</button>
        </div>
    </form>
</div>

{{-- POSTINGS TABLE --}}
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">All Postings</h3>
        <div style="font-size:12px;color:var(--muted)">{{ $postings->total() }} total postings</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Country</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($postings as $posting)
            <tr>
                <td style="font-weight:700">{{ $posting->getTitle() }}</td>
                <td>{{ $posting->country?->name ?? '—' }}</td>
                <td>
                    @if($posting->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="color:var(--muted);font-size:12px">{{ $posting->created_at?->format('M d, Y') }}</td>
                <td>
                    <form action="{{ route('admin.careers.postings.toggle', $posting->public_id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline btn-sm">
                            {{ $posting->is_active ? '⏸ Deactivate' : '▶ Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No job postings yet. Create your first posting above.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($postings->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $postings->currentPage() }} of {{ $postings->lastPage() }}</span>
        {{ $postings->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
</style>
@endpush
