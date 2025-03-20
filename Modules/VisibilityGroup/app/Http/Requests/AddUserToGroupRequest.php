<?php

namespace Modules\VisibilityGroup\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id'   => 'required|array',
            'user_id.*' => 'exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'   => 'At least one user must be selected.',
            'user_id.array'      => 'User ID must be an array.',
            'user_id.*.exists'   => 'One or more selected users do not exist.',
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
