@extends('layouts.admin')

@php
    $isEdit = isset($certificate);
@endphp

@section('title', $isEdit ? 'Edit Certificate' : 'New Certificate')
@section('page_title', $isEdit ? 'Edit Certificate — ' . $certificate->getTitle() : 'Create Certificate')

@section('content')
<div style="margin-bottom:20px">
    <a href="{{ route('admin.certificates.index') }}" style="font-size:12px;color:var(--muted)">← Back to Certificates</a>
</div>

<form action="{{ $isEdit ? route('admin.certificates.update', $certificate->id) : route('admin.certificates.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- BILINGUAL TITLES --}}
    <div class="form-card">
        <h3>Titles</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="title_en">Title (English) *</label>
                <input type="text" id="title_en" name="title_en" dir="ltr" maxlength="255" required
                       value="{{ old('title_en', $isEdit ? $certificate->title_en : '') }}">
            </div>
            <div class="form-group">
                <label for="title_ar">Title (Arabic) *</label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar', $isEdit ? $certificate->title_ar : '') }}">
            </div>
        </div>
    </div>

    {{-- BILINGUAL DESCRIPTIONS --}}
    <div class="form-card">
        <h3>Descriptions</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="description_en">Description (English)</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="4">{{ old('description_en', $isEdit ? $certificate->description_en : '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="4">{{ old('description_ar', $isEdit ? $certificate->description_ar : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- FILES & METADATA --}}
    <div class="form-card">
        <h3>Preview Image &amp; Certificate File</h3>
        <div class="form-grid">
            <div class="form-group full">
                <label for="preview_image">Preview Image (JPG / PNG / WEBP — max 5 MB)</label>
                <input type="file" id="preview_image" name="preview_image" accept=".jpg,.jpeg,.png,.webp">
                @if($isEdit && $certificate->preview_image)
                    <div style="margin-top:10px">
                        <img src="{{ asset($certificate->preview_image) }}" alt="Current preview" style="width:96px;height:96px;object-fit:cover;border-radius:4px;border:1px solid var(--line)">
                        <small style="color:var(--muted);font-size:11px">Leave empty to keep the current image.</small>
                    </div>
                @endif
            </div>
            <div class="form-group full">
                <label for="file">Certificate File (PDF / DOC / DOCX / ZIP — max 20 MB) *{{ $isEdit ? ' (leave empty to keep existing)' : '' }}</label>
                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.zip" {{ $isEdit ? '' : 'required' }}>
                @if($isEdit && $certificate->file_path)
                    <small style="color:var(--muted);font-size:11px">Current: {{ basename($certificate->file_path) }} — leave empty to keep.</small>
                @endif
            </div>
        </div>
    </div>

    {{-- DATE, ORDER, STATUS --}}
    <div class="form-card">
        <h3>Metadata</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="issuance_date">Issuance Date</label>
                <input type="date" id="issuance_date" name="issuance_date"
                       value="{{ old('issuance_date', $isEdit ? ($certificate->issuance_date?->format('Y-m-d')) : '') }}">
                <small style="color:var(--muted);font-size:11px">Optional date when this certificate was issued.</small>
            </div>
            <div class="form-group full">
                <label style="flex-direction:row;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:14px;color:var(--ink)">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px"
                           {{ old('is_active', $isEdit ? $certificate->is_active : true) ? 'checked' : '' }}>
                    Active (visible on the public site)
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-sun">{{ $isEdit ? '💾 Update Certificate' : '✅ Create Certificate' }}</button>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>
@endsection
