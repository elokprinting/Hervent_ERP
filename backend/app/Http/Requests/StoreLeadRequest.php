<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_company' => ['nullable', 'string', 'max:255'],
            'customer_contact' => ['nullable', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string'],
            'deadline' => ['required', 'date_format:Y-m-d'],
            'pic_user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'items' => ['required', 'array', 'list', 'min:1'],
            'items.*' => ['required', 'array:product_name,details,quantity'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.details' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
