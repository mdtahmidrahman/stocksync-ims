<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'payable_id' => 'required|integer',
            'payable_type' => 'required|string|in:App\Models\Order,App\Models\Sale,App\Models\Purchase',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
        ];
    }
}
