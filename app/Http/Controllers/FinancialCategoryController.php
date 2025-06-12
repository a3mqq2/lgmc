<?php

namespace App\Http\Controllers;

use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinancialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FinancialCategory::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->latest()->paginate(15)->withQueryString();

        return view('finance.financial-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.financial-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:financial_categories,name',
            'type' => 'required|in:deposit,withdrawal'
        ], [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.unique' => 'اسم التصنيف موجود مسبقاً',
            'name.max' => 'اسم التصنيف يجب أن يكون أقل من 255 حرف',
            'type.required' => 'نوع التصنيف مطلوب',
            'type.in' => 'نوع التصنيف يجب أن يكون إيداع أو سحب'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            FinancialCategory::create([
                'name' => $request->name,
                'type' => $request->type
            ]);

            return redirect()->route('finance.financial-categories.index')
                ->with('success', 'تم إنشاء التصنيف المالي بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('حدث خطأ أثناء إنشاء التصنيف المالي')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialCategory $financialCategory)
    {
        return view('finance.financial-categories.show', compact('financialCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialCategory $financialCategory)
    {
        return view('finance.financial-categories.edit', compact('financialCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialCategory $financialCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:financial_categories,name,' . $financialCategory->id,
            'type' => 'required|in:deposit,withdrawal'
        ], [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.unique' => 'اسم التصنيف موجود مسبقاً',
            'name.max' => 'اسم التصنيف يجب أن يكون أقل من 255 حرف',
            'type.required' => 'نوع التصنيف مطلوب',
            'type.in' => 'نوع التصنيف يجب أن يكون إيداع أو سحب'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $financialCategory->update([
                'name' => $request->name,
                'type' => $request->type
            ]);

            return redirect()->route('finance.financial-categories.index')
                ->with('success', 'تم تحديث التصنيف المالي بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('حدث خطأ أثناء تحديث التصنيف المالي')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialCategory $financialCategory)
    {
        try {
            $financialCategory->delete();

            return redirect()->route('finance.financial-categories.index')
                ->with('success', 'تم حذف التصنيف المالي بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('حدث خطأ أثناء حذف التصنيف المالي');
        }
    }
}