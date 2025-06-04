<?php

namespace App\Http\Controllers\Common;

use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transaction_types = TransactionType::all();
        $branches = Branch::all();

        $vaults = Vault::query();
        if (get_area_name() == "user" || get_area_name() == "finance") {
            $vaults->where('branch_id', auth()->user()->branch_id);
        }
        $vaults = $vaults->get();

        return view('general.transactions.create', compact('transaction_types', 'branches', 'vaults'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'desc' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal',
            'vault_id' => 'required|exists:vaults,id',
        ]);

        $vault = Vault::findOrFail($request->vault_id);

        if ($request->type == "withdrawal" && $vault->balance < $request->amount) {
            return redirect()->back()->withErrors(["لا يوجد رصيد كافي في الحساب المحددة"]);
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

    public function index(Request $request)
    {
        $query = Transaction::query();
    
        // الفلاتر الأساسية
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    
        if ($request->filled('vault_id')) {
            $query->where('vault_id', $request->vault_id);
        }
    
        if ($request->filled('desc')) {
            $query->where('desc', 'like', '%' . $request->desc . '%');
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        // فلاتر التاريخ
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
    
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
    
        // فلتر نطاق المبلغ
        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case '0-100':
                    $query->where('amount', '<', 100);
                    break;
                case '100-500':
                    $query->whereBetween('amount', [100, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('amount', [500, 1000]);
                    break;
                case '1000+':
                    $query->where('amount', '>', 1000);
                    break;
            }
        }
    
        // فلتر المبلغ المحدد
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
    
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
    
        // فلتر الرقم المرجعي
        if ($request->filled('reference_id')) {
            $query->where('reference_id', 'like', '%' . $request->reference_id . '%');
        }
    
        // فلتر الفرع (حسب المنطقة)
        if (get_area_name() == "finance") {
            if (auth()->user()->branch_id) {
                $query->where('branch_id', auth()->user()->branch_id);
            }
        } elseif (get_area_name() == "admin") {
            // الأدمن يمكنه رؤية جميع الفروع أو فرع محدد
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        } else {
            // المستخدمون العاديون يرون فرعهم فقط
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        // فلتر حسب التاريخ المحدد
        if ($request->filled('date_filter')) {
            $today = now();
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $today->subDay());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [
                        $today->startOfWeek(),
                        $today->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', $today->month)
                          ->whereYear('created_at', $today->year);
                    break;
                case 'last_month':
                    $lastMonth = $today->subMonth();
                    $query->whereMonth('created_at', $lastMonth->month)
                          ->whereYear('created_at', $lastMonth->year);
                    break;
            }
        }
    
        // الترتيب
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'created_at_desc':
                    $query->orderByDesc('created_at');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at');
                    break;
                case 'amount_desc':
                    $query->orderByDesc('amount');
                    break;
                case 'amount_asc':
                    $query->orderBy('amount');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
    
        // تحميل العلاقات
        $transactions = $query->with(['user:id,name,email', 'vault:id,name,balance'])
                              ->paginate(20);
    
        // حساب الإحصائيات
        $statistics = $this->calculateStatistics($request);
    
        // الحصول على البيانات الإضافية
        $users = User::select('id', 'name', 'email')
                     ->when(get_area_name() !== 'admin', function($q) {
                         return $q->where('branch_id', auth()->user()->branch_id);
                     })
                     ->orderBy('name')
                     ->get();
    
        $vaults = Vault::select('id', 'name', 'balance', 'branch_id')
                      ->when(auth()->user()->branch_id, function($q) {
                          return $q->where('branch_id', auth()->user()->branch_id);
                      })
                      ->orderBy('name')
                      ->get();
    
        $branches = collect();
        if (get_area_name() == 'admin') {
            $branches = Branch::select('id', 'name')->orderBy('name')->get();
        }
    
        return view('general.transactions.index', compact(
            'transactions', 
            'statistics', 
            'users', 
            'vaults', 
            'branches'
        ));
    }
    
    /**
     * حساب الإحصائيات المالية
     */
    private function calculateStatistics(Request $request)
    {
        $query = Transaction::query();
    
        // تطبيق نفس الفلاتر المستخدمة في الجدول
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    
        if ($request->filled('vault_id')) {
            $query->where('vault_id', $request->vault_id);
        }
    
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
    
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        // فلتر الفرع
        if (get_area_name() == "finance" && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        // حساب الإحصائيات
        $deposits = $query->clone()->where('type', 'deposit')->sum('amount');
        $withdrawals = $query->clone()->where('type', 'withdrawal')->sum('amount');
        $totalTransactions = $query->count();
        $netBalance = $deposits - $withdrawals;
    
        // إحصائيات إضافية
        $avgTransactionAmount = $totalTransactions > 0 ? 
            $query->clone()->avg('amount') : 0;
    
        $todayTransactions = $query->clone()
            ->whereDate('created_at', today())
            ->count();
    
        $todayDeposits = $query->clone()
            ->where('type', 'deposit')
            ->whereDate('created_at', today())
            ->sum('amount');
    
        $todayWithdrawals = $query->clone()
            ->where('type', 'withdrawal')
            ->whereDate('created_at', today())
            ->sum('amount');
    
        return [
            'total_deposits' => $deposits,
            'total_withdrawals' => $withdrawals,
            'net_balance' => $netBalance,
            'total_transactions' => $totalTransactions,
            'avg_transaction_amount' => $avgTransactionAmount,
            'today_transactions' => $todayTransactions,
            'today_deposits' => $todayDeposits,
            'today_withdrawals' => $todayWithdrawals,
        ];
    }


    
    /**
     * طباعة المعاملات
     */
    public function print(Request $request)
    {
        $query = Transaction::query();
        
        // تطبيق نفس الفلاتر
        $this->applyFilters($query, $request);
        
        $transactions = $query->with(['user', 'vault'])->get();
        $statistics = $this->calculateStatistics($request);
        
        return view('general.transactions.print', compact('transactions', 'statistics'));
    }
    
    /**
     * حذف جماعي للمعاملات
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id'
        ]);
    

        DB::beginTransaction();
        try {
            $transactions = Transaction::whereIn('id', $request->transaction_ids)
                                     ->with('vault')
                                     ->get();
    
            foreach ($transactions as $transaction) {
                // عكس المعاملة من رصيد الخزينة
                if ($transaction->type === 'deposit') {
                    $transaction->vault->decrement('balance', $transaction->amount);
                } elseif ($transaction->type === 'withdrawal') {
                    $transaction->vault->increment('balance', $transaction->amount);
                }
    
                $transaction->delete();
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المعاملات المحددة بنجاح'
            ]);
    
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Bulk delete failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المعاملات'
            ], 500);
        }
    }
    
    /**
     * تطبيق الفلاتر على الاستعلام
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    
        if ($request->filled('vault_id')) {
            $query->where('vault_id', $request->vault_id);
        }
    
        if ($request->filled('desc')) {
            $query->where('desc', 'like', '%' . $request->desc . '%');
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
    
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
    
        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case '0-100':
                    $query->where('amount', '<', 100);
                    break;
                case '100-500':
                    $query->whereBetween('amount', [100, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('amount', [500, 1000]);
                    break;
                case '1000+':
                    $query->where('amount', '>', 1000);
                    break;
            }
        }
    
        // فلتر الفرع
        if (get_area_name() == "finance" && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        return $query;
    }
}
