<?php

namespace App\Http\Requests\Admin;

use App\Services\FileValidationService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
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
            'attachment' => ['required', 'file', 'mimes:pdf,dwg,dxf,jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($file = $this->file('attachment')) {
                $result = app(FileValidationService::class)->validate($file, FileValidationService::PROFILE_ATTACHMENT);

                if (! $result['ok']) {
                    $validator->errors()->add('attachment', $result['reason']);
                }
            }
        });
    }
}
