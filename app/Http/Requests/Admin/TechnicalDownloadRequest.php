<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TechnicalDownloadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Defence-in-depth: /admin middleware already gates access, but the
        // request also verifies the acting user actually holds an admin role.
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'preview_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => $isUpdate
                ? ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480']
                : ['required', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
            'issuance_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ];
    }
}
