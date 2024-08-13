<?php

namespace App\Http\Controllers;

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

        // Filtering
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

        // Retrieve transactions with pagination
        $transactions = $query->latest()->paginate(10);

        // Retrieve all transaction types for the filter dropdown
        $transactionTypes = TransactionType::all();
        $vaults = Vault::query();
        if(get_area_name() == "user") {
            $vaults = $vaults->where('branch_id', auth()->user()->branch_id);
        }

        $vaults = $vaults->get();
        // Return the view with data
        return view('general.transactions.index', compact('transactions', 'transactionTypes','vaults'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactionTypes = TransactionType::all();
        $branches = Branch::all(); // Assuming Branch model is already defined
        $vaults = Vault::all();
        return view('general.transactions.create', compact('transactionTypes', 'branches','vaults'));
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
            'vault_id' => "required|exists:vaults,id",
        ]);
    
        $validatedData['user_id'] = auth()->id();
        $transaction = Transaction::create($validatedData);
    
        $vault = Vault::findOrFail($request->vault_id);
    
        if ($request->type == "deposit") {
            $vault->increment('balance', $request->amount);
        } else {
            if ($vault->balance - $request->amount < 0) {
                return redirect()->back()->withErrors(["لا يوجد رصيد كافي في الخزينة المحددة"]);
            }
            $vault->decrement('balance', $request->amount);
        }
    
        // Save the updated vault balance in the transaction
        $transaction->balance = $vault->balance;
        $transaction->save();
    
        $vault->save();
    
        Log::create(['user_id' => auth()->user()->id, 'details' => 'تم إضافة معاملة جديدة: ' . $transaction->desc]);
    
        return redirect()->route(get_area_name() . '.transactions.index')
            ->with('success', 'تم إضافة معاملة جديدة بنجاح.');
    }
    


}
