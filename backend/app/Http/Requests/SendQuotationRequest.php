<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-quotations') ?? false;
    }

    public function rules(): array
    {
        return [
            'sent_to_email' => ['required', 'email', 'max:255'],
        ];
    }
}
