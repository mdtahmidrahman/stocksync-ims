<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('warehouses')->where('company_id', auth()->user()->company_id)],
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}