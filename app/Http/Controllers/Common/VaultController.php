<?php

namespace App\Http\Controllers\Common;

use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VaultTransfer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaults = Vault::query();

        if (auth()->user()->branch_id) {
            $vaults->where('branch_id', auth()->user()->branch_id);
        } else {
            $vaults->where('branch_id', null);
        }

        $vaults = $vaults->get();
        return view('general.vaults.index', compact('vaults'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('general.vaults.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "openning_balance" => "required",
        ]);




        $vault = new Vault();
        $vault->name = $request->name;
        $vault->openning_balance = $request->openning_balance;
        $vault->balance = $vault->openning_balance;
        $vault->branch_id = auth()->user()->branch_id;
        $vault->save();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم انشاء الحساب: " . $request->name,
            'loggable_id' => $vault->id,
            'loggable_type' => Vault::class,
            'action' => 'create_vault',
        ]);

        return redirect()->route(get_area_name() . ".vaults.index")->with('success', 'تمت إضافة حساب جديدة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vault $vault)
    {
        // عرض المعاملات المتعلقة بالحساب
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vault $vault)
    {
        $users = User::all();
        return view('general.vaults.edit', compact('vault','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vault $vault)
    {
        $request->validate([
            "name" => "required",
            "user_id" => "required",
        ]);



        $user = User::find($request->user_id);

        $vault->update([
            'name' => $request->name,
            'branch_id' => $request->branch_id,
            'user_id' => $request->user_id,
            "branch_id" => $user->branch_id,
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تحديث اسم الحساب إلى: " . $request->name,
            'loggable_id' => $vault->id,
            'loggable_type' => Vault::class,
            'action' => 'update_vault',
        ]);

        return redirect()->route(get_area_name() . '.vaults.index')->with('success', "تم تحديث الحساب بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vault $vault)
    {
        if ($vault->transactions->count()) {
            return redirect()->back()->withErrors(['لا يمكن حذف هذه الحساب نظرًا لوجود معاملات مرتبطة بها']);
        }

        $vault->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف الحساب: " . $vault->name,
            'loggable_id' => $vault->id,
            'loggable_type' => Vault::class,
            'action' => 'delete_vault',
        ]);

        return redirect()->route(get_area_name() . '.vaults.index')->with('success', 'تم حذف الحساب بنجاح');
    }


    public function closeCustody(Vault $vault)
    {
        if($vault->balance <= 0)
        {
            return redirect()->back()->withErrors(['لا يمكن اغلاق الخزينة دون وجود قيمة ']);
        }

        $fromVault = $vault;
        $toVault = Vault::where('branch_id', $vault->branch_id)
            ->where('user_id', null)
            ->where('id', '!=', $vault->id)
            ->first();

        try {
            DB::beginTransaction();
            // create transaction with type transfer
            $amount = $fromVault->balance;
            $transaction = new Transaction();
            $transaction->amount = $amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $fromVault->id;
            $transaction->type = "withdrawal";
            $transaction->desc = "اغلاق العهدة وتسوية الرصيد لخزينة " . $fromVault->name;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = 0;
            $transaction->financial_category_id = 4;
            $transaction->save();

            $fromVault->decrement('balance', $fromVault->balance);
            $toVault->increment('balance', $amount);

            $transactionTo = new Transaction();
            $transactionTo->amount = $amount;
            $transactionTo->user_id = auth()->id();
            $transactionTo->vault_id = $toVault->id;
            $transactionTo->type = "deposit";
            $transactionTo->desc = "استلام عهدة من خزينة " . $fromVault->name;
            $transactionTo->branch_id = auth()->user()->branch_id;
            $transactionTo->balance = $toVault->balance;
            $transactionTo->financial_category_id = 4;
            $transactionTo->save();


            $transfer = VaultTransfer::create([
                'from_vault_id' => $fromVault->id,
                'to_vault_id' => $toVault->id,
                'description' => "اغلاق عهدة خزينة " . $fromVault->name . " وتحويل الرصيد إلى خزينة " . $toVault->name,
                'user_id' => Auth::id(),
                'branch_id' => auth()->user()->branch_id,
                'amount' => $amount,
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);


            // update all transfers under from vault by vault_transfer_id

            DB::table('transactions')
            ->where('vault_id', $fromVault->id)
            ->whereNull('vault_transfer_id')
            ->update(['vault_transfer_id' => $transfer->id, 'is_transfered' => true]);

            

            DB::commit();
        } catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['حدث خطأ أثناء إغلاق الخزينة: ' . $e->getMessage()]);
        }

        return redirect()->route(get_area_name() . '.vaults.index')->with('success', 'تم اغلاق الخزينة بنجاح');
    }
}