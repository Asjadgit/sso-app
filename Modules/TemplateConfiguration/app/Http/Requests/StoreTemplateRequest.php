<?php

namespace Modules\TemplateConfiguration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'view_type' => 'required|string',
            'configuration' => 'nullable|json',
            'is_default' => 'nullable|boolean',
            'visibility_levels' => 'nullable|array',
            'visibility_levels.*' => 'exists:visibility_levels,id' // Ensure IDs exist in DB
        ];
    }

    public function messages(): array
    {
        return [
            'view_type.required' => 'The view type field is required.',
            'is_default.boolean' => 'Is Default must be true or false.',
            'visibility_levels.array' => 'Visibility levels must be an array.',
            'visibility_levels.*.exists' => 'One or more selected visibility levels do not exist.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
