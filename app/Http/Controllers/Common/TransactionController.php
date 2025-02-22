<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Models\Vault;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('vault_id')) {
            $query->where('vault_id', $request->vault_id);
        }

        if ($request->filled('desc')) {
            $query->where('desc', 'like', '%' . $request->desc . '%');
        }

        if ($request->filled('transaction_type_id')) {
            $query->where('transaction_type_id', $request->transaction_type_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if (get_area_name() == "finance") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $transactions = $query->latest()->paginate(10);
        $transactionTypes = TransactionType::all();

        $vaults = Vault::query();
        if (auth()->user()->branch_id) {
            $vaults->where('branch_id', auth()->user()->branch_id);
        }
        $vaults = $vaults->get();

        return view('general.transactions.index', compact('transactions', 'transactionTypes', 'vaults'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactionTypes = TransactionType::all();
        $branches = Branch::all();

        $vaults = Vault::query();
        if (get_area_name() == "user" || get_area_name() == "finance") {
            $vaults->where('branch_id', auth()->user()->branch_id);
        }
        $vaults = $vaults->get();

        return view('general.transactions.create', compact('transactionTypes', 'branches', 'vaults'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'desc' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'type' => 'required|in:deposit,withdrawal',
            'vault_id' => 'required|exists:vaults,id',
        ]);

        $vault = Vault::findOrFail($request->vault_id);

        if ($request->type == "withdrawal" && $vault->balance < $request->amount) {
            return redirect()->back()->withErrors(["لا يوجد رصيد كافي في الخزينة المحددة"]);
        }

        $validatedData['user_id'] = auth()->id();
        $transaction = Transaction::create($validatedData);

        if ($request->type == "deposit") {
            $vault->increment('balance', $request->amount);
        } else {
            $vault->decrement('balance', $request->amount);
        }

        $transaction->update([
            'balance' => $vault->balance,
            'branch_id' => $vault->branch_id,
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'details' => 'تم إضافة معاملة جديدة: ' . $transaction->desc,
            'loggable_id' => $transaction->id,
            'loggable_type' => Transaction::class,
            'action' => 'create_transaction',
        ]);

        return redirect()->route(get_area_name() . '.transactions.index')
            ->with('success', 'تم إضافة معاملة جديدة بنجاح.');
    }
}
