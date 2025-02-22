<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vault;
use App\Models\VaultTransfer;
use App\Models\Log;
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

        if (auth()->user()->branch_id) {
            $query->whereHas('fromVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->orWhereHas('toVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            });
        }

        $vaultTransfers = $query->paginate(50);

        // Log viewing transfers
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم عرض قائمة التحويلات بين الخزائن"
        ]);

        return view('finance.vault_transfers.index', compact('vaultTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $from_vaults = auth()->user()->branch_id
            ? Vault::where('branch_id', auth()->user()->branch_id)->get()
            : Vault::where('id', 1)->get();

        $to_vaults = Vault::all();

        // Log access to create form
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم الدخول إلى صفحة إنشاء تحويل جديد بين الخزائن"
        ]);

        return view('finance.vault_transfers.create', compact('from_vaults', 'to_vaults'));
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
            return redirect()->back()->withErrors(['لا يمكنك التحويل من خزينة ليست مخصصة لفرعك.']);
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

        Transaction::create([
            'user_id' => auth()->id(),
            'desc' => "قيمة التحويل رقم #{$transfer->id}",
            'amount' => $request->amount,
            'branch_id' => auth()->user()->branch_id,
            'transaction_type_id' => 6,
            'type' => "withdrawal",
            'vault_id' => $from_vault->id,
            'balance' => $from_vault->balance,
        ]);

        // Log transfer creation
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء تحويل من خزينة رقم {$from_vault->id} إلى خزينة رقم {$request->to_vault_id} بمبلغ {$request->amount}",
            'loggable_id' => $transfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم إضافة التحويل بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VaultTransfer $vaultTransfer)
    {
        if ($vaultTransfer->toVault->branch_id && $vaultTransfer->toVault->branch_id != auth()->user()->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك عرض تحويل لفرع آخر.']);
        }

        // Log viewing of transfer
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم عرض تفاصيل التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

        return view('finance.vault_transfers.show', compact('vaultTransfer'));
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

        if ($vaultTransfer->status === 'approved') {
            return redirect()->back()->withErrors(['لا يمكن تعديل تحويل تمت الموافقة عليه.']);
        }

        $vaultTransfer->update($request->all());

        // Log update
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

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

        $vaultTransfer->delete();

        // Log deletion
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

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

        $to_vault = Vault::find($vaultTransfer->to_vault_id);
        $to_vault->increment('balance', $vaultTransfer->amount);

        // Log approval
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تمت الموافقة على التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

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

        $from_vault = Vault::find($vaultTransfer->from_vault_id);
        $from_vault->increment('balance', $vaultTransfer->amount);

        // Log rejection
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم رفض التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
        ]);

        return redirect()->route('finance.vault-transfers.index')->with('success', 'تم رفض التحويل بنجاح.');
    }
}
