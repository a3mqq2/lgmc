<?php

namespace App\Http\Controllers\Common;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\FinancialCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $financialCategories = FinancialCategory::all();
        $vaults = Vault::query();
        if (auth()->user()->branch_id) {
            $vaults->where('branch_id', auth()->user()->branch_id);
        } else {
            $vaults->where('branch_id', null);
        }
        $vaults = $vaults->get();

        return view('general.transactions.create', compact('branches', 'vaults','financialCategories'));
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
            'financial_category_id' => 'required|exists:financial_categories,id',
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
    

        if($request->filled('financial_category_id'))
        {
            $query->where('financial_category_id', $request->financial_category_id);
        }

        if($request->filled('is_transfered'))
        {
            if($request->is_transfered == 1)
            {
                $query->whereNotNull('vault_transfer_id');
            } else {
                $query->whereNull('vault_transfer_id');
            }
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
                              ->paginate(100);
    
        // حساب الإحصائيات
        $statistics = $this->calculateStatistics($request);
    
        // الحصول على البيانات الإضافية
        $users = User::select('id', 'name', 'email')
                     ->when(auth()->user()->branch_id, function($q) {
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


        $financialCategories = FinancialCategory::all();
    
        return view('general.transactions.index', compact(
            'transactions', 
            'statistics', 
            'users', 
            'vaults', 
            'branches',
            'financialCategories',
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



    public function report(Request $request)
    {
        $request->validate([
            "from_date" => "required|date",
            "to_date" => "required|date",
            "vault_id" => "nullable|exists:vaults,id",
            "type" => "nullable|in:deposit,withdrawal,transfer",
            "format" => "nullable|in:view,pdf,excel,print"
        ]);
    
        $query = Transaction::with(['vault', 'user', 'financialCategory']);
    
        // فلترة حسب الخزينة
        if ($request->vault_id) {
            $query->where('vault_id', $request->vault_id);
        }
    
        // فلترة حسب نوع المعاملة
        if ($request->type) {
            $query->where('type', $request->type);
        }
    
        // فلترة حسب الفرع للمستخدمين العاديين
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        // فلترة حسب التاريخ
        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();
        $query->whereBetween('created_at', [$from, $to]);
    
        $transactions = $query->orderBy('created_at', 'desc')->get();
    
        // حساب الإجماليات
        $totalDeposits = $transactions->where('type', 'deposit')->sum('amount');
        $totalWithdrawals = $transactions->where('type', 'withdrawal')->sum('amount');
        $netBalance = $totalDeposits - $totalWithdrawals;
    
        // معلومات إضافية للتقرير
        $reportData = [
            'from_date' => $from,
            'to_date' => $to,
            'vault' => $request->vault_id ? Vault::find($request->vault_id) : null,
            'type_filter' => $request->type,
            'total_deposits' => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'net_balance' => $netBalance,
            'transaction_count' => $transactions->count(),
            'generated_at' => now(),
            'generated_by' => auth()->user()
        ];
    
        // تحديد نوع العرض
        $format = $request->get('format', 'view');
    
        switch ($format) {
            case 'pdf':
                return $this->generatePdfReport($transactions, $reportData);
                
            case 'excel':
                return $this->generateExcelReport($transactions, $reportData);
                
            case 'print':
                return view('general.reports.transactions_print', compact('transactions', 'reportData'));
                
            default:
                return view('general.reports.transactions', compact('transactions', 'reportData'));
        }
    }
    
    /**
     * إنشاء تقرير PDF
     */
    private function generatePdfReport($transactions, $reportData)
    {
        $pdf = Pdf::loadView('general.reports.transactions_pdf', compact('transactions', 'reportData'));
        
        $filename = 'transactions_report_' . $reportData['from_date']->format('Y-m-d') . '_to_' . $reportData['to_date']->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * إنشاء تقرير Excel
     */
    private function generateExcelReport($transactions, $reportData)
    {
        $filename = 'transactions_report_' . $reportData['from_date']->format('Y-m-d') . '_to_' . $reportData['to_date']->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new TransactionsExport($transactions, $reportData), $filename);
    }
    
    /**
     * طباعة التقرير
     */
    public function reportPrint(Request $request)
    {
        // نفس منطق report() ولكن مع عرض للطباعة
        return $this->report($request->merge(['format' => 'print']));
    }
    
    /**
     * تصدير المعاملات المحددة
     */
    public function export(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'format' => 'required|in:pdf,excel,csv'
        ]);
    
        $transactions = Transaction::with(['vault', 'user', 'financialCategory'])
            ->whereIn('id', $request->transaction_ids)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $reportData = [
            'total_deposits' => $transactions->where('type', 'deposit')->sum('amount'),
            'total_withdrawals' => $transactions->where('type', 'withdrawal')->sum('amount'),
            'transaction_count' => $transactions->count(),
            'generated_at' => now(),
            'generated_by' => auth()->user(),
            'export_type' => 'selected_transactions'
        ];
    
        $reportData['net_balance'] = $reportData['total_deposits'] - $reportData['total_withdrawals'];
    
        switch ($request->format) {
            case 'pdf':
                return $this->generatePdfReport($transactions, $reportData);
                
            case 'excel':
                return $this->generateExcelReport($transactions, $reportData);
                
            case 'csv':
                return $this->generateCsvReport($transactions, $reportData);
        }
    }
    
    /**
     * إنشاء تقرير CSV
     */
    private function generateCsvReport($transactions, $reportData)
    {
        $filename = 'transactions_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
    
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للدعم العربي
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'رقم المعاملة',
                'نوع المعاملة', 
                'الخزينة',
                'المستخدم',
                'التصنيف المالي',
                'المبلغ',
                'الوصف',
                'التاريخ',
                'الوقت'
            ]);
    
            // البيانات
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->type == 'deposit' ? 'إيداع' : 'سحب',
                    $transaction->vault->name,
                    $transaction->user->name,
                    $transaction->financialCategory->name ?? 'غير محدد',
                    number_format($transaction->amount, 2),
                    $transaction->desc,
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->created_at->format('H:i:s')
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * API للحصول على إحصائيات سريعة
     */
    public function quickStats(Request $request)
    {
        $request->validate([
            'vault_id' => 'nullable|exists:vaults,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);
    
        $query = Transaction::query();
    
        if ($request->vault_id) {
            $query->where('vault_id', $request->vault_id);
        }
    
        if ($request->from_date && $request->to_date) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }
    
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        $transactions = $query->get();
    
        return response()->json([
            'total_deposits' => $transactions->where('type', 'deposit')->sum('amount'),
            'total_withdrawals' => $transactions->where('type', 'withdrawal')->sum('amount'),
            'transaction_count' => $transactions->count(),
            'net_balance' => $transactions->where('type', 'deposit')->sum('amount') - $transactions->where('type', 'withdrawal')->sum('amount')
        ]);
    }

}
