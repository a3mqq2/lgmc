<?php

namespace App\Http\Controllers;

use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Enums\PricingType;
use App\Models\Transaction;
use App\Models\TotalInvoice;
use Illuminate\Http\Request;
use App\Enums\MembershipStatus;

class TotalInvoiceController extends Controller
{
    public function show(Doctor $doctor)
    {
        return view('finance.total_invoices.create', compact('doctor'));
    }


    public function store(Request $request)
    {

        // check if the use has  branch id is null

        if(auth()->user()->branch_id == null) {
            return redirect()->route(get_area_name().'.total_invoices.index')->with('error', 'لا يمكنك إضافة فاتورة كلية لأنك لم تحدد فرعك بعد.');
        }

        $request->validate([
            'total_amount' => 'required|numeric|min:1',
            'invoices' => 'required|array',
            'notes' => 'nullable|string|max:255',
        ]);

        // إنشاء رقم الفاتورة الكلية
        $totalInvoiceNumber = 'TINV-' . TotalInvoice::count()+1;

        // إنشاء الفاتورة الكلية

        $invoices = Invoice::whereIn('id', $request->invoices)->get();
        $totalInvoice = TotalInvoice::create([
            'invoice_number' => $totalInvoiceNumber,
            'doctor_id' => $invoices->first()->invoiceable_id,
            'branch_id' => auth()->user()->branch_id,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
        ]);

        // تحديث الفواتير وإسنادها للفاتورة الكلية
        Invoice::whereIn('id', $request->invoices)->update([
            'status' => \App\Enums\InvoiceStatus::paid,
            'total_invoice_id' => $totalInvoice->id,
        ]);


        foreach($invoices as $invoice)
        {
            if($invoice->pricing)
            {
                
                if($invoice->pricing->type == PricingType::Membership)
                {
                    $membership = $invoice->invoiceable;
                    $membership->membership_status = MembershipStatus::Active;
                    $membership->membership_expiration_date = now()->addYear();
                    $membership->save();

                } else if($invoice->pricing->type == PricingType::License)
                {
                    if($invoice->licence && ($invoice->licence->status == "under_payment"))
                    {   
                        $invoice->licence->status = "active";
                        $invoice->licence->save();
                    }
              
                }
            }
        }


        // create transaction 
        $vault = auth()->user()->branch_id ? auth()->user()->branch->vault : Vault::first();
        $transaction = new Transaction();
        $transaction->desc = "فاتورة كلية رقم $totalInvoiceNumber";
        $transaction->amount = $request->total_amount;
        $transaction->type = "deposit";
        $transaction->branch_id = auth()->user()->branch_id;
        $transaction->user_id = auth()->id();
        $transaction->vault_id  = $vault->id;
        $transaction->transaction_type_id = 6; 
        // TODO CHANGE TO 7
        $transaction->save();

        $vault->increment('balance', $request->total_amount);
        $vault->save();


        return redirect()->route(get_area_name().'.total_invoices.index')->with('success', 'تم دفع الفواتير بنجاح.');
    }

    public function index(Request $request)
    {
        $query = TotalInvoice::with(['doctor', 'user']);
    
        if ($request->has('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
    
        if ($request->has('doctor_name')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor_name . '%');
            });
        }
    
        if ($request->has('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
    
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
    
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }


        if(auth()->user()->branch_id)
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }
    
        $totalInvoices = $query->orderBy('created_at', 'desc')->paginate(10);
    
        return view(get_area_name() . '.total_invoices.index', compact('totalInvoices'));
    }


    public function show_invoice($id)
    {
        $totalInvoice = TotalInvoice::findOrFail($id);
        return view('finance.total_invoices.show', compact('totalInvoice'));
    }


    public function print($id)
    {
        $totalInvoice = TotalInvoice::findOrFail($id);
        return view('finance.total_invoices.print', compact('totalInvoice'));
    }

}
