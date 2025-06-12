<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalFacilityRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرح له بتنفيذ هذا الطلب.
     */
    public function authorize(): bool
    {
        return auth()->check(); // السماح فقط للمستخدمين المسجلين
    }

    /**
     * قواعد التحقق من البيانات.
     */
    public function rules(): array
    {
        return [
            'name'                   => 'required|string|max:255',
            'address'                => 'required|string|max:255',
            'phone_number'           => 'required|string|digits:10',
            'manager_id'             => 'required',
            'last_issued_date' => 'nullable|date',
        ];
    }

    /**
     * رسائل الخطأ المخصصة لكل قاعدة تحقق.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنشأة الطبية مطلوب.',
            'address.required' => 'العنوان مطلوب.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'manager_id.required' => 'مدير المنشأة الطبية مطلوب.',
        ];
    }
}
