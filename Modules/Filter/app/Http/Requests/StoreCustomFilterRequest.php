<?php

namespace Modules\Filter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'entity_type' => 'required|string',
            'visibility_level' => 'nullable|exists:visibility_levels,id', // Ensure valid visibility level
            'visibility_group_ids' => 'nullable|array',
            'visibility_group_ids.*' => 'exists:visibility_groups,id', // Ensure valid group IDs
            'filter_criteria' => 'nullable|array',
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'The filter name is required.',
            'entity_type.required' => 'Entity Type is required.',
            'visibility_level.exists' => 'The selected visibility level is invalid.',
            'visibility_group_ids.exists' => 'One or more selected visibility groups do not exist.'
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
