<?php

namespace App\Http\Controllers;

use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Log;
use App\Enums\PricingType;
use App\Models\Transaction;
use App\Models\TotalInvoice;
use Illuminate\Http\Request;
use App\Enums\MembershipStatus;

class TotalInvoiceController extends Controller
{
    /**
     * Show form for creating a total invoice.
     */
    public function show(Doctor $doctor)
    {
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم فتح صفحة إنشاء فاتورة كلية للطبيب: {$doctor->name}",
            'loggable_id' => $doctor->id,
            'loggable_type' => Doctor::class,
            "action" => "create_total_invoice",
        ]);

        return view('finance.total_invoices.create', compact('doctor'));
    }

    /**
     * Store a newly created total invoice.
     */
    public function store(Request $request)
    {
        if (auth()->user()->branch_id === null) {
            return redirect()->route(get_area_name() . '.total_invoices.index')->with('error', 'لا يمكنك إضافة فاتورة كلية لأنك لم تحدد فرعك بعد.');
        }

        $request->validate([
            'total_amount' => 'required|numeric|min:1',
            'invoices' => 'required|array',
            'notes' => 'nullable|string|max:255',
        ]);

        $totalInvoiceNumber = 'TINV-' . (TotalInvoice::count() + 1);
        $invoices = Invoice::whereIn('id', $request->invoices)->get();

        $totalInvoice = TotalInvoice::create([
            'invoice_number' => $totalInvoiceNumber,
            'doctor_id' => $invoices->first()->invoiceable_id,
            'branch_id' => auth()->user()->branch_id,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
        ]);

        Invoice::whereIn('id', $request->invoices)->update([
            'status' => \App\Enums\InvoiceStatus::paid,
            'total_invoice_id' => $totalInvoice->id,
        ]);

        foreach ($invoices as $invoice) {
            if ($invoice->pricing) {
                if ($invoice->pricing->type === PricingType::Membership) {
                    $membership = $invoice->invoiceable;
                    $membership->membership_status = MembershipStatus::Active;
                    $membership->membership_expiration_date = now()->addYear();
                    $membership->save();
                } elseif ($invoice->pricing->type === PricingType::License && $invoice->licence && $invoice->licence->status === "under_payment") {
                    $invoice->licence->status = "active";
                    $invoice->licence->save();
                }
            }
        }

        $vault = auth()->user()->branch_id ? auth()->user()->branch->vault : Vault::first();

        $transaction = Transaction::create([
            'desc' => "فاتورة كلية رقم {$totalInvoiceNumber}",
            'amount' => $request->total_amount,
            'type' => "deposit",
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->id(),
            'vault_id' => $vault->id,
            'transaction_type_id' => 7, // Updated to the correct type
        ]);

        $vault->increment('balance', $request->total_amount);

        // Log the total invoice creation
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء فاتورة كلية رقم {$totalInvoiceNumber} للطبيب: {$invoices->first()->invoiceable->name} بقيمة {$request->total_amount}",
            'loggable_id' => $totalInvoice->doctor_id,
            'loggable_type' => Doctor::class,
            "action" => "create_total_invoice",
        ]);

        return redirect()->route(get_area_name() . '.total_invoices.index')->with('success', 'تم دفع الفواتير بنجاح.');
    }

    /**
     * Display a listing of total invoices.
     */
    public function index(Request $request)
    {
        $query = TotalInvoice::with(['doctor', 'user']);

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor', fn($q) => $q->where('name', 'like', '%' . $request->doctor_name . '%'));
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->user_name . '%'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if (auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $totalInvoices = $query->orderBy('created_at', 'desc')->paginate(10);


        return view(get_area_name() . '.total_invoices.index', compact('totalInvoices'));
    }

    /**
     * Show a specific total invoice.
     */
    public function show_invoice($id)
    {
        $totalInvoice = TotalInvoice::findOrFail($id);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم عرض تفاصيل الفاتورة الكلية رقم {$totalInvoice->invoice_number}",
            'loggable_id' => $totalInvoice->doctor->id,
            'loggable_type' => Doctor::class,
            "action" => "show_total_invoice",
        ]);

        return view('finance.total_invoices.show', compact('totalInvoice'));
    }

    /**
     * Print a total invoice.
     */
    public function print($id)
    {
        $totalInvoice = TotalInvoice::findOrFail($id);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم طباعة الفاتورة الكلية رقم {$totalInvoice->invoice_number}",
            'loggable_id' => $totalInvoice->doctor->id,
            'loggable_type' => Doctor::class,
            "action" => "print_total_invoice",
        ]);

        return view('finance.total_invoices.print', compact('totalInvoice'));
    }
}
