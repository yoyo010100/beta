<?php

namespace App\Http\Requests;

use App\Services\FileValidationService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

            // Anti-spam: honeypot must stay empty, timestamp must be present.
            'hp_check' => ['prohibited'],
            'hp_time' => ['required', 'integer'],

            // Math CAPTCHA answer (see FrontendController::generateMathCaptcha).
            'captcha_answer' => ['required', 'numeric'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Tolerate either `captcha` or `captcha_answer` as the answer field name.
        if (! $this->has('captcha_answer') && $this->has('captcha')) {
            $this->merge(['captcha_answer' => $this->input('captcha')]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->verifyElapsedTime($validator);
            $this->verifyMathCaptcha($validator);

            if ($file = $this->file('drawing')) {
                $result = app(FileValidationService::class)->validate($file, FileValidationService::PROFILE_BOQ);

                if (! $result['ok']) {
                    $validator->errors()->add('drawing', $result['reason']);
                }
            }
        });
    }

    /**
     * Reject submissions that arrive faster than a human could complete the form.
     * Accepts `hp_time` as Unix seconds or Unix milliseconds.
     */
    private function verifyElapsedTime(Validator $validator): void
    {
        $submittedAt = (int) $this->input('hp_time');

        if ($submittedAt <= 0) {
            $validator->errors()->add('hp_time', __('Invalid form submission.'));

            return;
        }

        $nowMs = (int) round(microtime(true) * 1000);
        $elapsedMs = $submittedAt >= 100_000_000_000
            ? $nowMs - $submittedAt
            : $nowMs - ($submittedAt * 1000);

        if ($elapsedMs < 3000) {
            $validator->errors()->add('hp_time', __('Invalid form submission.'));
        }
    }

    /**
     * Compare the answer against the session challenge and consume it (single use).
     */
    private function verifyMathCaptcha(Validator $validator): void
    {
        $expected = $this->session()->pull('math_captcha_result');
        $given = $this->input('captcha_answer');

        if ($expected === null || ! is_numeric($given) || (int) $given !== (int) $expected) {
            $validator->errors()->add('captcha_answer', __('Incorrect answer, please solve the challenge again.'));
        }
    }

    /**
     * Discard spam silently: the bot receives a success response and nothing is stored.
     */
    protected function failedValidation(Validator $validator): void
    {
        if (! $validator->errors()->hasAny(['hp_check', 'hp_time', 'captcha_answer'])) {
            parent::failedValidation($validator);

            return;
        }

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json(['success' => true], 200));
        }

        throw new HttpResponseException(
            redirect()->back()->with('success', __('messages.quote_success'))
        );
    }
}
