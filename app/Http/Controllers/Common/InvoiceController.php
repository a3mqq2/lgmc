<?php

namespace App\Http\Controllers\Common;

use App\Models\User;
use App\Models\Vault;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }


        public function index(Request $request)
        {
            // Start the query
            $query = Invoice::query();
    
            // Filter by invoiceable type (Doctor or MedicalFacility)
            if ($request->filled('invoiceable')) {
                $invoiceableType = $request->invoiceable === 'Doctor' ? 'App\Models\Doctor' : 'App\Models\MedicalFacility';
                $query->where('invoiceable_type', $invoiceableType);
            }
    
            // Filter by status (paid, unpaid, partial)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
    
            // Filter by user_id (who created the invoice)
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
    
            // Filter by license_id
            if ($request->filled('license_id')) {
                $query->where('license_id', $request->license_id);
            }
    
            // Filter by date range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
    
            // Filter by amount range
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }


            if(auth()->user()->branch_id)
            {
                $query->where('branch_id', auth()->user()->branch_id);
            }
    
            // Search by invoice_number or description
            if ($request->filled('search')) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('invoice_number', 'like', '%' . $request->search . '%')
                             ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
    
            // Paginate results
            $invoices = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
            $users = User::orderByDesc('id')->get();
            $vaults = Vault::when(auth()->user()->branch_id, function($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->when(!auth()->user()->branch_id, function($q) {
                $q->whereNull('branch_id');
            })->get();
            return view('general.invoices.index', compact('invoices','users','vaults'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return view('general.invoices.edit', compact('invoice'));
    }


    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'amount' => $request->amount,
        ]);

        return redirect()->route(get_area_name().'.invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }


    public function received(Invoice $invoice, Request $request)
    {

        try {
            DB::beginTransaction();
            if($invoice->status != InvoiceStatus::unpaid)
            {
                return redirect()->back()
                ->withErrors(['لا يمكن الاستلام من هذه الفاتورة']);
            }
    


    
            if($invoice->branch_id != auth()->user()->branch_id)
            {
                return redirect()->back()->withErrors(['لا يمكن استلام قيمة فاتورة لغير فرعها الحالي']);
            }
            $vault = auth()->user()->branch_id ? auth()->user()->branch->vault : Vault::first();
            $this->invoiceService->markAsPaid($vault,$invoice, $request->notes);
            DB::commit();
        } catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->route(get_area_name().'.invoices.index')
            ->with('success', 'تم تحديث حالة الفاتورة إلى مدفوعة.');
    }

    public function relief(Request $request, Invoice $invoice)
    {
        try {
            DB::beginTransaction();
            if($invoice->status != InvoiceStatus::unpaid)
            {
                return redirect()->back()
                ->withErrors(['لا يمكن الإعفاء من هذه الفاتورة']);
            }
    
            if($invoice->branch_id != auth()->user()->branch_id)
            {
                return redirect()->back()->withErrors(['لا يمكن إعفاء قيمة فاتورة لغير فرعها الحالي']);
            }
            $this->invoiceService->markAsRelief($invoice, $request->notes);
            DB::commit();
        } catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->route(get_area_name().'.invoices.index')
            ->with('success', 'تم تحديث حالة الفاتورة إلى معفاة.');
    }


    public function print(Invoice $invoice)
    {
        return view('general.invoices.print', compact('invoice'));
    }
}
