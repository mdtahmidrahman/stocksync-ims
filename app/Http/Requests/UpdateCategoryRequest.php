<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        $category = $this->route('category');
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where('company_id', auth()->user()->company_id)->ignore($category->id)],
            'description' => 'nullable|string'
        ];
    }
}