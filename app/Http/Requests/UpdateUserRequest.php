<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            
            // Email validation: Ensure unique email except for the current user
            'email'           => 'required|string|email|max:255|unique:users,email,' . $this->route('user'),

            'password'        => 'nullable|confirmed|string|min:8',
            'active'          => 'sometimes|boolean',
            'branch_id'       => 'nullable|exists:branches,id',
            'branches'        => 'nullable|array',
            'branches.*'      => 'exists:branches,id',

            // Phone validation: Starts with 091, 092, 093, or 095 and is 10 digits
            'phone'           => ['nullable', 'regex:/^(091|092|093|095)\d{7}$/'],
            'phone2'          => ['nullable', 'regex:/^(091|092|093|095)\d{7}$/'],

            // Passport number validation
            'passport_number' => 'nullable|string|max:50',

            // National ID validation: Must be 12 digits and start with 1 or 2
            'ID_number'       => ['nullable', 'regex:/^(1|2)\d{11}$/'],

            'permissions'     => 'nullable|array',
            'permissions.*'   => 'string',
            'roles'           => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'       => 'البريد الإلكتروني يجب أن يكون بصيغة صحيحة.',
            'email.unique'      => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.regex'       => 'رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 095 ويتكون من 10 أرقام.',
            'phone2.regex'      => 'رقم الهاتف الثاني يجب أن يبدأ بـ 091 أو 092 أو 093 أو 095 ويتكون من 10 أرقام.',
            'ID_number.regex'   => 'الرقم الوطني يجب أن يتكون من 12 رقمًا ويبدأ بـ 1 أو 2.',
        ];
    }
}
