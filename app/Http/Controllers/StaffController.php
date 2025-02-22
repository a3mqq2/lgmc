<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;

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

        unset($validatedData['password_confirmation'], $validatedData['branches'], $validatedData['permissions']);

        // تحديث كلمة المرور إذا تم إدخالها
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);

            // سجل تغيير كلمة المرور
            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم تحديث كلمة مرور الموظف: {$user->name}",
                'loggable_id' => $user->id,
                'loggable_type' => User::class,
                "action" => "change_password",
            ]);
        } else {
            unset($validatedData['password']);
        }

        // تحديث بيانات المستخدم
        $user->update($validatedData);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تحديث بيانات الموظف: {$user->name}",
            'loggable_id' => $user->id,
            'loggable_type' => User::class,
            "action" => "update_user",
        ]);

        // مزامنة الفروع إذا تم تحديدها
        if ($request->has('branches')) {
            $user->branches()->sync($request->branches);

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم تحديث الفروع المرتبطة بالموظف: {$user->name}",
                'loggable_id' => $user->id,
                'loggable_type' => User::class,
                "action" => "update_user_branches",
            ]);
        }

        // مزامنة الصلاحيات إذا تم تحديدها
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم تحديث صلاحيات الموظف: {$user->name}",
                'loggable_id' => $user->id,
                'loggable_type' => User::class,
                "action" => "update_user_permissions",
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث الموظف بنجاح');
    }
}
