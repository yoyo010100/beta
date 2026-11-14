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

{-- Main create/edit form (must NOT contain nested <form> elements) --}
<form action="{{ $isEdit ? route('admin.catalog.products.update', $product->public_id) : route('admin.catalog.products.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {-- BILINGUAL TITLES + SLUG --}
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

    {-- BILINGUAL DESCRIPTIONS --}
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

    {-- DYNAMIC ATTRIBUTES / SPECIFICATIONS REPEATER --}
    <div class="form-card">
        <div class="flex-between" style="margin-bottom:18px">
            <h3 style="margin:0;padding:0;border:none">Product Attributes / Specifications</h3>
            <button type="button" class="btn btn-outline btn-sm" id="addAttrBtn">+ Add Attribute</button>
        </div>
        <p style="font-size:12px;color:var(--muted);margin-bottom:14px;">
            Enter technical attributes such as Weight, Height, Width, Area m², Grade, etc.
        </p>
        <div id="attrRows">
            @php
                $attrNamesEn = old('attr_name_en');
                $attrNamesAr = old('attr_name_ar');
                $attrValuesEn = old('attr_value_en');
                $attrValuesAr = old('attr_value_ar');
                $attrSorts = old('attr_sort_order');

                if (!is_array($attrNamesEn) && $isEdit) {
                    $attrNamesEn = $product->attributes->pluck('name_en')->toArray();
                    $attrNamesAr = $product->attributes->pluck('name_ar')->toArray();
                    $attrValuesEn = $product->attributes->pluck('value_en')->toArray();
                    $attrValuesAr = $product->attributes->pluck('value_ar')->toArray();
                    $attrSorts = $product->attributes->pluck('sort_order')->toArray();
                    $attrIds = $product->attributes->pluck('id')->toArray();
                } else {
                    $attrIds = [];
                }
                $attrCount = is_array($attrNamesEn) ? count($attrNamesEn) : 0;
                if ($attrCount === 0) {
                    $attrNamesEn = [null]; $attrNamesAr = [null];
                    $attrValuesEn = [null]; $attrValuesAr = [null];
                    $attrSorts = [null]; $attrIds = [null];
                    $attrCount = 1;
                }
            @endphp
            @for($i = 0; $i < $attrCount; $i++)
            <div class="attr-row" style="display:grid;grid-template-columns:1.1fr 1fr 1fr 1fr 60px 40px;gap:10px;margin-bottom:10px;align-items:end">
                <input type="text" name="attr_name_en[]" maxlength="120" placeholder="Name (EN) e.g. Weight"
                       value="{{ old('attr_name_en.' . $i, $attrNamesEn[$i] ?? '') }}"
                       style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">
                <input type="text" name="attr_name_ar[]" maxlength="120" placeholder="اسم (عربي)"
                       value="{{ old('attr_name_ar.' . $i, $attrNamesAr[$i] ?? '') }}"
                       dir="rtl" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">
                <input type="text" name="attr_value_en[]" maxlength="255" placeholder="Value (EN) e.g. 500 kg"
                       value="{{ old('attr_value_en.' . $i, $attrValuesEn[$i] ?? '') }}"
                       style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">
                <input type="text" name="attr_value_ar[]" maxlength="255" placeholder="القيمة (عربي)"
                       value="{{ old('attr_value_ar.' . $i, $attrValuesAr[$i] ?? '') }}"
                       dir="rtl" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">
                <input type="number" name="attr_sort_order[]" min="0"
                       value="{{ old('attr_sort_order.' . $i, $attrSorts[$i] ?? $i) }}"
                       style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px;width:60px">
                @if($isEdit && isset($attrIds[$i]))
                    <input type="hidden" name="attr_ids[]" value="{{ $attrIds[$i] }}">
                @endif
                <button type="button" class="btn btn-danger btn-sm remove-attr" aria-label="Remove row">✕</button>
            </div>
            @endfor
        </div>
    </div>

    {-- MULTIPLE IMAGE UPLOAD --}
    <div class="form-card">
        <h3>Product Gallery Images</h3>
        <div class="form-group full">
            <label for="images">Upload Gallery Images (JPG / PNG / WEBP — max 5 MB each)</label>
            <input type="file" id="images" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple>
            <small style="color:var(--muted);font-size:11px">Multiple images can be selected. The first image becomes the cover.</small>
        </div>

        @if($isEdit && $product->images->isNotEmpty())
        <div class="form-group full" style="margin-top:14px">
            <label style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)">Existing Images</label>
            <div id="existingImages" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:10px">
                @foreach($product->images as $image)
                <div class="image-thumb" style="position:relative;width:120px;height:120px;border:1px solid var(--line);border-radius:8px;overflow:hidden">
                    <img src="{{ asset($image->image_path) }}" alt="Gallery" style="width:100%;height:100%;object-fit:cover">
                    <input type="hidden" name="existing_image_ids[]" value="{{ $image->id }}">
                    <input type="checkbox" name="removed_images[]" value="{{ $image->id }}" style="display:none" class="remove-checkbox-{{ $image->id }}">
                    <button type="button" class="remove-img-btn" onclick="removeImage({{ $image->id }})" aria-label="Remove image" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.65);color:#fff;border:none;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10">✕</button>
                    <div class="img-removed-{{ $image->id }}" style="position:absolute;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;z-index:5">removed</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {-- FILE UPLOAD + STATUS --}
    <div class="form-card">
        <h3>Technical Attachment &amp; Status</h3>
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

{-- Existing attachments (separate forms — kept outside the main form to avoid nested <form>) --}}
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

    // Dynamic attribute rows.
    var addBtn = document.getElementById('addAttrBtn');
    var rows = document.getElementById('attrRows');
    if (addBtn && rows) {
        addBtn.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'attr-row';
            row.setAttribute('style', 'display:grid;grid-template-columns:1.1fr 1fr 1fr 1fr 60px 40px;gap:10px;margin-bottom:10px;align-items:end');
            row.innerHTML =
                '<input type="text" name="attr_name_en[]" maxlength="120" placeholder="Name (EN) e.g. Weight" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<input type="text" name="attr_name_ar[]" maxlength="120" placeholder="اسم (عربي)" dir="rtl" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<input type="text" name="attr_value_en[]" maxlength="255" placeholder="Value (EN) e.g. 500 kg" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<input type="text" name="attr_value_ar[]" maxlength="255" placeholder="القيمة (عربي)" dir="rtl" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px">' +
                '<input type="number" name="attr_sort_order[]" min="0" value="0" style="padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px;width:60px">' +
                '<button type="button" class="btn btn-danger btn-sm remove-attr" aria-label="Remove row">✕</button>';
            rows.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-attr')) {
            var all = document.querySelectorAll('#attrRows .attr-row');
            if (all.length > 1) {
                e.target.closest('.attr-row').remove();
            } else {
                e.target.closest('.attr-row').querySelectorAll('input').forEach(function (i) { i.value = ''; });
            }
        }
    });
});

// Remove an existing image on edit (soft-mark for deletion).
function removeImage(imageId) {
    var checkbox = document.querySelector('.remove-checkbox-' + imageId);
    var removedDiv = document.querySelector('.img-removed-' + imageId);
    var thumb = checkbox.closest('.image-thumb');

    if (checkbox && !checkbox.checked) {
        checkbox.checked = true;
        if (removedDiv) removedDiv.style.display = 'flex';
        if (thumb) thumb.style.opacity = '0.45';
    } else {
        checkbox.checked = false;
        if (removedDiv) removedDiv.style.display = 'none';
        if (thumb) thumb.style.opacity = '1';
    }
}
</script>
@endpush
