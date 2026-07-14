<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        $warehouse = $this->route('warehouse');
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('warehouses')->where('company_id', auth()->user()->company_id)->ignore($warehouse->id)],
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}