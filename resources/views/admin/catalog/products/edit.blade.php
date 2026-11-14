@extends('layouts.admin')

@php
    $isEdit = isset($product);
@endphp

@section('title', 'Edit Product — ' . ($product ? $product->getTitle() : ''))
@section('page_title', 'Edit Product — ' . ($product ? $product->getTitle() : ''))

@section('content')
<div style="margin-bottom:24px">
    <a href="{{ route('admin.catalog.products') }}" style="font-size:12px;color:var(--muted);text-decoration:none">← Back to Products</a>
</div>

<form action="{{ route('admin.catalog.products.update', $product->public_id) }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- BASIC INFORMATION --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">📝</span>
            <h3>Basic Information</h3>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="title_ar">Title (Arabic) <span class="req">*</span></label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar', $product->title['ar'] ?? '') }}">
            </div>
            <div class="form-group">
                <label for="title_en">Title (English) <span class="req">*</span></label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en', $product->title['en'] ?? '') }}">
            </div>
            <div class="form-group full">
                <label for="slug">Slug (URL) <span class="req">*</span></label>
                <input type="text" id="slug" name="slug" dir="ltr" maxlength="150" required
                       placeholder="e.g. heavy-steel-beams" value="{{ old('slug', $product->slug ?? '') }}">
                <p class="hint">Used in the product page URL.</p>
            </div>
        </div>
    </div>

    {{-- DESCRIPTIONS --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">📄</span>
            <h3>Description</h3>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="5">{{ old('description_ar', $product->description['ar'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">Description (English)</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="5">{{ old('description_en', $product->description['en'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- PRODUCT ATTRIBUTES / SPECIFICATIONS --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">📐</span>
            <h3>Product Attributes &amp; Specifications</h3>
            <button type="button" class="btn btn-outline btn-sm" id="addAttrBtn">+ Add Attribute</button>
        </div>
        <p class="hint">Enter technical attributes such as Weight, Height, Width, Area m², Grade, etc. Order is preserved as entered.</p>
        <div id="attrRows" class="attr-list">
            @php
                $attrNamesEn = old('attr_name_en');
                $attrNamesAr = old('attr_name_ar');
                $attrValuesEn = old('attr_value_en');
                $attrValuesAr = old('attr_value_ar');
                $attrIds = [];

                if (!is_array($attrNamesEn)) {
                    $attrNamesEn = $product->attributes->pluck('name_en')->toArray();
                    $attrNamesAr = $product->attributes->pluck('name_ar')->toArray();
                    $attrValuesEn = $product->attributes->pluck('value_en')->toArray();
                    $attrValuesAr = $product->attributes->pluck('value_ar')->toArray();
                    $attrIds = $product->attributes->pluck('id')->toArray();
                }
                $attrCount = is_array($attrNamesEn) ? count($attrNamesEn) : 0;
                if ($attrCount === 0) {
                    $attrNamesEn = [null]; $attrNamesAr = [null];
                    $attrValuesEn = [null]; $attrValuesAr = [null];
                    $attrIds = [null];
                    $attrCount = 1;
                }
            @endphp
            @for($i = 0; $i < $attrCount; $i++)
            <div class="attr-row">
                <input type="text" name="attr_name_en[]" maxlength="120" placeholder="Name (EN) e.g. Weight"
                       value="{{ old('attr_name_en.' . $i, $attrNamesEn[$i] ?? '') }}">
                <input type="text" name="attr_name_ar[]" maxlength="120" placeholder="اسم (عربي)" dir="rtl"
                       value="{{ old('attr_name_ar.' . $i, $attrNamesAr[$i] ?? '') }}">
                <input type="text" name="attr_value_en[]" maxlength="255" placeholder="Value (EN) e.g. 500 kg"
                       value="{{ old('attr_value_en.' . $i, $attrValuesEn[$i] ?? '') }}">
                <input type="text" name="attr_value_ar[]" maxlength="255" placeholder="القيمة (عربي)" dir="rtl"
                       value="{{ old('attr_value_ar.' . $i, $attrValuesAr[$i] ?? '') }}">
                @if(isset($attrIds[$i]))
                    <input type="hidden" name="attr_ids[]" value="{{ $attrIds[$i] }}">
                @endif
                <button type="button" class="btn-remove-attr" aria-label="Remove attribute">✕</button>
            </div>
            @endfor
        </div>
    </div>

    {{-- PRODUCT GALLERY IMAGES --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">🖼️</span>
            <h3>Product Gallery Images</h3>
        </div>
        <div class="upload-zone" id="galleryUploadZone">
            <input type="file" id="galleryFiles" name="images[]" accept="image/jpeg,image/png,image/webp" multiple style="display:none">
            <div class="upload-content">
                <div class="upload-icon">⬆️</div>
                <div class="upload-title">Drag &amp; drop images or click to browse</div>
                <div class="upload-sub">JPG, PNG, WEBP — up to 5 MB each</div>
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('galleryFiles').click()">Choose files</button>
            </div>
            <div id="galleryPreview" class="preview-grid"></div>
        </div>
        <p class="hint">The first image becomes the product cover. Add more images for the gallery.</p>

        @if($product->images->isNotEmpty())
        <div class="existing-images">
            <div class="existing-header">
                <span class="existing-label">Existing Images</span>
                <span class="existing-count">{{ $product->images->count() }}</span>
            </div>
            <div id="existingGallery" class="preview-grid">
                @foreach($product->images as $image)
                @php $isFirst = $loop->first; @endphp
                <div class="image-thumb existing-thumb" data-image-id="{{ $image->id }}">
                    <img src="{{ asset($image->image_path) }}" alt="Gallery {{ $loop->index }}" style="width:100%;height:100%;object-fit:cover">
                    <div class="thumb-overlay">
                        <span class="cover-badge {{ $isFirst ? '' : 'hidden' }}">Cover</span>
                        <button type="button" class="btn-remove-existing" onclick="removeExistingImage({{ $image->id }}, this)" aria-label="Remove image">✕</button>
                    </div>
                    <input type="hidden" name="existing_image_ids[]" value="{{ $image->id }}">
                    <input type="checkbox" name="removed_images[]" value="{{ $image->id }}" style="display:none" class="remove-checkbox-{{ $image->id }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- TECHNICAL DATASHEET / CAD FILE --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">📎</span>
            <h3>Technical Datasheet / CAD File</h3>
        </div>
        <div class="file-dropzone" id="technicalDropzone">
            <input type="file" id="technicalFile" name="technical_file" accept=".pdf,.dwg,.dxf,.doc,.docx,.zip" style="display:none">
            <div class="drop-content">
                <div class="drop-icon">📄</div>
                <div class="drop-title">Drag &amp; drop technical file or click to browse</div>
                <div class="drop-sub">PDF, DWG, DXF, DOC, DOCX, ZIP — up to 20 MB</div>
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('technicalFile').click()">Choose file</button>
            </div>
            <div id="technicalPreview" class="file-preview" style="display:none">
                <span class="file-icon">📄</span>
                <div class="file-info">
                    <div id="technicalFileName" class="file-name"></div>
                    <div id="technicalFileSize" class="file-size"></div>
                </div>
                <button type="button" class="btn-remove-file" onclick="removeTechnicalFile()" aria-label="Remove file">✕</button>
                <input type="hidden" name="technical_file_remove" value="0" id="technicalFileRemove">
            </div>

            @php
                $techFile = $product->attachments->where(function($q) {
                    $q->where('file_name', 'like', '%.pdf')
                      ->orWhere('file_name', 'like', '%.dwg')
                      ->orWhere('file_name', 'like', '%.dxf')
                      ->orWhere('file_name', 'like', '%.doc')
                      ->orWhere('file_name', 'like', '%.docx')
                      ->orWhere('file_name', 'like', '%.zip');
                })->first();
            @endphp
            @if($techFile)
            <div class="existing-file" style="margin-top:12px;padding:12px;border:1px solid var(--line);border-radius:8px;background:#fafbfc">
                <span class="file-icon">📄</span>
                <div style="flex:1">
                    <div style="font-weight:700;color:var(--ink)">{{ $techFile->file_name }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ number_format(($techFile->size_bytes ?? 0) / 1024, 0) }} KB • {{ $techFile->scan_status }}</div>
                </div>
                <button type="button" class="btn-remove-file" onclick="removeExistingTechnicalFile({{ $techFile->public_id }}, this)" aria-label="Remove file">✕</button>
                <input type="hidden" name="existing_technical_file_id" value="{{ $techFile->public_id }}">
                <input type="checkbox" name="removed_technical_file" value="1" style="display:none" id="removeTechnicalFileCheckbox">
            </div>
            @endif
        </div>
        <p class="hint">Optional technical datasheet or CAD drawing for the product.</p>
    </div>

    {{-- STATUS --}}
    <div class="form-card">
        <div class="card-head">
            <span class="card-icon">⚙️</span>
            <h3>Status</h3>
        </div>
        <div class="form-grid">
            <div class="form-group full">
                <label class="checkbox-label">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:18px;height:18px;accent-color:var(--sun)"
                           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                    Active (visible in the public catalog)
                </label>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.catalog.products') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-sun">💾 Update Product</button>
    </div>
</form>

{{-- Existing non-technical attachments --}}
@if($product->attachments->where('file_path', 'not like', 'uploads/products/technical/%')->isNotEmpty())
<div class="form-card" style="margin-top:24px">
    <div class="card-head">
        <span class="card-icon">📁</span>
        <h3>Existing Files</h3>
    </div>
    @foreach($product->attachments->where('file_path', 'not like', 'uploads/products/technical/%') as $att)
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
.form-card{background:var(--white);border:1px solid var(--line);border-radius:12px;margin-bottom:20px;overflow:hidden}
.card-head{display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid var(--line);background:#fafbfc}
.card-icon{font-size:16px}
.card-head h3{margin:0;font-size:15px;font-weight:800;letter-spacing:.04em;color:var(--ink)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:20px}
.form-group{display:flex;flex-direction:column;gap:7px}
.form-group.full{grid-column:1/-1}
label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)}
.req{color:var(--sun)}
input,textarea{padding:11px 14px;border:1px solid var(--line);border-radius:8px;font-size:14px;font-family:inherit;background:#fafbfc}
input:focus,textarea:focus{outline:none;border-color:var(--sun)}
.hint{font-size:11px;color:var(--muted);margin-top:6px}
.form-actions{display:flex;gap:12px;justify-content:flex-end;padding:20px;border-top:1px solid var(--line)}

/* Attributes repeater */
.attr-list{padding:20px}
.attr-row{display:grid;grid-template-columns:1.15fr 1fr 1fr 1fr 44px;gap:10px;margin-bottom:10px;align-items:center}
.attr-row input{padding:10px 14px}
.btn-remove-attr{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:6px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px}
.btn-remove-attr:hover{background:#fecaca;color:#991b1b}

/* Gallery upload zone */
.upload-zone{border:2px dashed var(--line);border-radius:12px;padding:26px;text-align:center;margin-bottom:12px;background:#fafbfc;transition:border-color .2s}
.upload-zone.drag-over{border-color:var(--sun);background:#fffdf0}
.upload-content{display:inline-block;text-align:center}
.upload-icon{font-size:32px;margin-bottom:8px;display:block}
.upload-title{font-size:14px;font-weight:700;color:var(--ink);margin-bottom:4px;display:block}
.upload-sub{font-size:11px;color:var(--muted);display:block;margin-bottom:10px}
.preview-grid{display:flex;flex-wrap:wrap;gap:10px}
.image-thumb{position:relative;width:120px;height:120px;border:1px solid var(--line);border-radius:8px;overflow:hidden;background:#fafbfc}
.image-thumb img{width:100%;height:100%;object-fit:cover}
.thumb-overlay{position:absolute;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:flex-end;justify-content:flex-end;padding:6px}
.cover-badge{background:var(--sun);color:var(--ink);font-size:9px;font-weight:800;padding:2px 6px;border-radius:4px;position:absolute;top:6px;left:6px;z-index:10}
.btn-remove-new, .btn-remove-existing{background:rgba(255,255,255,.85);color:var(--ink);border:none;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10;position:absolute;top:6px;right:6px}
.btn-remove-new:hover, .btn-remove-existing:hover{background:#fff;color:#b91c1c}
.existing-images{margin-top:16px}
.existing-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.existing-label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)}
.existing-count{font-size:11px;color:var(--sun);font-weight:700}

/* Technical file dropzone */
.file-dropzone{border:2px dashed var(--line);border-radius:12px;padding:26px;text-align:center;margin-bottom:12px;background:#fafbfc;transition:border-color .2s}
.file-dropzone.drag-over{border-color:var(--sun);background:#f0fdf4}
.drop-content{display:inline-block;text-align:center}
.drop-icon{font-size:32px;margin-bottom:8px;display:block}
.drop-title{font-size:14px;font-weight:700;color:var(--ink);margin-bottom:4px;display:block}
.drop-sub{font-size:11px;color:var(--muted);display:block;margin-bottom:10px}
.file-preview{display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--sun);border-radius:8px;background:#fffdf0;max-width:460px;margin-top:10px}
.file-icon{font-size:20px}
.file-info{flex:1}
.file-name{font-weight:700;color:var(--ink);font-size:14px}
.file-size{font-size:11px;color:var(--muted)}
.btn-remove-file{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:6px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;flex-shrink:0}
.btn-remove-file:hover{background:#fecaca;color:#991b1b}

.checkbox-label{display:flex;align-items:center;gap:10px;cursor:pointer}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:8px;font-size:12px;font-weight:800;letter-spacing:.06em;text-decoration:none;cursor:pointer;transition:.2s;border:none}
.btn-outline{background:var(--white);border:1px solid var(--line);color:var(--ink)}
.btn-outline:hover{border-color:var(--sun);color:var(--sun)}
.btn-sun{background:var(--sun);color:var(--ink)}
.btn-sun:hover{background:#e09a00}
.btn-danger{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca}
.btn-danger:hover{background:#fecaca;color:#991b1b}
.btn-sm{padding:8px 14px;font-size:11px}
html[dir="rtl"] .form-grid{direction:rtl}
html[dir="rtl"] .attr-row{direction:rtl}
html[dir="rtl"] .form-actions{justify-content:flex-start}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-suggest slug from English title
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

    // Dynamic attribute rows
    var addBtn = document.getElementById('addAttrBtn');
    var rows = document.getElementById('attrRows');
    if (addBtn && rows) {
        addBtn.addEventListener('click', function () {
            var index = rows.querySelectorAll('.attr-row').length;
            var row = document.createElement('div');
            row.className = 'attr-row';
            row.innerHTML =
                '<input type="text" name="attr_name_en[]" maxlength="120" placeholder="Name (EN) e.g. Weight">' +
                '<input type="text" name="attr_name_ar[]" maxlength="120" placeholder="اسم (عربي)" dir="rtl">' +
                '<input type="text" name="attr_value_en[]" maxlength="255" placeholder="Value (EN) e.g. 500 kg">' +
                '<input type="text" name="attr_value_ar[]" maxlength="255" placeholder="القيمة (عربي)" dir="rtl">' +
                '<button type="button" class="btn-remove-attr" aria-label="Remove attribute">✕</button>';
            rows.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('btn-remove-attr')) {
            var all = document.querySelectorAll('#attrRows .attr-row');
            if (all.length > 1) {
                e.target.closest('.attr-row').remove();
            } else {
                e.target.closest('.attr-row').querySelectorAll('input').forEach(function (i) { i.value = ''; });
            }
        }
    });

    // Gallery image upload with live preview
    var galleryInput = document.getElementById('galleryFiles');
    var galleryZone = document.getElementById('galleryUploadZone');
    var galleryPreview = document.getElementById('galleryPreview');
    var galleryCount = 0;

    if (galleryInput && galleryZone && galleryPreview) {
        galleryZone.addEventListener('click', function () { galleryInput.click(); });
        galleryZone.addEventListener('dragover', function (e) { e.preventDefault(); galleryZone.classList.add('drag-over'); });
        galleryZone.addEventListener('dragleave', function () { galleryZone.classList.remove('drag-over'); });
        galleryZone.addEventListener('drop', function (e) {
            e.preventDefault();
            galleryZone.classList.remove('drag-over');
            handleGalleryFiles(e.dataTransfer.files);
        });
        galleryInput.addEventListener('change', function () { handleGalleryFiles(this.files); });
    }

    function handleGalleryFiles(files) {
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if (!file.type.match('image.*')) continue;
            if (file.size > 5 * 1024 * 1024) continue;
            addGalleryPreview(file);
        }
    }

    function addGalleryPreview(file) {
        galleryCount++;
        var isFirst = galleryPreview.children.length === 0 && document.querySelectorAll('.existing-thumb').length === 0;
        var reader = new FileReader();
        reader.onload = function (e) {
            var wrapper = document.createElement('div');
            wrapper.className = 'image-thumb new-thumb';
            wrapper.style.position = 'relative';
            wrapper.style.width = '120px';
            wrapper.style.height = '120px';
            wrapper.style.border = '1px solid var(--line)';
            wrapper.style.borderRadius = '8px';
            wrapper.style.overflow = 'hidden';
            wrapper.innerHTML =
                '<img src="' + e.target.result + '" alt="Gallery" style="width:100%;height:100%;object-fit:cover">' +
                '<span class="cover-badge ' + (isFirst ? '' : 'hidden') + '" style="background:var(--sun);color:var(--ink);font-size:9px;font-weight:800;padding:2px 6px;border-radius:4px;position:absolute;top:6px;left:6px;z-index:10">Cover</span>' +
                '<button type="button" class="btn-remove-new" onclick="removeNewGallery(this)" style="background:rgba(255,255,255,.85);color:var(--ink);border:none;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10;position:absolute;top:6px;right:6px">✕</button>';
            galleryPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    }

    function removeNewGallery(btn) {
        var thumb = btn.closest('.image-thumb');
        if (thumb) thumb.remove();
    }
});

// Remove existing gallery image (soft-mark for deletion)
function removeExistingImage(imageId, btn) {
    var checkbox = document.querySelector('.remove-checkbox-' + imageId);
    var thumb = btn.closest('.existing-thumb');
    if (checkbox && !checkbox.checked) {
        checkbox.checked = true;
        thumb.style.opacity = '0.4';
        thumb.style.background = '#f1f1f1';
        btn.textContent = '↺';
        btn.title = 'Restore';
    } else {
        checkbox.checked = false;
        thumb.style.opacity = '1';
        thumb.style.background = '';
        btn.textContent = '✕';
        btn.title = 'Remove';
    }
}

// Technical file handling
var technicalInput = document.getElementById('technicalFile');
var technicalZone = document.getElementById('technicalDropzone');
var technicalPreview = document.getElementById('technicalPreview');

if (technicalInput && technicalZone && technicalPreview) {
    technicalZone.addEventListener('click', function (e) {
        if (!e.target.closest('.btn-remove-file')) technicalInput.click();
    });
    technicalZone.addEventListener('dragover', function (e) { e.preventDefault(); technicalZone.classList.add('drag-over'); });
    technicalZone.addEventListener('dragleave', function () { technicalZone.classList.remove('drag-over'); });
    technicalZone.addEventListener('drop', function (e) {
        e.preventDefault();
        technicalZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) handleTechnicalFile(e.dataTransfer.files[0]);
    });
    technicalInput.addEventListener('change', function () {
        if (this.files.length) handleTechnicalFile(this.files[0]);
    });
}

function handleTechnicalFile(file) {
    var allowed = ['.pdf', '.dwg', '.dxf', '.doc', '.docx', '.zip'];
    var ext = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        alert('Invalid file type. Allowed: PDF, DWG, DXF, DOC, DOCX, ZIP');
        return;
    }
    if (file.size > 20 * 1024 * 1024) {
        alert('File too large. Max 20 MB.');
        return;
    }
    document.getElementById('technicalFileName').textContent = file.name;
    document.getElementById('technicalFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    technicalPreview.style.display = 'flex';
    document.getElementById('technicalFileRemove').value = '0';
}

function removeTechnicalFile() {
    technicalPreview.style.display = 'none';
    technicalInput.value = '';
    document.getElementById('technicalFileRemove').value = '1';
}

function removeExistingTechnicalFile(publicId, btn) {
    if (!confirm('Remove this technical file?')) return;
    var checkbox = document.getElementById('removeTechnicalFileCheckbox');
    var hidden = document.querySelector('input[name="existing_technical_file_id"]');
    if (checkbox) { checkbox.checked = true; checkbox.value = '1'; }
    if (hidden) hidden.value = '';
    var card = btn.closest('.existing-file');
    if (card) card.style.display = 'none';
    document.getElementById('technicalFileRemove').value = '1';
}
</script>
@endpush
