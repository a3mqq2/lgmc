<?php

namespace App\Http\Controllers\Admin;
use App\Models\Log;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\UserService;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Permission;


class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    
    public function index(Request $request)
    {
        $filters = $request->only([
            'branch_id', 'active', 'name', 'phone', 'passport_number', 'ID_number'
        ]);
        $users = User::filter($filters)->get();
        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        $permissions = Permission::all();
        $branches = Branch::all();
        $roles = Role::with('permissions')->get();
        return view('admin.users.create', compact('permissions','branches','roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم إنشاء الموظف بنجاح.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::with('permissions')->get();
        $branches = Branch::all();
        return view('admin.users.edit', compact('user','roles','branches'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم تحديث الموظف بنجاح.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->userService->deleteUser($user);
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم حذف الموظف بنجاح.');
    }

    public function change_status(User $user)
    {
        $this->userService->changeStatus($user);
        return redirect()->route(get_area_name().'.users.index')->with('success', 'تم تغيير حالة الموظف بنجاح.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
}
