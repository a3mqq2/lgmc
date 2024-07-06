<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by branch_id if provided
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by active status if provided
        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        // Filter by name if provided
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by phone if provided
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Filter by passport_number if provided
        if ($request->filled('passport_number')) {
            $query->where('passport_number', 'like', '%' . $request->passport_number . '%');
        }

        // Filter by ID_number if provided
        if ($request->filled('ID_number')) {
            $query->where('ID_number', 'like', '%' . $request->ID_number . '%');
        }

        // Add more filtering conditions as needed

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }



 /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $branches = Branch::all();
        return view('admin.users.create', compact('permissions','branches'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'active' => 'boolean',
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'ID_number' => 'nullable|string|max:50',
        ]);



        $user = new User;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->phone2 = $request->phone2;
        $user->active  = true;
        $user->passport_number = $request->passport_number;
        $user->ID_number = $request->ID_number;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->save();

        $user->branches()->attach($request->branches);

        if ($request->has('permissions')) {
            foreach ($request->permissions as $roleId => $permissionIds) {
                foreach ($permissionIds as $permissionId) {
                    $permission = Permission::findById($permissionId);
                    $user->givePermissionTo($permission);
                }
            }
        }

        Log::create([
            'user_id' => auth()->id(),
            'details' => 'تم إنشاء الموظف: ' . $request->name
        ]);
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم إنشاء الموظف بنجاح.');
    }


    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $permissions = Permission::all();
        $branches = Branch::all();
        return view('admin.users.edit', compact('user','permissions','branches'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|confirmed|string|min:8',
            'active' => 'boolean',
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'ID_number' => 'nullable|string|max:50',
        ]);
    
        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->phone2 = $request->phone2;
        $user->passport_number = $request->passport_number;
        $user->ID_number = $request->ID_number;
        $user->active = $request->has('active') ? true : false;
    
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = $request->password;
        }
    
        // Save user changes
        $user->save();
    
        // Sync user's branches
        $user->branches()->sync($request->branches);
        $user->syncPermissions($request->permissions); // Assuming $request->permissions contains an array of permission names
        // Log the update action
        Log::create([
            'user_id' => auth()->id(),
            'details' => 'تم تحديث الموظف: ' . $user->name
        ]);
    
        // Redirect back with success message
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم تحديث الموظف بنجاح.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
         // Logging
         Log::create([
            'user_id' => auth()->id(),
            'details' => 'تم حذف الموظف: ' . $user->name
        ]);
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم حذف الموظف بنجاح.');
    }

    public function change_status(User $user) {
        $user->active = !$user->active;
        $user->save();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تغيير حالة الموظف  " . $user->name,
        ]);

        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم تغيير حالة الموظف بنجاح الموظف بنجاح.');
    }

    public function show(User $user) {
        return view('admin.users.show', compact('user'));
    }
}

