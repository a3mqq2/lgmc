<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                   => 'required|string|max:255',
            'type'         => 'required|in:private_clinic,medical_services',
            'address'                => 'required|string|max:255',
            'phone_number'           => 'required|string|digits:10',
            'manager_id'             => 'required',
            'last_issued_date' => 'nullable|date',
            'commercial_number' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom error messages for the validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنشأة الطبية مطلوب.',
            'facility_type.required' => 'نوع المنشأة الطبية مطلوب.',
            'address.required' => 'العنوان مطلوب.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'branch_id.required' => 'الفرع مطلوب.',
            'manager_id.required' => 'مدير المنشأة الطبية مطلوب.',
        ];
    }

}
