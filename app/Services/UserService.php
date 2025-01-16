<?php

namespace App\Services;

use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserService
{
    /**
     * Create a new user.
     *
     * @param  array  $data Validated data from StoreUserRequest
     * @return \App\Models\User
     */
    public function createUser(array $data): User
    {

        $user = new User;
        $user->name             = $data['name'];
        $user->phone            = $data['phone'] ?? null;
        $user->phone2           = $data['phone2'] ?? null;
        $user->active           = $data['active'] ?? true;
        $user->passport_number  = $data['passport_number'] ?? null;
        $user->ID_number        = $data['ID_number'] ?? null;
        $user->email            = $data['email'];
        $user->password         = Hash::make($data['password']);
        $user->save();

        if (isset($data['branches']) && is_array($data['branches'])) {
            $user->branches()->attach($data['branches']);
        }

        if (!empty($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (!empty($data['permissions']) && is_array($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم إنشاء الموظف: ' . $user->name,
        ]);

        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data Validated data from UpdateUserRequest
     * @return \App\Models\User
     */
    
     
     public function updateUser(User $user, array $data): User
{

    // Update basic fields if present
    if (isset($data['name'])) {
        $user->name = $data['name'];
    }
    if (isset($data['phone'])) {
        $user->phone = $data['phone'];
    }
    if (isset($data['phone2'])) {
        $user->phone2 = $data['phone2'];
    }
    if (isset($data['active'])) {
        $user->active = $data['active'];
    }
    if (isset($data['passport_number'])) {
        $user->passport_number = $data['passport_number'];
    }
    if (isset($data['ID_number'])) {
        $user->ID_number = $data['ID_number'];
    }
    if (isset($data['email'])) {
        $user->email = $data['email'];
    }

    // Only update password if provided (not empty)
    if (!empty($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    // Save the user
    $user->save();

    // Sync branches if provided
    if (isset($data['branches']) && is_array($data['branches'])) {
        // use sync() if you want to replace existing branches
        $user->branches()->sync($data['branches']);
    }

    // Sync roles if provided
    if (isset($data['roles']) && is_array($data['roles'])) {
        $user->syncRoles($data['roles']);
    } else {
        // If needed, you could do $user->syncRoles([]) to remove all roles
        // or just skip if you want to keep existing roles when not specified
    }

    // Sync permissions if provided
    if (isset($data['permissions']) && is_array($data['permissions'])) {
        // dd($data['permissions']);
        $user->syncPermissions($data['permissions']);
    } else {
        // If needed, remove direct permissions with syncPermissions([])
        // or skip to preserve existing
    }

    // Log update
    Log::create([
        'user_id' => Auth::id(),
        'details' => 'تم تحديث بيانات الموظف: ' . $user->name,
    ]);

    return $user;
}

    /**
     * Delete an existing user.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $name = $user->name;
        $user->delete();

        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم حذف الموظف: ' . $name
        ]);
    }

    /**
     * Change a user's active status.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function changeStatus(User $user): void
    {
        $user->active = !$user->active;
        $user->save();

        Log::create([
            'user_id' => Auth::id(),
            'details' => "تم تغيير حالة الموظف  " . $user->name
        ]);
    }
}
