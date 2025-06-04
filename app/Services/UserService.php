<?php

namespace App\Services;

use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
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


        // create vault 
        $vault = new Vault();
        $vault->user_id = $user->id;
        $vault->openning_balance = 0;
        $vault->balance = 0;
        $vault->name = "حساب الموظف " . $user->name;
        $vault->branch_id = $data['branch_id'] ?? $user->branches->first()->id ?? null;
        $vault->save();

        $user->vault_id = $vault->id;
        $user->save();


        if (!empty($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (!empty($data['permissions']) && is_array($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم إنشاء الموظف: ' . $user->name,
            'loggable_id' => $user->id,
            'loggable_type' => User::class,
            'action' => 'create_user',
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

    if (!empty($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    $user->save();

    if (isset($data['branches']) && is_array($data['branches'])) {
        $user->branches()->sync($data['branches']);
    }

    if (isset($data['roles']) && is_array($data['roles'])) {
        $user->syncRoles($data['roles']);
    } else {
    }

    if (isset($data['permissions']) && is_array($data['permissions'])) {
        $user->syncPermissions($data['permissions']);
    } else {
    }

    // Log update
    Log::create([
        'user_id' => Auth::id(),
        'details' => 'تم تحديث بيانات الموظف: ' . $user->name,
        'loggable_id' => $user->id,
            'loggable_type' => User::class,
            'action' => 'update_user',
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


        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم حذف الموظف: ' . $user->name,
            'loggable_id' => $user->id,
            'loggable_type' => User::class,
            'action' => 'delete_user',
        ]);

        $name = $user->name;
        $user->delete();
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
            'details' => "تم تغيير حالة الموظف  " . $user->name,
            'loggable_id' => $user->id,
            'loggable_type' => User::class,
            'action' => 'change_user_status',
        ]);
    }
}
