@extends('layouts.admin')

@php
    $isEdit = isset($announcement);
@endphp

@section('title', $isEdit ? 'Edit Announcement' : 'New Announcement')
@section('page_title', $isEdit ? 'Edit Announcement — ' . $announcement->getTitle() : 'Create Announcement')

@section('content')
<div style="margin-bottom:20px">
    <a href="{{ route('admin.announcements.index') }}" style="font-size:12px;color:var(--muted)">← Back to Announcements</a>
</div>

{{-- Main create/edit form (must NOT contain nested <form> elements) --}}
<form action="{{ $isEdit ? route('admin.announcements.update', $announcement->public_id) : route('admin.announcements.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- BILINGUAL TITLES + META --}}
    <div class="form-card">
        <h3>Basic Information</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="title_ar">Title (Arabic) *</label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar', $announcement->title['ar'] ?? '') }}">
            </div>
            <div class="form-group">
                <label for="title_en">Title (English) *</label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en', $announcement->title['en'] ?? '') }}">
            </div>
            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    @foreach(['news' => 'News', 'press_release' => 'Press Release', 'event' => 'Event'] as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $announcement->type ?? 'news') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="slug">Slug (URL) *</label>
                <input type="text" id="slug" name="slug" dir="ltr" maxlength="200" required
                       placeholder="e.g. new-facility-opening" value="{{ old('slug', $announcement->slug ?? '') }}">
            </div>
            <div class="form-group">
                <label for="published_at">Published At</label>
                <input type="date" id="published_at" name="published_at"
                       value="{{ old('published_at', ($announcement->published_at ?? null)?->format('Y-m-d')) }}">
                <small style="color:var(--muted);font-size:11px">Leave empty to keep as an unpublished draft.</small>
            </div>
        </div>
    </div>

    {{-- BILINGUAL EXCERPTS --}}
    <div class="form-card">
        <h3>Excerpt (Optional — used in list previews)</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="excerpt_ar">Excerpt (Arabic)</label>
                <textarea id="excerpt_ar" name="excerpt_ar" dir="rtl" rows="3" maxlength="500">{{ old('excerpt_ar', $announcement->excerpt['ar'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="excerpt_en">Excerpt (English)</label>
                <textarea id="excerpt_en" name="excerpt_en" dir="ltr" rows="3" maxlength="500">{{ old('excerpt_en', $announcement->excerpt['en'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- BILINGUAL CONTENT --}}
    <div class="form-card">
        <h3>Content *</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="content_ar">Content (Arabic) *</label>
                <textarea id="content_ar" name="content_ar" dir="rtl" rows="10" required>{{ old('content_ar', $announcement->content['ar'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="content_en">Content (English) *</label>
                <textarea id="content_en" name="content_en" dir="ltr" rows="10" required>{{ old('content_en', $announcement->content['en'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- FILE UPLOAD + STATUS --}}
    <div class="form-card">
        <h3>Attachment &amp; Status</h3>
        <div class="form-grid">
            <div class="form-group full">
                <label for="attachment">Upload File (PDF / JPG / PNG / WEBP — max 10 MB)</label>
                <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp">
                @if($isEdit)
                    <small style="color:var(--muted);font-size:11px">Leave empty to keep existing files. New uploads are queued for virus scanning.</small>
                @endif
            </div>
            <div class="form-group full">
                <label style="flex-direction:row;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:14px;color:var(--ink)">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px"
                           {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                    Active (visible on the public news page)
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-sun">{{ $isEdit ? '💾 Update Announcement' : '✅ Publish Announcement' }}</button>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

{{-- Existing attachments (separate forms — kept outside the main form to avoid nested <form>) --}}
@if($isEdit && $announcement->attachments->isNotEmpty())
<div class="form-card" style="margin-top:24px">
    <h3>Existing Files</h3>
    @foreach($announcement->attachments as $att)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--line);border-radius:6px;margin-bottom:8px">
        <div>
            <div style="font-size:13px;font-weight:700">{{ $att->file_name }}</div>
            <div style="font-size:11px;color:var(--muted)">
                {{ number_format(($att->size_bytes ?? 0) / 1024, 0) }} KB &bull;
                <span class="badge badge-{{ $att->scan_status }}">{{ $att->scan_status }}</span>
            </div>
        </div>
        <form action="{{ route('admin.catalog.attachments.destroy', $att->public_id) }}" method="POST"
              onsubmit="return confirm('Remove this file?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
        </form>
    </div>
    @endforeach
</div>
@endif
@endsection

@push('styles')
<style>
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-suggest slug from the English title (only while slug is untouched).
    var titleEn = document.getElementById('title_en');
    var slug = document.getElementById('slug');
    if (titleEn && slug) {
        titleEn.addEventListener('input', function () {
            if (slug.dataset.touched === '1') return;
            slug.value = titleEn.value.toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
        });
        slug.addEventListener('input', function () { slug.dataset.touched = '1'; });
    }
});
</script>
@endpush
