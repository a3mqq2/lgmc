<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalFacilityRequest extends FormRequest
{
    public function authorize()
    {
        // Adjust if you want specific authorization logic
        return true;
    }

    public function rules()
    {
        return [
            'serial_number'            => 'required',
            'name'                     => 'required|string|max:255',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address'                  => 'required|string|max:255',
            'phone_number'             => 'required|string|max:20',
            'commerical_number'        => 'required',
            'activity_start_date'      => 'required|date',
            'documents' => "nullable",
        ];
    }
}
