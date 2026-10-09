<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'max:10000'],
            'followed_up_at' => ['sometimes', 'date'],
            'customer_name' => ['sometimes', 'required', 'string', 'max:255'],
            'customer_company' => ['sometimes', 'nullable', 'string', 'max:255'],
            'customer_contact' => ['sometimes', 'nullable', 'string', 'max:120'],
            'customer_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'customer_address' => ['sometimes', 'nullable', 'string'],
            'deadline' => ['sometimes', 'required', 'date_format:Y-m-d'],
            'pic_user_id' => ['sometimes', 'required', 'integer', Rule::exists('users', 'id')],
            'items' => ['sometimes', 'required', 'array', 'list', 'min:1'],
            'items.*' => ['required', 'array:product_name,details,quantity'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.details' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
