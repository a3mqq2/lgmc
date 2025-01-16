<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Implement authorization logic if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255', // Adjust uniqueness if needed
            'password'        => 'nullable|confirmed|string|min:8',
            'active'          => 'sometimes|boolean',
            'branch_id'       => 'nullable|exists:branches,id',
            'branches'        => 'nullable|array',
            'branches.*'      => 'exists:branches,id',
            'phone'           => 'nullable|string|max:20',
            'phone2'          => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'ID_number'       => 'nullable|string|max:50',
            'permissions'     => 'nullable|array',
            'permissions.*'   => 'string'
        ];
    }
}
