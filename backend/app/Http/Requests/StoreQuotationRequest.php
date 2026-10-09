<?php

namespace App\Http\Requests;

use App\Support\QuotationAmounts;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use OverflowException;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-quotations') ?? false;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'list', 'min:1'],
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
                if ($validator->errors()->any()) {
                    return;
                }

                $this->validateAmounts($validator);
            },
        ];
    }

    private function validateAmounts(Validator $validator): void
    {
        try {
            app(QuotationAmounts::class)->calculate($this->validated('items'));
        } catch (InvalidArgumentException|OverflowException) {
            $validator->errors()->add('items', 'The quotation amounts exceed the supported limit.');
        }
    }
}
