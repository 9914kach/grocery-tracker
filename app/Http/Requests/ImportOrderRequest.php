<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store'               => ['required', 'string', 'max:100'],
            'ordered_at'          => ['required', 'date'],
            'receipt_reference'   => ['nullable', 'string', 'max:255'],
            'items'               => ['required', 'array', 'min:1'],
            'items.*.raw_name'    => ['required', 'string', 'max:255'],
            'items.*.quantity'    => ['required', 'numeric', 'min:0.001'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'items.*.line_total'  => ['nullable', 'numeric', 'min:0'],
            'items.*.external_reference' => ['nullable', 'string', 'max:255'],
            'items.*.weight_grams'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
