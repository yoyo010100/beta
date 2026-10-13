<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Defence-in-depth: /admin middleware already gates access, but the
        // request also verifies the acting user actually holds an admin role.
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:150', Rule::unique('services', 'slug')->ignore($service?->id)],
            'is_active' => ['boolean'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,dwg,dxf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
