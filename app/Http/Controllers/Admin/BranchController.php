<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::all();
        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:branches,name",
            "phone" => "required|unique:branches,phone",
            "city" => "nullable",
            "code" => "required",
        ]);

        try {
            DB::beginTransaction();

            $branch = Branch::create([
                'code' => $request->code,
                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم انشاء فرع جديد: {$branch->name}",
                'loggable_id' => $branch->id,
                'loggable_type' => Branch::class,
                'action' => 'create_branch',
            ]);

            DB::commit();
            return redirect()->route(get_area_name() . '.branches.index')
                ->with('success', 'تم انشاء الفرع بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            "code" => "required",
            "name" => "required",
            "phone" => "required",
            "city" => "nullable",
        ]);

        try {
            DB::beginTransaction();

            $branch->update([
                'code' => $request->code,
                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم تعديل بيانات فرع: {$branch->name}",
                'loggable_id' => $branch->id,
                'loggable_type' => Branch::class,
                'action' => 'update_branch',
            ]);

            DB::commit();
            return redirect()->route(get_area_name() . '.branches.index')
                ->with('success', 'تم تحديث الفرع بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        try {
            DB::beginTransaction();

            if ($branch->doctors->count() > 0) {
                return redirect()->back()->withErrors(['error' => 'لا يمكن حذف الفرع لوجود اطباء مرتبطين به']);
            }

            $branchName = $branch->name;
            $branchId = $branch->id;
            $branch->delete();

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم حذف فرع: {$branchName}",
                'loggable_id' => $branchId,
                'loggable_type' => Branch::class,
                'action' => 'delete_branch',
            ]);

            DB::commit();

            return redirect()->route(get_area_name() . '.branches.index')
                ->with('success', 'تم حذف الفرع بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}