<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Defence-in-depth: /admin middleware already gates access, but the
        // request also verifies the acting user actually holds an admin role.
        return (bool) $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:150', Rule::unique('products', 'slug')->ignore($product?->id)],
            'spec_keys' => ['nullable', 'array'],
            'spec_keys.*' => ['nullable', 'string', 'max:120'],
            'spec_values' => ['nullable', 'array'],
            'spec_values.*' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,dwg,dxf,jpg,jpeg,png', 'max:10240'],
            'technical_file' => ['nullable', 'file', 'mimes:pdf,dwg,dxf,doc,docx,zip', 'max:20480'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'removed_images' => ['nullable', 'array'],
            'removed_images.*' => ['integer', 'exists:product_images,id'],
            'removed_technical_file' => ['nullable', 'boolean'],
            'attr_name_en' => ['nullable', 'array'],
            'attr_name_en.*' => ['nullable', 'string', 'max:120'],
            'attr_name_ar' => ['nullable', 'array'],
            'attr_name_ar.*' => ['nullable', 'string', 'max:120'],
            'attr_value_en' => ['nullable', 'array'],
            'attr_value_en.*' => ['nullable', 'string', 'max:255'],
            'attr_value_ar' => ['nullable', 'array'],
            'attr_value_ar.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
