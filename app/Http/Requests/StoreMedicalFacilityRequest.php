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
            'serial_number'          => 'required',
            'name'                   => 'required|string|max:255',
            'commerical_number'      => 'required',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address'                => 'required|string|max:255',
            'phone_number'           => 'required|string|digits:10',
            'activity_start_date'                   => 'required|date',
            'activity_type'          => 'required|in:commercial_record,negative_certificate',
            'documents'              => 'required',
            'branch_id'              => 'required|exists:branches,id',
            'manager_id'             => 'nullable',
        ];
    }
}
