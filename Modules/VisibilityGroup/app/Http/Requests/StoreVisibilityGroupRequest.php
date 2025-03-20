<?php

namespace Modules\VisibilityGroup\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisibilityGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:visibility_groups,id',
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
