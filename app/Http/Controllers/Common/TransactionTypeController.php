<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\TransactionType;

class TransactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionTypes = TransactionType::all();
        return view('admin.transaction_types.index', compact('transactionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.transaction_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "type" => "required|in:deposit,withdrawal",
        ]);

        $transactionType = new TransactionType();
        $transactionType->name = $request->name;
        $transactionType->type = $request->type;
        $transactionType->save();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء تصنيف مالي جديد: " . $transactionType->name,
            'loggable_id' => $transactionType->id,
            'loggable_type' => TransactionType::class,
            'action' => 'create_transaction_type',
        ]);

        return redirect()->route(get_area_name() . '.transaction-types.index')
            ->with('success', 'تم إنشاء تصنيف مالي جديد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionType $transactionType)
    {
        // عرض التفاصيل إذا لزم الأمر
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionType $transactionType)
    {
        return view('admin.transaction_types.edit', compact('transactionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionType $transactionType)
    {
        $request->validate([
            "name" => "required",
            "type" => "required|in:deposit,withdrawal",
        ]);

        $transactionType->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تحديث تصنيف مالي: " . $transactionType->name,
            'loggable_id' => $transactionType->id,
            'loggable_type' => TransactionType::class,
            'action' => 'update_transaction_type',
        ]);

        return redirect()->route(get_area_name() . '.transaction-types.index')
            ->with('success', 'تم تحديث تصنيف مالي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionType $transactionType)
    {
        if ($transactionType->transactions->count()) {
            return redirect()->back()->withErrors(['لا يمكنك حذف تصنيف مالي يحتوي على معاملات']);
        }

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف تصنيف مالي: " . $transactionType->name,
            'loggable_id' => $transactionType->id,
            'loggable_type' => TransactionType::class,
            'action' => 'delete_transaction_type',
        ]);

        $transactionType->delete();

        return redirect()->route(get_area_name() . '.transaction-types.index')
            ->with('success', 'تم حذف تصنيف مالي بنجاح');
    }
}
