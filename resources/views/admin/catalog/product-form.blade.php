@extends('layouts.admin')

@php
    $isEdit = isset($product);
@endphp

@section('title', $isEdit ? 'Edit Product' : 'New Product')
@section('page_title', $isEdit ? 'Edit Product — ' . $product->getTitle() : 'Create Product')

@section('content')
<div style="margin-bottom:20px">
    <a href="{{ route('admin.catalog.products') }}" style="font-size:12px;color:var(--muted)">← Back to Products</a>
</div>

{{-- Main create/edit form (must NOT contain nested <form> elements) --}}
<form action="{{ $isEdit ? route('admin.catalog.products.update', $product->public_id) : route('admin.catalog.products.store') }}"
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
                       value="{{ old('title_ar', $product->title['ar'] ?? '') }}">
            </div>
            <div class="form-group">
                <label for="title_en">Title (English) *</label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en', $product->title['en'] ?? '') }}">
            </div>
            <div class="form-group full">
                <label for="slug">Slug (URL) *</label>
                <input type="text" id="slug" name="slug" dir="ltr" maxlength="150" required
                       placeholder="e.g. heavy-steel-beams" value="{{ old('slug', $product->slug ?? '') }}">
            </div>
        </div>
    </div>

    {{-- BILINGUAL DESCRIPTIONS --}}
    <div class="form-card">
        <h3>Description</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="6">{{ old('description_ar', $product->description['ar'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">Description (English)</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="6">{{ old('description_en', $product->description['en'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- SPECIFICATIONS (parallel key/value rows) --}}
    <div class="form-card">
        <div class="flex-between" style="margin-bottom:18px">
            <h3 style="margin:0;padding:0;border:none">Specifications</h3>
            <button type="button" class="btn btn-outline btn-sm" id="addSpecBtn">+ Add Row</button>
        </div>
        <div id="specRows">
            @php
                $specKeys = old('spec_keys');
                $specValues = old('spec_values');
                if (!is_array($specKeys)) {
                    $specKeys = array_keys($product->specifications ?? []);
                    $specValues = array_values($product->specifications ?? []);
                }
                $specCount = is_array($specKeys) ? count($specKeys) : 0;
                if ($specCount === 0) { $specKeys = [null]; $specValues = [null]; $specCount = 1; }
            @endphp
            @for($i = 0; $i < $specCount; $i++)
            <div class="spec-row" style="display:flex;gap:12px;margin-bottom:12px;align-items:center">
                <input type="text" name="spec_keys[]" maxlength="120" placeholder="Spec name (e.g. Grade)"
                       style="flex:1;padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px"
                       value="{{ $specKeys[$i] ?? '' }}">
                <input type="text" name="spec_values[]" maxlength="255" placeholder="Value (e.g. S355)"
                       style="flex:1;padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px"
                       value="{{ $specValues[$i] ?? '' }}">
                <button type="button" class="btn btn-danger btn-sm remove-spec" aria-label="Remove row">✕</button>
            </div>
            @endfor
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
                           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                    Active (visible in the public catalog)
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-sun">{{ $isEdit ? '💾 Update Product' : '✅ Create Product' }}</button>
        <a href="{{ route('admin.catalog.products') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

{{-- Existing attachments (separate forms — kept outside the main form to avoid nested <form>) --}}
@if($isEdit && $product->attachments->isNotEmpty())
<div class="form-card" style="margin-top:24px">
    <h3>Existing Files</h3>
    @foreach($product->attachments as $att)
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

    // Dynamic specification rows.
    var addBtn = document.getElementById('addSpecBtn');
    var rows = document.getElementById('specRows');
    if (addBtn && rows) {
        addBtn.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'spec-row';
            row.setAttribute('style', 'display:flex;gap:12px;margin-bottom:12px;align-items:center');
            row.innerHTML =
                '<input type="text" name="spec_keys[]" maxlength="120" placeholder="Spec name (e.g. Grade)" style="flex:1;padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<input type="text" name="spec_values[]" maxlength="255" placeholder="Value (e.g. S355)" style="flex:1;padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<button type="button" class="btn btn-danger btn-sm remove-spec" aria-label="Remove row">✕</button>';
            rows.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-spec')) {
            var all = document.querySelectorAll('#specRows .spec-row');
            if (all.length > 1) {
                e.target.closest('.spec-row').remove();
            } else {
                // Keep one blank row rather than leaving the section empty.
                e.target.closest('.spec-row').querySelectorAll('input').forEach(function (i) { i.value = ''; });
            }
        }
    });
});
</script>
@endpush
