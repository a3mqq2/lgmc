<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vault;
use App\Models\VaultTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaultTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = VaultTransfer::query()->with(['fromVault', 'toVault', 'user', 'branch']);
        if(auth()->user()->branch_id)
        {
            $query->whereHas('fromVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->orWhereHas('toVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            });

        }

        $vaultTransfers = $query->paginate(50);
        return view('finance.vault_transfers.index', compact('vaultTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->branch_id)
        {
            $from_vaults = Vault::where('branch_id', auth()->user()->branch_id)->get();
        } else {
            $from_vaults = Vault::where('id',1)->get();
        }

        $to_vaults = Vault::all();
        return view('finance.vault_transfers.create', compact('from_vaults','to_vaults'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_vault_id' => 'required|exists:vaults,id',
            'to_vault_id' => 'required|exists:vaults,id',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $from_vault = Vault::find($request->from_vault_id);

        if (auth()->user()->branch_id && $from_vault->branch_id != auth()->user()->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك التحويل من خزينة ليست مخصصه لفرعك.']);
        }

        if ($from_vault->balance < $request->amount) {
            return redirect()->back()->withErrors(['رصيد الخزينة غير كافٍ لإجراء هذا التحويل.']);
        }

        $transfer = VaultTransfer::create([
            'from_vault_id' => $request->from_vault_id,
            'to_vault_id' => $request->to_vault_id,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'branch_id' => auth()->user()->branch_id,
            'amount' => $request->amount,
        ]);

        $from_vault->decrement('balance', $request->amount);

        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->desc = "قيمة التحويل رقم #" . $transfer->id;
        $transaction->amount = $request->amount;
        $transaction->branch_id = auth()->user()->branch_id;
        $transaction->transaction_type_id = 6;
        $transaction->type = "withdrawal";
        $transaction->vault_id = $from_vault->id;
        $transaction->balance = $from_vault->balance;
        $transaction->save();

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم إضافة التحويل بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VaultTransfer $vaultTransfer)
    {

        if($vaultTransfer->toVault->branch_id && ($vaultTransfer->toVault->branch_id != auth()->user()->branch_id)) {
            return redirect()->back()->withErrors(['لا يمكنك عرض تحويل لفرع آخر.']);
        }



        return view('finance.vault_transfers.show', compact('vaultTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaultTransfer $vaultTransfer)
    {

        // add this condition to prevent editing approved transfers

        if ($vaultTransfer->status === 'approved') {
            return redirect()->back()->withErrors(['لا يمكن تعديل تحويل تمت الموافقة عليه.']);
        }

        if($vaultTransfer->toVault->branch_id && ($vaultTransfer->toVault->branch_id != auth()->user()->branch_id)) {
            return redirect()->back()->withErrors(['لا يمكنك تعديل تحويل لفرع آخر.']);
        }


        return view('finance.vault_transfers.edit', compact('vaultTransfer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VaultTransfer $vaultTransfer)
    {
        $request->validate([
            'from_vault_id' => 'required|exists:vaults,id',
            'to_vault_id' => 'required|exists:vaults,id',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $from_vault = Vault::find($request->from_vault_id);
        if ($vaultTransfer->status === 'approved') {
            return redirect()->back()->withErrors(['لا يمكن تعديل تحويل تمت الموافقة عليه.']);
        }

        if ($from_vault->balance < $request->amount) {
            return redirect()->back()->withErrors(['رصيد الخزينة غير كافٍ لإجراء هذا التحويل.']);
        }

        $vaultTransfer->update($request->all());

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم تحديث التحويل بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VaultTransfer $vaultTransfer)
    {
        if ($vaultTransfer->status === 'approved') {
            return redirect()->back()->withErrors(['لا يمكن حذف تحويل تمت الموافقة عليه.']);
        }

        $from_vault = Vault::find($vaultTransfer->from_vault_id);
        $from_vault->increment('balance', $vaultTransfer->amount);

        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->desc = "استرداد قيمة التحويل رقم #" . $vaultTransfer->id;
        $transaction->amount = $vaultTransfer->amount;
        $transaction->branch_id = auth()->user()->branch_id;
        $transaction->transaction_type_id = 5;
        $transaction->type = "deposit";
        $transaction->vault_id = $from_vault->id;
        $transaction->balance = $from_vault->balance;
        $transaction->save();

        
        $vaultTransfer->delete();

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم حذف التحويل بنجاح.');
    }

    /**
     * Approve the specified transfer.
     */
    public function approve(VaultTransfer $vaultTransfer, Request $request)
    {
        $vaultTransfer->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'notes' => $request->notes,
        ]);


        if($vaultTransfer->toVault->branch_id && ($vaultTransfer->toVault->branch_id != auth()->user()->branch_id)) {
            return redirect()->back()->withErrors(['لا يمكنك الموافقة على تحويل لفرع آخر.']);
        }

        $to_vault = Vault::find($vaultTransfer->to_vault_id);
        $to_vault->increment('balance', $vaultTransfer->amount);

        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->desc = "ايداع قيمة التحويل رقم #" . $vaultTransfer->id;
        $transaction->amount = $vaultTransfer->amount;
        $transaction->branch_id = auth()->user()->branch_id;
        $transaction->transaction_type_id = 5;
        $transaction->type = "deposit";
        $transaction->vault_id = $to_vault->id;
        $transaction->balance = $to_vault->balance;
        $transaction->save();


        return redirect()->route('finance.vault-transfers.index')->with('success', 'تمت الموافقة على التحويل بنجاح.');
    }

    /**
     * Reject the specified transfer.
     */
    public function reject(VaultTransfer $vaultTransfer, Request $request)
    {
        $vaultTransfer->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'notes' => $request->notes,
        ]);


        // depsoit amount back to the from vault
        $from_vault = Vault::find($vaultTransfer->from_vault_id);
        $from_vault->increment('balance', $vaultTransfer->amount);

        $transaction = new Transaction();
        $transaction->user_id = auth()->id();
        $transaction->desc = "استرداد قيمة التحويل رقم #" . $vaultTransfer->id;
        $transaction->amount = $vaultTransfer->amount;
        $transaction->branch_id = auth()->user()->branch_id;
        $transaction->transaction_type_id = 5;
        $transaction->type = "deposit";
        $transaction->vault_id = $from_vault->id;
        $transaction->balance = $from_vault->balance;
        $transaction->save();

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم رفض التحويل بنجاح.');
    }
}
