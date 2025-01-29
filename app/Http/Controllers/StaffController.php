<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // التحقق من صحة المدخلات
        $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'password'        => 'nullable|confirmed|string|min:8',
            'active'          => 'sometimes|boolean',
            'branch_id'       => 'nullable|exists:branches,id',
            'branches'        => 'nullable|array',
            'branches.*'      => 'exists:branches,id',
            'phone'           => ['nullable', 'regex:/^(091|092|093|095)\d{7}$/'],
            'phone2'          => ['nullable', 'regex:/^(091|092|093|095)\d{7}$/'],
            'passport_number' => 'nullable|string|max:50',
            'ID_number'       => ['nullable', 'regex:/^(1|2)\d{11}$/'],
            'permissions'     => 'nullable|array',
            'permissions.*'   => 'string'
        ]);
    
        // إزالة `password_confirmation` حتى لا يتم حفظه
        unset($validatedData['password_confirmation']);
    
        // إزالة `branches` من التحديث لأنه يتم تخزينه في جدول وسيط
        unset($validatedData['branches']);
        unset($validatedData['permissions']);
    
        // إذا لم يتم إدخال كلمة مرور جديدة، لا نقوم بتحديثها
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        } else {
            // تشفير كلمة المرور الجديدة
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
    
        // تحديث بيانات المستخدم
        $user->update($validatedData);
    
        // مزامنة الفروع إذا تم تحديدها
        if ($request->has('branches')) {
            $user->branches()->sync($request->branches);
        }
    
        // مزامنة الصلاحيات إذا تم تحديدها
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }
    
        return redirect()->route('admin.users.index')->with('success', 'تم تحديث الموظف بنجاح');
    }
    
    
}
