<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Implement your authorization logic here if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|confirmed|min:8',
            'active'          => 'sometimes|boolean',
            'branch_id'       => 'nullable|exists:branches,id',
            'branches'        => 'nullable',
            'branches.*'      => 'exists:branches,id',
            'phone'           => 'nullable|string|digits:10',
            'phone2'          => 'nullable|string|digits:10',
            'passport_number' => 'nullable|string|max:50',
            'ID_number'       => 'nullable|string|max:50',
            'roles'           => 'nullable',
            'permissions'     => 'nullable',
        ];
    }
}
