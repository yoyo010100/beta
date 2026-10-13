<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Defence-in-depth: /admin middleware already gates access, but the
        // request also verifies the acting user actually holds an admin role.
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['required', 'string'],
            'description_en' => ['required', 'string'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'is_active' => ['boolean'],
        ];
    }
}
