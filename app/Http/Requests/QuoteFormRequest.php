<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:150'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['required', 'string', 'max:50'],
            'country_id' => ['required', 'exists:countries,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'client_message' => ['required', 'string', 'max:5000'],
            'drawing' => ['nullable', 'file', 'mimes:pdf,dwg,dxf,jpg,jpeg,png', 'max:10240'],
            'website_url_hp' => ['nullable', 'string'],
            'captcha_answer' => ['required', 'numeric'],
        ];
    }
}
