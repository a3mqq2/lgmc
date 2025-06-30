<?php

namespace App\Http\Controllers\Common;
use ZipArchive;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Pricing;
use App\Models\FileType;
use App\Models\Signature;
use App\Models\Specialty;
use App\Models\DoctorRank;
use PhpParser\Comment\Doc;
use App\Mail\FinalApproval;
use App\Mail\FirstApproval;
use App\Models\Institution;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Imports\DoctorsImport;
use App\Models\MedicalFacility;
use App\Services\DoctorService;
use Illuminate\Support\Facades\DB;
use App\Imports\DoctorsSheetImport;
use App\Mail\RegisterUnderEditMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\DoctorMail;
use App\Models\DoctorMailService;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }


    public function index(Request $request)
    {
        // $request->validate(['type' => 'required|in:libyan,palestinian,foreign','visitor']);
        $doctors = $this->doctorService->getDoctors();
        $data = $this->doctorService->getRequirements();
        $data['doctors'] = $doctors;
        return view('general.doctors.index', $data);
    }

    public function create(Request $request)
    {
        // $request->validate(['type' => 'required|in:libyan,palestinian,foreign','visitor']);
        $data = $this->doctorService->getRequirements();
        return view('general.doctors.create',$data);
    }

    public function store(StoreDoctorRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->doctorService->create($validatedData);
            return redirect()->route(get_area_name().'.doctors.index', ['type' => request('type') ] )->with('success', 'تم إضافة الطبيب بنجاح');
        } catch (\Exception $e )  {

            return redirect()->route(get_area_name().'.doctors.index', ['type' => request('type') ] )->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit(Doctor $doctor)
    {
        $data = $this->doctorService->getRequirements();
        $data['doctor'] = $doctor;
        $data['file_types'] = FileType::where('type', 'doctor')->where('doctor_type', $doctor->type->value)
        ->where('for_registration', 1)->get();
        return view('general.doctors.edit', $data);
    }

    
    public function show(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        $data['specialties'] = Specialty::all();
        $data['doctor_ranks'] = DoctorRank::where('doctor_type', $doctor->type->value)->get();
        $data['institutions'] = Institution::where('branch_id', $doctor->branch_id)->get();
        $data['medicalFacilities'] = MedicalFacility::where('branch_id', $doctor->branch_id)->get();
        return view('general.doctors.show', $data);
    }


    public function update(UpdateDoctorRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $doctor = Doctor::findOrFail($id);
            $this->doctorService->update($doctor, $validatedData);
            return redirect()->route(get_area_name().'.doctors.index', ['type' =>  $doctor->type->value  ])->with('success', 'تم تعديل الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function destroy(Doctor $doctor)
    {
        try {
            $this->doctorService->delete($doctor);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم حذف الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function print(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        return view('general.doctors.print', $data);
    }

    public function approve(Doctor $doctor)
    {
        try {
            $this->doctorService->approve($doctor);
            return redirect()->route(get_area_name().'.doctors.index',['type' => $doctor->type->value])->with('success', 'تم الموافقة على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function reject(Doctor $doctor)
    {
        try {
            $this->doctorService->reject($doctor);
            return redirect()->route(get_area_name().'.doctors.index', ['type' => $doctor->type->value])->with('success', 'تم الرفض على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function print_id(Doctor $doctor)
    {
        return view('general.doctors.print-id', ['doctor' => $doctor]);
    }

    public function import(Request $request)
    {
        return view('general.doctors.import');
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        // import excel
        if(request('doctor_type') == "palestinian") {
            Excel::import(new DoctorsImport, $request->file('file'));
        }
        Excel::import(new DoctorsSheetImport, $request->file('file'));
        return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم إضافة الأطباء بنجاح');
    }


    public function ban(Request $request, Doctor $doctor)
    {   


       
        // Check if the doctor is currently banned
        if ($doctor->membership_status->value === 'banned') {
            // ✅ UNBAN LOGIC



            $newStatus = $doctor->membership_expiration_date && $doctor->membership_expiration_date > now() ? 'active' : 'expired';
            
            $doctor->update(['membership_status' => $newStatus, 'ban_reason' => null]);
    
            // Log the unban action
            \App\Models\Log::create([
                'user_id' => auth()->id(),
                'branch_id' => auth()->user()->branch_id ?? null,
                'action' => 'رفع الحظر عن الطبيب',
                'details' => "تم رفع الحظر عن الطبيب: {$doctor->name} (الرقم النقابي: {$doctor->id}) - الحالة الآن: {$newStatus}",
                'loggable_id' => $doctor->id,
                'loggable_type' => \App\Models\Doctor::class,
            ]);
    
            // ✅ If membership expired, create an invoice for renewal
            if ($newStatus === 'expired') {

            }
    
            return redirect()->back()->with('success', 'تم رفع الحظر عن الطبيب بنجاح.');
        }
    
        // ✅ BAN LOGIC
        $doctor->update(['membership_status' => 'banned']);
        $request->validate([
            'ban_reason' => "required",
        ]); 
        $doctor->update(['ban_reason' => $request->ban_reason]);

        foreach($doctor->licenses as $licence)
        {
            $licence->status = "banned";
            $licence->save();
        }

        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'branch_id' => auth()->user()->branch_id ?? null,
            'action' => 'حظر الطبيب',
            'details' => "تم حظر الطبيب: {$doctor->name} (الرقم النقابي: {$doctor->id})",
            'loggable_id' => $doctor->id,
            'loggable_type' => \App\Models\Doctor::class,
        ]);
    
        return redirect()->back()->with('success', 'تم حظر الطبيب بنجاح.');
    }
    
    

    public function printList(Request $request)
    {
        $query = Doctor::query();
    
        if ($request->filled('doctor_number')) {
            $query->where('doctor_number', $request->doctor_number);
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
    
        if ($request->filled('doctor_rank_id')) {
            $query->where('doctor_rank_id', $request->doctor_rank_id);
        }
    
        if ($request->filled('registered_at')) {
            $query->whereDate('registered_at', $request->registered_at);
        }
    
        if ($request->filled('membership_status')) {
            $query->where('membership_status', $request->membership_status);
        }
    
        if ($request->filled('specialization')) {
            $query->where('specialization_id', $request->specialization);
        }
    
        if ($request->filled('last_license_status')) {
            $query->whereHas('latestLicence', function($q) use ($request) {
                $q->where('status', $request->last_license_status);
            });
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        $doctors = $query->get();
    
        return view('general.doctors.print_list', compact('doctors'));
    }


    public function change_status(Request $request, Doctor $doctor)
    {
        $request->validate([
            "final_status" => "required|in:under_edit,approved",
            'edit_note' => "required_if:final_status,==,under_edit",
            // visit from and visit to are required when doctor type is visitor
            'visit_from' => 'nullable|date',
            'visit_to' => 'nullable|date',
        ]);


        try {
            DB::beginTransaction();
            if($request->final_status == "under_edit")
            {
                $doctor->membership_status = "under_edit";
                $doctor->edit_note = $request->edit_note;
                $doctor->save();

                try {
                    Mail::to($doctor->email)->send(new RegisterUnderEditMail($doctor));
                } catch (\Exception $e) {
                    // Log the error or handle it as needed
                }



            } else if($request->final_status == "approved")
            {
                $doctor->membership_status = "under_payment";

                if($request->is_paid)
                {
                    $doctor->membership_status = "active";
                    if(!$doctor->code)
                    {
                        $doctor->setSequentialIndex();
                        $doctor->makeCode();
                    }
                  
                    $doctor->save();

                }
                $doctor->specialty_1_id = $request->specialty_1_id;
                $doctor->doctor_rank_id = $request->doctor_rank_id;
                $doctor->institution_id = $request->institution_id;
                $doctor->visit_from = $request->visit_from ? Carbon::createFromFormat('Y-m-d', $request->visit_from) : null;
                $doctor->visit_to = $request->visit_to ? Carbon::createFromFormat('Y-m-d', $request->visit_to) : null;
                if($request->registered_at)
                {
                    $doctor->registered_at = Carbon::createFromFormat('Y-m-d', $request->registered_at);
                } else {
                    $doctor->registered_at = now();
                }
                $doctor->edit_note = null;
                $doctor->save();


                
                
                $this->createApproveDoctorInvoices($doctor, $request->is_paid);
  
            }
            DB::commit();

            if($request->is_paid)
            {
                $invoice = Invoice::where('doctor_id', $doctor->id)->where('status', 'paid')->first(); 
                if($invoice)
                {
                    return redirect()->route(get_area_name().'.invoices.print', $invoice->id);
                }
            }

            return redirect()->back()
            ->with('success', 'تمت تغيير الحالة بنجاح');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['حدث خطأ ما يرجى التواصل مع الدعم الفني رمز الخطأ : ' . $e->getMessage() ]);
        }
    }


    public function createApproveDoctorInvoices($doctor, $is_paid)
    {
        // create membership_invoice
        $invoice = new Invoice();
        $invoice->invoice_number = rand(0,999999999);
        $invoice->description = " فاتورة عضوية طبيب جديد  " . $doctor->name .  ($doctor->type->value == "visitor" ? "  زائر  " : "") ;
        $invoice->user_id = auth()->id();
        $invoice->amount = 0;
        $invoice->status = "unpaid";
        $invoice->doctor_id = $doctor->type->value == "visitor" ? $doctor->medicalFacilityWork->manager_id : $doctor->id;
        $invoice->category = "registration";
        $invoice->visitor_id = $doctor->type->value == "visitor" ? $doctor->id : null;
        $invoice->branch_id = auth()->user()->branch_id;
        $invoice->save();

        // registeration invoice 
        $pricing_membership = Pricing::where('doctor_type', $doctor->type->value)->where('type','membership')
        ->where('doctor_rank_id', $doctor->doctor_rank_id)->first();
        
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->description = $pricing_membership->name;
        $invoice_item->amount = $pricing_membership->amount;
        $invoice_item->pricing_id = $pricing_membership->id;
        $invoice_item->save();


        
        // if($doctor->type->value != "visitor")
        // {
        //     $pricing_card_id = Pricing::where('doctor_type', $doctor->type->value)->where('type','card')->first();

        //     $invoice_item = new InvoiceItem();
        //     $invoice_item->invoice_id = $invoice->id;
        //     $invoice_item->description = $pricing_card_id->name;
        //     $invoice_item->amount = $pricing_card_id->amount;
        //     $invoice_item->pricing_id = $pricing_card_id->id;
        //     $invoice_item->save();
        // }


        if($doctor->type->value == "visitor")
        {
            $pricing_license = Pricing::where('doctor_type', $doctor->type->value)->where('type','license')->first();
            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_license->name;
            $invoice_item->amount = $pricing_license->amount;
            $invoice_item->pricing_id = $pricing_license->id;
            $invoice_item->save();

        }

        
        $invoice->update([
            'amount' => $invoice->items()->sum('amount'),
        ]);


        try {
            Mail::to($doctor->email)
            ->send(new FirstApproval($doctor, $invoice));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
        }


        if($is_paid)
        {
            $invoice->status = "paid";
            $invoice->received_by = auth()->id();
            $invoice->received_at = now();
            $invoice->save();

            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            // create transaction 
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = " فاتورة عضوية طبيب جديد  " . $doctor->name . " رقم الفاتورة  " . $invoice->id;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 1;
            $transaction->save();

            $doctor->membership_status = "active";
            if($doctor->type->value == "visitor")
            {
                $membership_expiration_date = $doctor->visit_to;
                
                if(!$doctor->code)
                {
                    $doctor->setSequentialIndex();
                    $doctor->makeCode();
                }

                // create license
                $license = new Licence();
                $license->workin_medical_facility_id  = $doctor->medical_facility_id;
                $license->doctor_id = $doctor->id;
                $license->doctor_type = $doctor->type->value;
                $license->issued_date = $doctor->visit_from;
                $license->expiry_date = $doctor->visit_to;
                $license->status = "active";
                $license->created_by = auth()->id();
                $license->amount = 0; 
                $licence->branch_id = auth()->user()->branch_id;
                $license->save();
            } else {
                $membership_expiration_date = $doctor->type->value == "foreign" ?   Carbon::now()->addMonths(6) : Carbon::now()->addMonths(12);
            }

            $doctor->membership_expiration_date = $membership_expiration_date;
            $doctor->save();

        }

    }


    public function print_license(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        $data['signature'] = Signature::where('is_selected', 1)->when($doctor->branch_id, function($q) use($doctor) {
            $q->where("branch_id", $doctor->branch_id);
        })->first();
        if($doctor->type->value == "foreign")
        {
            return view('general.doctors.print_license_foreign', $data);
        }

        if($doctor->type->value == "palestinian")
        {
            return view('general.doctors.print_license_palestinian', $data);
        }


        if($doctor->type->value == "libyan")
        {
            return view('general.doctors.print_license_libyan', $data);
        }


        return view('general.doctors.print_license', $data);
    }
    

    
    public function generateReport(Request $request)
    {
        $reportType = $request->input('report_type', 'summary');
        $format = $request->input('format', 'pdf');
        
        // Build query based on filters
        $query = Doctor::query();
        
        // Apply filters
        $this->applyReportFilters($query, $request);
        
        // Get data based on report type
        if ($reportType === 'summary') {
            $data = $this->generateSummaryReportData($query, $request);
            $view = 'general.reports.doctors_summary';
        } else {
            $data = $this->generateDetailedReportData($query, $request);
            $view = 'general.reports.doctors_detailed';
        }
        
        // Add general report info
        $data['report_date'] = now()->format('Y-m-d H:i');
        $data['report_type'] = $reportType;
        $data['filters'] = $this->getAppliedFilters($request);
        
        // Generate report based on format
        switch ($format) {
            case 'excel':
                return $this->exportToExcel($data, $reportType);
                
            case 'print':
                return view($view . '_print', $data);
                
            case 'pdf':
            default:
                $pdf = PDF::loadView($view, $data);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download('doctors_report_' . now()->format('Y-m-d') . '.pdf');
        }
    }
    
    private function applyReportFilters($query, Request $request)
    {
        // Doctor rank filter
        if ($request->filled('doctor_rank_id')) {
            $query->whereIn('doctor_rank_id', $request->input('doctor_rank_id'));
        }

        if($request->filled('medical_facility_id'))
        {
            $query->whereHas('licenses', function($q) use ($request) {
                $q->where('status', 'active');
                $q->where('medical_facility_id', $request->medical_facility_id);
            });
        }
        
        // Institution filter
        if ($request->filled('institution_id')) {
            $query->whereIn('institution_id', $request->input('institution_id'));
        }
        
        // Specialty filter
        if ($request->filled('specialty_id')) {
            $query->where('specialty_1_id',$request->specialty_id);
        }
        
        // Doctor type filter
        if ($request->filled('doctor_type')) {
            $query->whereIn('type', $request->input('doctor_type'));
        }
        
        // Registration date range
        if ($request->filled('registered_from')) {
            $query->whereDate('registered_at', '>=', $request->input('registered_from'));
        }
        if ($request->filled('registered_to')) {
            $query->whereDate('registered_at', '<=', $request->input('registered_to'));
        }
        
        // Membership expiry date range
        if ($request->filled('membership_expired_from')) {
            $query->whereDate('membership_expiration_date', '>=', $request->input('membership_expired_from'));
        }
        if ($request->filled('membership_expired_to')) {
            $query->whereDate('membership_expiration_date', '<=', $request->input('membership_expired_to'));
        }
        
        // Membership status filter
        if ($request->filled('membership_status')) {
            $query->whereIn('membership_status', $request->input('membership_status'));
        }
        
        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }
    }
    
    private function generateSummaryReportData($query, Request $request)
    {
        $data = [];
        
        // Total counts
        $data['total_doctors'] = $query->count();
        
        // Clone query for each aggregation to avoid conflicts
        $baseQuery = clone $query;
        
        // Group by doctor rank
        $data['by_rank'] = (clone $baseQuery)
            ->select('doctor_rank_id', DB::raw('count(*) as total'))
            ->with('doctor_rank')
            ->groupBy('doctor_rank_id')
            ->get();
        
        // Group by specialty - Direct relationship
        $data['by_specialty'] = DB::table('doctors')
            ->leftJoin('specialties', 'doctors.specialty_1_id', '=', 'specialties.id')
            ->select(
                DB::raw('COALESCE(specialties.name, "بدون تخصص") as name'), 
                DB::raw('count(doctors.id) as total')
            )
            ->when($request->filled('doctor_rank_id'), function($q) use ($request) {
                $q->whereIn('doctors.doctor_rank_id', $request->input('doctor_rank_id'));
            })
            ->groupBy('specialties.id', 'specialties.name')
            ->orderBy('total', 'desc')
            ->get();
        
        // Group by type
        $data['by_type'] = (clone $baseQuery)
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();
        
        // Group by membership status
        $data['by_membership_status'] = (clone $baseQuery)
            ->select('membership_status', DB::raw('count(*) as total'))
            ->groupBy('membership_status')
            ->get();
        
        // Group by gender
        $data['by_gender'] = (clone $baseQuery)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
        
        // Financial summary (if finance area)
        if (get_area_name() == "finance") {
            $financialDoctorIds = (clone $baseQuery)->pluck('id');
            
            $data['financial_summary'] = [
                'total_due' => DB::table('invoices')
                    ->whereIn('doctor_id', $financialDoctorIds)
                    ->where('status', 'unpaid')
                    ->sum('amount'),
                'total_paid' => DB::table('invoices')
                    ->whereIn('doctor_id', $financialDoctorIds)
                    ->where('status', 'paid')
                    ->sum('amount'),
                'total_relief' => (clone $baseQuery)->sum('full_break_amount')
            ];
        }
        
        // Registration trends (last 12 months) - Fixed
        $data['registration_trends'] = (clone $baseQuery)
            ->selectRaw('YEAR(registered_at) as year, MONTH(registered_at) as month, count(*) as total')
            ->whereNotNull('registered_at')
            ->where('registered_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderByRaw('YEAR(registered_at) DESC, MONTH(registered_at) DESC')
            ->get();
        
        // Membership expiry analysis
        $data['membership_expiry'] = [
            'expired' => (clone $baseQuery)->where('membership_expiration_date', '<', now())->count(),
            'expiring_30_days' => (clone $baseQuery)->whereBetween('membership_expiration_date', [now(), now()->addDays(30)])->count(),
            'expiring_90_days' => (clone $baseQuery)->whereBetween('membership_expiration_date', [now(), now()->addDays(90)])->count(),
            'active' => (clone $baseQuery)->where('membership_expiration_date', '>', now()->addDays(90))->count(),
        ];
        
        return $data;
    }
    
    private function generateDetailedReportData($query, Request $request)
    {
        $data = [];
        
        // Get doctors with relationships
        $doctors = $query->with([
            'doctor_rank',
            'institutionObj',
            'invoices' => function($q) {
                $q->select('doctor_id', 'status', DB::raw('sum(amount) as total'))
                    ->groupBy('doctor_id', 'status');
            }
        ]);
        
        // Apply additional options
        if (!$request->input('include_contact')) {
            $doctors->select(DB::raw('*, NULL as phone, NULL as email, NULL as address'));
        }
        
        // Paginate for performance
        $data['doctors'] = $doctors->paginate(50);
        
        // Include additional data based on options
        $data['include_photo'] = $request->input('include_photo', false);
        $data['include_contact'] = $request->input('include_contact', true);
        $data['include_financial'] = $request->input('include_financial', false);
        $data['include_membership_history'] = $request->input('include_membership_history', false);
        
        // Get summary statistics
        $data['summary'] = [
            'total' => $data['doctors']->total(),
            'per_page' => $data['doctors']->perPage(),
            'current_page' => $data['doctors']->currentPage(),
            'last_page' => $data['doctors']->lastPage()
        ];
        
        return $data;
    }
    
    private function getAppliedFilters(Request $request)
    {
        $filters = [];
        
        if ($request->filled('doctor_rank_id')) {
            $filters['doctor_ranks'] = DoctorRank::whereIn('id', $request->input('doctor_rank_id'))->pluck('name')->toArray();
        }
        
        if ($request->filled('institution_id')) {
            $filters['institutions'] = Institution::whereIn('id', $request->input('institution_id'))->pluck('name')->toArray();
        }
        
        if ($request->filled('specialty_id')) {
            $filters['specialties'] = Specialty::whereIn('id', $request->input('specialty_id'))->pluck('name')->toArray();
        }
        
        if ($request->filled('doctor_type')) {
            $filters['types'] = $request->input('doctor_type');
        }
        
        if ($request->filled('registered_from') || $request->filled('registered_to')) {
            $filters['registration_period'] = [
                'from' => $request->input('registered_from'),
                'to' => $request->input('registered_to')
            ];
        }
        
        if ($request->filled('membership_expired_from') || $request->filled('membership_expired_to')) {
            $filters['membership_expiry_period'] = [
                'from' => $request->input('membership_expired_from'),
                'to' => $request->input('membership_expired_to')
            ];
        }
        
        return $filters;
    }
    
    public function previewReport(Request $request)
    {
        // Same logic as generateReport but return view instead of download
        $reportType = $request->input('report_type', 'summary');
        
        $query = Doctor::query();
        $this->applyReportFilters($query, $request);
        
        if ($reportType === 'summary') {
            $data = $this->generateSummaryReportData($query, $request);
            $view = 'general.reports.doctors_summary_preview';
        } else {
            $data = $this->generateDetailedReportData($query, $request);
            $view = 'general.reports.doctors_detailed_preview';
        }
        
        $data['report_date'] = now()->format('Y-m-d H:i');
        $data['report_type'] = $reportType;
        $data['filters'] = $this->getAppliedFilters($request);
        
        return view($view, $data);
    }
    
    private function exportToExcel($data, $reportType)
    {
        return Excel::download(
            new DoctorsReportExport($data, $reportType), 
            'doctors_report_' . now()->format('Y-m-d') . '.xlsx'
        );
    }



    public function printListFinance(Request $request)
    {
        $doctors = $this->doctorService->getDoctors(true);
        return view('general.doctors.finance_list', compact('doctors'));
    }


    public function print_membership_form(Doctor $doctor)
    {
        return view('general.doctors.membership-form', compact('doctor'));
    }



    public function downloadAllFiles(Doctor $doctor)
    {
        // التحقق من وجود ملفات
        if ($doctor->files->count() == 0) {
            return back()->with('error', 'لا توجد مستندات للتحميل');
        }
    
        // إنشاء اسم فريد للملف المضغوط
        $zipFileName = 'doctor_' . $doctor->code . '_' . $doctor->name . '_files_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
    
        // إنشاء المجلد المؤقت إذا لم يكن موجود
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }
    
        // إنشاء ملف ZIP
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $addedFiles = 0; // عداد للملفات المضافة
            
            // إضافة جميع الملفات إلى الملف المضغوط
            foreach ($doctor->files as $index => $file) {
                // استخدام Storage::disk('public') للحصول على المسار الصحيح
                $filePath = Storage::disk('public')->path($file->file_path);
                
                if (file_exists($filePath)) {
                    // إنشاء اسم فريد للملف داخل الـ ZIP
                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                    $fileNameInZip = ($index + 1) . '_';
                    
                    // إضافة نوع الملف إلى الاسم إذا كان موجود
                    if ($file->fileType) {
                        $fileNameInZip .= $file->fileType->name . '_';
                    }
                    
                    $fileNameInZip .= $file->file_name;
                    
                    // التأكد من أن الاسم فريد
                    if (!$fileNameInZip) {
                        $fileNameInZip = ($index + 1) . '_document.' . $fileExtension;
                    }
                    
                    // إضافة الملف إلى الـ ZIP
                    if ($zip->addFile($filePath, $fileNameInZip)) {
                        $addedFiles++;
                    } else {
                        \Log::error("Failed to add file to ZIP: " . $filePath);
                    }
                } else {
                    \Log::warning("File not found: " . $filePath);
                }
            }
            
            // إضافة ملف معلومات الطبيب
            $infoContent = "معلومات الطبيب\n";
            $infoContent .= "================\n\n";
            $infoContent .= "كود الطبيب: " . $doctor->code . "\n";
            $infoContent .= "اسم الطبيب: " . $doctor->name . "\n";
            $infoContent .= "الاسم بالإنجليزية: " . ($doctor->name_en ?? 'غير محدد') . "\n";
            $infoContent .= "التخصص: " . ($doctor->specialization ?? 'غير محدد') . "\n";
            $infoContent .= "الصفة: " . ($doctor->doctor_rank?->name ?? 'غير محدد') . "\n";
            $infoContent .= "رقم الهاتف: " . $doctor->phone . "\n";
            $infoContent .= "البريد الإلكتروني: " . $doctor->email . "\n";
            $infoContent .= "تاريخ التحميل: " . date('Y-m-d H:i:s') . "\n";
            $infoContent .= "عدد المستندات: " . $doctor->files->count() . "\n";
            $infoContent .= "عدد الملفات المضافة: " . $addedFiles . "\n\n";
            $infoContent .= "قائمة المستندات:\n";
            $infoContent .= "================\n";
            
            foreach ($doctor->files as $index => $file) {
                $infoContent .= ($index + 1) . ". " . $file->file_name;
                if ($file->fileType) {
                    $infoContent .= " (" . $file->fileType->name . ")";
                }
                $infoContent .= "\n";
            }
            
            $zip->addFromString('معلومات_الطبيب.txt', $infoContent);
            
            $zip->close();
            
            // التحقق من وجود ملفات في الأرشيف
            if ($addedFiles == 0) {
                unlink($zipPath); // حذف الملف المضغوط الفارغ
                return back()->with('error', 'لم يتم العثور على أي ملفات للتحميل');
            }
            
            // تحميل الملف المضغوط
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'فشل في إنشاء الملف المضغوط');
        }
    }

    public function renewMembership(Doctor $doctor)
    {
        try {
            $this->createInvoice($doctor);
            $doctor->update([
                'membership_status' => 'under_payment',
                'membership_expiration_date' => null,
            ]);
            return redirect()->route(get_area_name().'.doctors.show', $doctor)->with('success', 'تم إنشاء فاتورة تجديد الاشتراك بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }
    
    private function createInvoice($doctor)
    {
        // create membership_invoice
        $invoice = new Invoice();
        $invoice->invoice_number = rand(0,999999999);
        $invoice->description = " فاتورة تجديد اشتراك طبيب   " . $doctor->name;
        $invoice->user_id = auth()->id();
        $invoice->amount = 0;
        $invoice->status = "unpaid";
        $invoice->doctor_id = $doctor->id;
        $invoice->category = "registration";
        $invoice->branch_id = auth()->user()->branch_id;
        $invoice->save();
    
        // الحصول على أسعار العضوية
        $pricing_membership = Pricing::where('doctor_type', $doctor->type->value)
            ->where('type','membership')
            ->where('doctor_rank_id', $doctor->doctor_rank_id)
            ->first();

    
    
        if(in_array($doctor->type->value,['palestinian','foreign']))
        {
            $expiredYears = $this->calculateExpiredYears($doctor);
            if ($expiredYears <= 1) {
                // السنة الحالية فقط - بدون غرامات
                $this->addCurrentYearItems($invoice, $pricing_membership);
            } else {
              if(in_array($doctor->type->value, ['foreign','palestinian']))
              {
                  $this->addCurrentYearItems($invoice, $pricing_membership);
                  $this->addPenaltyItems($invoice, $pricing_membership, $expiredYears - 1);
              }
            }
        } else {
            $this->addCurrentYearItems($invoice, $pricing_membership);
        }

       
    
        // تحديث إجمالي الفاتورة
        $invoice->update([
            'amount' => $invoice->items()->sum('amount'),
        ]);
    }
    
    /**
     * حساب عدد السنوات المنتهية الصلاحية
     */
    private function calculateExpiredYears($doctor)
    {
        if (!$doctor->membership_expiration_date) {
            // إذا لم يكن هناك تاريخ انتهاء، نعتبرها سنة واحدة
            return 1;
        }
    
        $expirationDate = Carbon::parse($doctor->membership_expiration_date);
        $currentDate = Carbon::now();
        
        // حساب الفرق بالسنوات
        $yearsDifference = $currentDate->diffInYears($expirationDate);
        
        // إذا انتهت الصلاحية، نحسب عدد السنوات + السنة الحالية
        if ($expirationDate->isPast()) {
            return $yearsDifference + 1;
        }
        
        return 1; // لم تنته الصلاحية بعد
    }
    
    /**
     * إضافة عناصر السنة الحالية
     */
    private function addCurrentYearItems($invoice, $pricing_membership)
    {
        // عضوية السنة الحالية
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->description = $pricing_membership->name . ' (السنة الحالية)';
        $invoice_item->amount = $pricing_membership->amount;
        $invoice_item->pricing_id = $pricing_membership->id;
        $invoice_item->save();
    }
    
    /**
     * إضافة غرامات السنوات السابقة
     */
    private function addPenaltyItems($invoice, $pricing_membership, $expiredYears)
    {
        for ($i = 1; $i <= $expiredYears; $i++) {
            // غرامة العضوية للسنة السابقة (الضعف)
            $penalty_membership = new InvoiceItem();
            $penalty_membership->invoice_id = $invoice->id;
            $penalty_membership->description = 'غرامة تأخير عضوية - السنة ' . $i . ' (ضعف المبلغ)';
            $penalty_membership->amount = $pricing_membership->amount * 2;
            $penalty_membership->pricing_id = $pricing_membership->id;
            $penalty_membership->save();
        }
    }
    

    public function saveFileToDoctor($id, Request $request)
    {

        $doctorMail = DoctorMail::find($id);
        $service = DoctorMailService::find($request->service_id);
        $doctor = $doctorMail->doctor;
        
        $doctor->files()->create([
            'file_name' => $service->file_name,
            'file_path' => $service->file_path,
            'file_type_id' => 55,
            'order_number' => 9999,
        ]);

        try {
            $doctor->save();
            return redirect()->back()->with('success', 'تم حفظ الملف للطبيب بنجاح');
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            \Log::error("Error saving file to doctor: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }


    public function addCard(Doctor $doctor)
    {
          
            $pricing_card_id = Pricing::where('doctor_type', $doctor->type->value)->where('type','card')->first();


            $invoice = new Invoice();
            $invoice->invoice_number = rand(0,999999999);
            $invoice->description = "فاتورة بطاقة تعريفية جديدة للطبيب " . $doctor->name;
            $invoice->user_id = auth()->id();
            $invoice->amount = 0;
            $invoice->status = "unpaid";
            $invoice->doctor_id = $doctor->id;
            $invoice->category = "card";
            $invoice->branch_id = auth()->user()->branch_id;
            $invoice->save();

            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_card_id->name;
            $invoice_item->amount = $pricing_card_id->amount;
            $invoice_item->pricing_id = $pricing_card_id->id;
            $invoice_item->save();

            $invoice->update([
                'amount' => $invoice->items()->sum('amount'),
            ]);

            return redirect()->route(get_area_name().'.doctors.show', ['doctor' => $doctor, 'redirect' => 'finance']);

    }
}
