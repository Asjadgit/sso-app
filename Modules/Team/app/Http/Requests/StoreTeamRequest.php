<?php

namespace Modules\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'members.required'   => 'At least one user must be selected.',
            'members.array'      => 'Members must be an array.',
            'members.*.exists'   => 'One or more selected members do not exist.',
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
