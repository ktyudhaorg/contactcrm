<?php

namespace App\Http\Requests\WhatsApp;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class WhatsAppSendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to'           => ['required', 'string'],
            'message'      => ['nullable', 'string'],
            'media'        => ['nullable', 'array'],
            'media.data'   => ['nullable', 'file', 'max:20480'],
            'media.url'    => ['nullable', 'url'],
            'media.caption' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function () {
                if (empty($this->input('media'))) return;

                $hasData = $this->hasFile('media.data');
                $hasUrl  = !empty($this->input('media.url'));

                if (!$hasData && !$hasUrl) {
                    throw ValidationException::withMessages([
                        'media' => 'Either media.data or media.url is required.',
                    ]);
                }

                if ($hasData && $hasUrl) {
                    throw ValidationException::withMessages([
                        'media' => 'media.data and media.url cannot be used at the same time.',
                    ]);
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'to.required'     => 'Recipient number is required.',
            'media.url.url'   => 'media.url must be a valid URL.',
            'media.data.file' => 'media.data must be a file.',
            'media.data.max'  => 'media.data may not be greater than 20MB.',
        ];
    }
}
