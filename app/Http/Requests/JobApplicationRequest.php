<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'applicant_name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['required', 'string', 'max:50'],
            'job_posting_id' => ['required', 'exists:job_postings,id'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'website_url_hp' => ['nullable', 'string'],
            'captcha_answer' => ['required', 'numeric'],
        ];
    }
}
