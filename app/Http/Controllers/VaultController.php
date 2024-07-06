<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Vault;
use App\Models\Branch;
use Illuminate\Http\Request;

class VaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaults = Vault::query();


        if(get_area_name() == "user") {
            $vaults = $vaults->where('branch_id', auth()->user()->branch_id);
        }

        $vaults = $vaults->get();
        return view('general.vaults.index', compact('vaults'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('general.vaults.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "branch_id" => "nullable",
            "name" => "required",
            "openning_balance" => "required",
        ]);

        $vault = new Vault();
        $vault->name = $request->name;
        $vault->openning_balance = $request->openning_balance;
        $vault->balance = $vault->openning_balance;
        $vault->branch_id = $request->branch_id;
        $vault->save();

        if($vault->balance > 0) {
            // create transaction for that
        }
        Log::create(['user_id' => auth()->id(), 'details' => "تم انشاء  الخزينه  " . $request->name]);

        return redirect()->route(get_area_name().".vaults.index")->with('success', 'تمت اضافه خزينة جديده بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vault $vault)
    {
        // show with transations
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vault $vault)
    {
        $branches = Branch::all();
        return view('general.vaults.edit', compact('vault','branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vault $vault)
    {
        $request->validate([
            "name" => "required",
            "branch_id" => "nullable",
        ]);

        $vault->update(['name' => $request->name, 'branch_id' => $request->branch_id]);
        Log::create(['user_id' => auth()->id(), 'details' => "تم تحديث اسم الخزينه الى " . $request->name]);
        return redirect()->route(get_area_name().'.vaults.index')->with('success', "تم تحديث الخزينة بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vault $vault)
    {
        if($vault->transactions->count()) {
            return redirect()->back()->withErrors(['لا يمكن حذف هذه الخزينة نظرا لوجود معاملات تحت بها']);
        }

        $vault->delete();
        return redirect()->route(get_area_name().'.vaults.index')->with('success', 'تم حذف الخزينة بنجاح');
    }
}
