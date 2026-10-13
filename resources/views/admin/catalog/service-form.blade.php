@extends('layouts.admin')

@php
    $isEdit = isset($service);
@endphp

@section('title', $isEdit ? 'Edit Service' : 'New Service')
@section('page_title', $isEdit ? 'Edit Service — ' . $service->getTitle() : 'Create Service')

@section('content')
<div style="margin-bottom:20px">
    <a href="{{ route('admin.catalog.services') }}" style="font-size:12px;color:var(--muted)">← Back to Services</a>
</div>

{{-- Main create/edit form (must NOT contain nested <form> elements) --}}
<form action="{{ $isEdit ? route('admin.catalog.services.update', $service->public_id) : route('admin.catalog.services.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- BILINGUAL TITLES + SLUG --}}
    <div class="form-card">
        <h3>Basic Information</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="title_ar">Title (Arabic) *</label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar', $service->title['ar'] ?? '') }}">
            </div>
            <div class="form-group">
                <label for="title_en">Title (English) *</label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en', $service->title['en'] ?? '') }}">
            </div>
            <div class="form-group full">
                <label for="slug">Slug (URL) *</label>
                <input type="text" id="slug" name="slug" dir="ltr" maxlength="150" required
                       placeholder="e.g. structural-engineering" value="{{ old('slug', $service->slug ?? '') }}">
            </div>
        </div>
    </div>

    {{-- BILINGUAL DESCRIPTIONS --}}
    <div class="form-card">
        <h3>Description</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="6">{{ old('description_ar', $service->description['ar'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">Description (English)</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="6">{{ old('description_en', $service->description['en'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- FILE UPLOAD + STATUS --}}
    <div class="form-card">
        <h3>Attachment &amp; Status</h3>
        <div class="form-grid">
            <div class="form-group full">
                <label for="attachment">Upload File (PDF / DWG / DXF / JPG / PNG — max 10 MB)</label>
                <input type="file" id="attachment" name="attachment" accept=".pdf,.dwg,.dxf,.jpg,.jpeg,.png">
                @if($isEdit)
                    <small style="color:var(--muted);font-size:11px">Leave empty to keep existing files. New uploads are queued for virus scanning.</small>
                @endif
            </div>
            <div class="form-group full">
                <label style="flex-direction:row;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:14px;color:var(--ink)">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px"
                           {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                    Active (visible in the public catalog)
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-sun">{{ $isEdit ? '💾 Update Service' : '✅ Create Service' }}</button>
        <a href="{{ route('admin.catalog.services') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

{{-- Existing attachments (separate forms — kept outside the main form to avoid nested <form>) --}}
@if($isEdit && $service->attachments->isNotEmpty())
<div class="form-card" style="margin-top:24px">
    <h3>Existing Files</h3>
    @foreach($service->attachments as $att)
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-suggest slug from the English title (only while slug is empty).
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
