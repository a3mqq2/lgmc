<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VaultTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VaultTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = VaultTransfer::query()->with(['fromVault', 'toVault', 'user', 'branch']);

        // Base branch filtering
        if (auth()->user()->branch_id) {
            $query->whereHas('fromVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->orWhereHas('toVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            });
        }

        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        // Get paginated results
        $vaultTransfers = $query->paginate(50);

        // Calculate total amount for filtered results
        $totalAmount = $this->calculateTotalAmount($request);

        // Get data for filter dropdowns
        $filterData = $this->getFilterData();

        return view('finance.vault_transfers.index', array_merge([
            'vaultTransfers' => $vaultTransfers,
            'totalAmount' => $totalAmount,
        ], $filterData));
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // From vault filter
        if ($request->filled('from_vault_id')) {
            $query->where('from_vault_id', $request->from_vault_id);
        }

        // To vault filter
        if ($request->filled('to_vault_id')) {
            $query->where('to_vault_id', $request->to_vault_id);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Branch filter (only for admin users)
        if ($request->filled('branch_id') && !auth()->user()->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        // Amount range filter
        if ($request->filled('amount_from')) {
            $query->where('amount', '>=', $request->amount_from);
        }

        if ($request->filled('amount_to')) {
            $query->where('amount', '<=', $request->amount_to);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
    }

    /**
     * Apply sorting to the query
     */
    private function applySorting($query, Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        switch ($sortField) {
            case 'from_vault':
                $query->join('vaults as from_vaults', 'vault_transfers.from_vault_id', '=', 'from_vaults.id')
                      ->orderBy('from_vaults.name', $sortDirection)
                      ->select('vault_transfers.*');
                break;

            case 'to_vault':
                $query->join('vaults as to_vaults', 'vault_transfers.to_vault_id', '=', 'to_vaults.id')
                      ->orderBy('to_vaults.name', $sortDirection)
                      ->select('vault_transfers.*');
                break;

            case 'user':
                $query->join('users', 'vault_transfers.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortDirection)
                      ->select('vault_transfers.*');
                break;

            case 'amount':
                $query->orderBy('amount', $sortDirection);
                break;

            case 'status':
                $query->orderBy('status', $sortDirection);
                break;

            case 'created_at':
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }
    }

    /**
     * Calculate total amount for filtered results
     */
    private function calculateTotalAmount(Request $request)
    {
        $query = VaultTransfer::query();

        // Apply same branch filtering
        if (auth()->user()->branch_id) {
            $query->whereHas('fromVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->orWhereHas('toVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            });
        }

        // Apply same filters
        $this->applyFilters($query, $request);

        return $query->sum('amount');
    }

    /**
     * Get data for filter dropdowns
     */
    private function getFilterData()
    {
        $baseVaultQuery = Vault::query();
        $baseUserQuery = User::query();

        // Limit data based on user's branch
        if (auth()->user()->branch_id) {
            $fromVaults = Vault::where('branch_id', auth()->user()->branch_id)->get();
            $toVaults = Vault::all(); // Can transfer to any vault
            $users = User::where('branch_id', auth()->user()->branch_id)->get();
            $branches = collect(); // Empty for branch users
        } else {
            $fromVaults = Vault::all();
            $toVaults = Vault::all();
            $users = User::all();
            $branches = Branch::all();
        }

        return [
            'fromVaults' => $fromVaults,
            'toVaults' => $toVaults,
            'users' => $users,
            'branches' => $branches,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $from_vaults = auth()->user()->branch_id
            ? Vault::where('branch_id', auth()->user()->branch_id)->get()
            : Vault::where('id', 1)->get();

        $to_vaults = Vault::where('id', 1)->orWhere('branch_id', auth()->user()->branch_id)->get();

        if(!auth()->user()->branch_id) {
            $to_vaults = Vault::where('id', '!=', 1)->whereNull('user_id')->get();
        }

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
            return redirect()->back()->withErrors(['لا يمكنك التحويل من حساب ليست مخصصة لفرعك.']);
        }

        if ($from_vault->balance < $request->amount) {
            return redirect()->back()->withErrors(['رصيد الحساب غير كافٍ لإجراء هذا التحويل.']);
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
            'type' => "withdrawal",
            'vault_id' => $from_vault->id,
            'balance' => $from_vault->balance,
            'financial_category_id' => 5, 
        ]);

        // Log transfer creation
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء تحويل من حساب رقم {$from_vault->id} إلى حساب رقم {$request->to_vault_id} بمبلغ {$request->amount}",
            'loggable_id' => $transfer->id,
            'loggable_type' => VaultTransfer::class,
            "action" => "create_vault_transfer",
        ]);

        return redirect()->route(get_area_name() . '.vault-transfers.index')->with('success', 'تم إضافة التحويل بنجاح.');
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
            "action" => "view_vault_transfer",
        ]);

        return view('finance.vault_transfers.show', compact('vaultTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaultTransfer $vaultTransfer)
    {
        if ($vaultTransfer->status !== 'pending') {
            return redirect()->back()->withErrors(['لا يمكن تعديل تحويل غير معلق.']);
        }

        if ($vaultTransfer->fromVault->branch_id != auth()->user()->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك تعديل تحويل من فرع آخر.']);
        }

        $from_vaults = auth()->user()->branch_id
            ? Vault::where('branch_id', auth()->user()->branch_id)->get()
            : Vault::where('id', 1)->get();

        $to_vaults = Vault::where('id', 1)->get();

        if(!auth()->user()->branch_id) {
            $to_vaults = Vault::where('id', '!=', 1)->whereNull('user_id')->get();
        }

        return view('finance.vault_transfers.edit', compact('vaultTransfer', 'from_vaults', 'to_vaults'));
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
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($vaultTransfer->status !== 'pending') {
            return redirect()->back()->withErrors(['لا يمكن تعديل تحويل غير معلق.']);
        }

        // Restore old amount to from vault
        $old_from_vault = Vault::find($vaultTransfer->from_vault_id);
        $old_from_vault->increment('balance', $vaultTransfer->amount);

        // Check new from vault balance
        $new_from_vault = Vault::find($request->from_vault_id);
        if ($new_from_vault->balance < $request->amount) {
            // Restore the old deduction if validation fails
            $old_from_vault->decrement('balance', $vaultTransfer->amount);
            return redirect()->back()->withErrors(['رصيد الحساب الجديد غير كافٍ لإجراء هذا التحويل.']);
        }

        // Update vault transfer
        $vaultTransfer->update([
            'from_vault_id' => $request->from_vault_id,
            'to_vault_id' => $request->to_vault_id,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        // Deduct new amount from new vault
        $new_from_vault->decrement('balance', $request->amount);

        // Log update
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
            "action" => "update_vault_transfer",
        ]);

        return redirect()->route(get_area_name() . '.vault-transfers.index')->with('success', 'تم تحديث التحويل بنجاح.');
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

        // Delete related transaction
        Transaction::where('desc', 'like', "%#{$vaultTransfer->id}%")
                   ->where('vault_id', $from_vault->id)
                   ->where('type', 'withdrawal')
                   ->delete();

        $vaultTransfer->delete();

        // Log deletion
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
            "action" => "delete_vault_transfer",
        ]);

        return redirect()->route(get_area_name() . '.vault-transfers.index')->with('success', 'تم حذف التحويل بنجاح.');
    }

    /**
     * Approve the specified transfer.
     */
    public function approve(VaultTransfer $vaultTransfer, Request $request)
    {
        if ($vaultTransfer->status !== 'pending') {
            return redirect()->back()->withErrors(['لا يمكن الموافقة على تحويل غير معلق.']);
        }

        $vaultTransfer->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'notes' => $request->approve_note,
        ]);

        $to_vault = Vault::find($vaultTransfer->to_vault_id);
        $to_vault->increment('balance', $vaultTransfer->amount);

        // Create transaction for the to vault
        Transaction::create([
            'user_id' => auth()->id(),
            'desc' => "قيمة التحويل رقم #{$vaultTransfer->id}",
            'amount' => $vaultTransfer->amount,
            'branch_id' => $to_vault->branch_id,
            'type' => "deposit",
            'vault_id' => $to_vault->id,
            'balance' => $to_vault->balance,
            'financial_category_id' => 5, // Assuming 5 is the category for vault transfers
        ]);

        // Log approval
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تمت الموافقة على التحويل رقم #{$vaultTransfer->id}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
            "action" => "approve_vault_transfer",
        ]);

        return redirect()->route(get_area_name() . '.vault-transfers.index')->with('success', 'تمت الموافقة على التحويل بنجاح.');
    }

    /**
     * Reject the specified transfer.
     */
    public function reject(VaultTransfer $vaultTransfer, Request $request)
    {
        $request->validate([
            'reject_note' => 'required|string|max:500'
        ]);

        if ($vaultTransfer->status !== 'pending') {
            return redirect()->back()->withErrors(['لا يمكن رفض تحويل غير معلق.']);
        }

        $vaultTransfer->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'notes' => $request->reject_note,
        ]);

        $from_vault = Vault::find($vaultTransfer->from_vault_id);
        $from_vault->increment('balance', $vaultTransfer->amount);

        // Delete the withdrawal transaction since transfer is rejected
        Transaction::where('desc', 'like', "%#{$vaultTransfer->id}%")
                   ->where('vault_id', $from_vault->id)
                   ->where('type', 'withdrawal')
                   ->delete();

        // Log rejection
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم رفض التحويل رقم #{$vaultTransfer->id} - السبب: {$request->reject_note}",
            'loggable_id' => $vaultTransfer->id,
            'loggable_type' => VaultTransfer::class,
            "action" => "reject_vault_transfer",
        ]);

        return redirect()->route(get_area_name() . '.vault-transfers.index')->with('success', 'تم رفض التحويل بنجاح.');
    }

    /**
     * Export filtered transfers to Excel/CSV
     */
    public function export(Request $request)
    {
        $query = VaultTransfer::query()->with(['fromVault', 'toVault', 'user', 'branch']);

        // Apply same branch filtering
        if (auth()->user()->branch_id) {
            $query->whereHas('fromVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->orWhereHas('toVault', function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            });
        }

        // Apply filters
        $this->applyFilters($query, $request);

        $transfers = $query->get();

        // You can implement Excel export using Laravel Excel package
        // For now, returning a simple CSV response
        $filename = 'vault_transfers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transfers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'من الحساب',
                'إلى الحساب', 
                'المبلغ',
                'الوصف',
                'المستخدم',
                'الفرع',
                'الحالة',
                'تاريخ الإنشاء',
                'تاريخ الموافقة',
                'تاريخ الرفض'
            ]);

            // CSV data
            foreach ($transfers as $transfer) {
                fputcsv($file, [
                    $transfer->id,
                    $transfer->fromVault->name ?? '-',
                    $transfer->toVault->name ?? '-',
                    $transfer->amount,
                    $transfer->description ?? '',
                    $transfer->user->name ?? '-',
                    $transfer->branch->name ?? '-',
                    $transfer->status === 'approved' ? 'موافق عليه' : ($transfer->status === 'rejected' ? 'مرفوض' : 'قيد الانتظار'),
                    $transfer->created_at->format('Y-m-d H:i:s'),
                    $transfer->approved_at ? $transfer->approved_at->format('Y-m-d H:i:s') : '',
                    $transfer->rejected_at ? $transfer->rejected_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
