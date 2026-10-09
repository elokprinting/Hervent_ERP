<?php

namespace App\Http\Requests;

use App\Support\QuotationAmounts;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use OverflowException;

class UpdateQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-quotations') ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['sometimes', 'required', 'string', 'max:255'],
            'customer_company' => ['sometimes', 'nullable', 'string', 'max:255'],
            'customer_contact' => ['sometimes', 'nullable', 'string', 'max:120'],
            'customer_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'customer_address' => ['sometimes', 'nullable', 'string'],
            'items' => ['sometimes', 'required', 'array', 'list', 'min:1'],
            'items.*' => ['required', 'array:product_name,details,quantity,unit_price'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.details' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'items.*.unit_price' => ['required', 'numeric', 'decimal:0,2', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->any() || ! $this->has('items')) {
                    return;
                }

                try {
                    app(QuotationAmounts::class)->calculate($this->validated('items'));
                } catch (InvalidArgumentException|OverflowException) {
                    $validator->errors()->add('items', 'The quotation amounts exceed the supported limit.');
                }
            },
        ];
    }
}
