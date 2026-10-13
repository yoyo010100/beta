<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Defence-in-depth: /admin middleware already gates access, but the
        // request also verifies the acting user actually holds an admin role.
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        $announcement = $this->route('announcement');

        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'content_ar' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'type' => ['required', 'string', 'in:news,press_release,event'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('announcements', 'slug')->ignore($announcement?->id)],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ];
    }
}
