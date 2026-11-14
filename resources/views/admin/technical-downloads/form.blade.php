@extends('layouts.admin')

@php
    $isEdit = isset($download);
@endphp

@section('title', $isEdit ? 'Edit Technical Download' : 'New Technical Download')
@section('page_title', $isEdit ? 'Edit Download — ' . $download->getTitle() : 'Create Technical Download')

@section('content')
<div style="margin-bottom:20px">
    <a href="{{ route('admin.technical-downloads.index') }}" style="font-size:12px;color:var(--muted)">← Back to Technical Downloads</a>
</div>

<form action="{{ $isEdit ? route('admin.technical-downloads.update', $download->id) : route('admin.technical-downloads.store') }}"
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
                       value="{{ old('title_en', $isEdit ? $download->title_en : '') }}">
            </div>
            <div class="form-group">
                <label for="title_ar">Title (Arabic) *</label>
                <input type="text" id="title_ar" name="title_ar" dir="rtl" maxlength="255" required
                       value="{{ old('title_ar', $isEdit ? $download->title_ar : '') }}">
            </div>
        </div>
    </div>

    {{-- BILINGUAL DESCRIPTIONS --}}
    <div class="form-card">
        <h3>Descriptions</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="description_en">Description (English)</label>
                <textarea id="description_en" name="description_en" dir="ltr" rows="4">{{ old('description_en', $isEdit ? $download->description_en : '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea id="description_ar" name="description_ar" dir="rtl" rows="4">{{ old('description_ar', $isEdit ? $download->description_ar : '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- FILES & METADATA --}}
    <div class="form-card">
        <h3>Preview Image &amp; Download File</h3>
        <div class="form-grid">
            <div class="form-group full">
                <label for="preview_image">Preview Image (JPG / PNG / WEBP — max 5 MB)</label>
                <input type="file" id="preview_image" name="preview_image" accept=".jpg,.jpeg,.png,.webp">
                @if($isEdit && $download->preview_image)
                    <div style="margin-top:10px">
                        <img src="{{ asset($download->preview_image) }}" alt="Current preview" style="width:96px;height:96px;object-fit:cover;border-radius:4px;border:1px solid var(--line)">
                        <small style="color:var(--muted);font-size:11px">Leave empty to keep the current image.</small>
                    </div>
                @endif
            </div>
            <div class="form-group full">
                <label for="file">Downloadable File (PDF / DOC / DOCX / ZIP — max 20 MB) *{{ $isEdit ? ' (leave empty to keep existing)' : '' }}</label>
                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.zip" {{ $isEdit ? '' : 'required' }}>
                @if($isEdit && $download->file_path)
                    <small style="color:var(--muted);font-size:11px">Current: {{ basename($download->file_path) }} — leave empty to keep.</small>
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
                       value="{{ old('issuance_date', $isEdit ? ($download->issuance_date?->format('Y-m-d')) : '') }}">
                <small style="color:var(--muted);font-size:11px">Optional date when this document was issued.</small>
            </div>
            <div class="form-group full">
                <label style="flex-direction:row;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:14px;color:var(--ink)">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px"
                           {{ old('is_active', $isEdit ? $download->is_active : true) ? 'checked' : '' }}>
                    Active (visible on the public site)
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-sun">{{ $isEdit ? '💾 Update Download' : '✅ Create Download' }}</button>
        <a href="{{ route('admin.technical-downloads.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>
@endsection
