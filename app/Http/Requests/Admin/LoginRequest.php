<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest — guards the public POST /admin/login endpoint.
 *
 * Login is intentionally unauthenticated (authorize() true), so this request
 * focuses on strict, typed input and a generic shape that never leaks which
 * credential was wrong.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:254'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
