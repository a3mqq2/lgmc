<?php

namespace App\Http\Controllers;

use App\Models\Log; 
use App\Models\Doctor;
use App\Models\Licence;
use App\Models\LicenceLog;
use PhpParser\Comment\Doc;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\Transaction;
use App\Models\Vault;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class LicenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Licence::query();

        if ($request->has('type')) {
            if ($request->type === 'doctors') {
                $query->whereHasMorph('licensable', Doctor::class);
            } else {
                $query->whereHasMorph('licensable', MedicalFacility::class);
            }
        }

        if($request->status) {
            $query->where('status', $request->status);
        }

        if(get_area_name() != "admin") {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        $licences = $query->with('licensable')->latest()->paginate(10);
        return view('general.licences.index', compact('licences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        if(get_area_name() == "user") {
            $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->latest()->get();
            $medicalFacilities = MedicalFacility::where('branch_id', auth()->user()->branch_id)->latest()->get();
        } else {
            $doctors = Doctor::latest()->get();
            $medicalFacilities = MedicalFacility::latest()->get();
        }

        return view('general.licences.create', compact('doctors', 'medicalFacilities', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        $request->validate([
            'licensable_type' => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
            'licensable_id' => 'required|integer',
            'issued_date' => 'required|date',
            'expiry_date' => 'required|date',
            "doctor_id" => "required_if:licensable_type,==,App\Models\MedicalFacility"
        ]);
        

        try {
            DB::beginTransaction();

            $licencable = null;
            $type = null;
            if($request->licensable_type == "App\Models\Doctor") {
                $licencable = Doctor::findOrFail($request->licensable_id);
                $type = 'doctors';
            } else {
                $licencable = MedicalFacility::findOrFail($request->licensable_id);
                $type = 'facilities';
            }

            $licence = Licence::create([
                'licensable_type' => $request->licensable_type,
                'licensable_id' => $request->licensable_id,
                'issued_date' => $request->issued_date,
                'expiry_date' => $request->expiry_date,
                "branch_id" => $licencable->branch_id,
                "doctor_id" => $request->doctor_id,
                'status' => 'under_approve_branch',
                'created_by' => auth()->id(),
            ]);

            

            

            if($request->licensable_type == "App\Models\MedicalFacility") {
                $doctor = Doctor::findOrFail($request->doctor_id);
                $active_licence = $doctor->licenses->where('status', 'active')->count();
                if($active_licence==0) {
                    return redirect()->back()->withErrors(['ليس للطبيب اي اذن مزاولة ساري ']);
                }
            }



            LicenceLog::create([
                "user_id" => auth()->id(),
                "details" => "تمت اضافة اذن المزاولة لـ " . $licencable->name . " تاريخ الاذن :  " . $request->issued_date . '-' . $request->expiry_date . " الممثل " . (isset($doctor) ? $doctor->name : ""),
                "licence_id" => $licence->id,
            ]);

            Log::create([
                'user_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'details' => "تم إضافة الاذن مزاولة:  معرف الاذن مزاولة {$request->licensable_id}",
            ]);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }



        return redirect()->route(get_area_name().'.licences.index', ['status' => 'under_approve_branch', 'type' => $type])
            ->with('success', 'تم إضافة الاذن مزاولة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Licence $licence)
    {
        $vaults = Vault::when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->get();

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $licencable = Doctor::findOrFail($licence->licensable_id);
            $type = 'doctors';
        } else {
            $licencable = MedicalFacility::findOrFail($licence->licensable_id);
            $type = 'facilities';
        }


        return view('general.licences.show', compact('licence','vaults','type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Licence $licence)
    {
        if($licence->status != "under_approve_branch" && $licence->status != "under_approve_admin") {
            return redirect()->back()->withErrors(['لا يمكنك تعديل هذا الاذن ليس في حاله صحيحه']);
        }

        if(get_area_name() == "user") {
            $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->latest()->get();
            $medicalFacilities = MedicalFacility::where('branch_id', auth()->user()->branch_id)->latest()->get();
        } else {
            $doctors = Doctor::latest()->get();
            $medicalFacilities = MedicalFacility::latest()->get();
        }

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $licencable = Doctor::findOrFail($licence->licensable_id);
            $type = 'doctors';
        } else {
            $licencable = MedicalFacility::findOrFail($licence->licensable_id);
            $type = 'facilities';
        }



        return view('general.licences.edit', compact('licence', 'doctors', 'medicalFacilities','type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Licence $licence)
    {
        if($licence->status != "under_approve_branch" && $licence->status != "under_approve_admin") {
            return redirect()->back()->withErrors(['لا يمكنك تعديل هذا الاذن ليس في حاله صحيحه']);
        }


        $validatedData = $request->validate([
            'licensable_type' => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
            'licensable_id' => 'required|integer',
            'issued_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);

        $licencable = null;
        if($request->licensable_type == "App\Models\Doctor") {
            $validatedData['branch_id'] = Doctor::findOrFail($request->licensable_id)->branch_id;
            $licencable = Doctor::findOrFail($request->licensable_id);
        } else {
            $validatedData['branch_id'] =  MedicalFacility::findOrFail($request->licensable_id)->branch_id;
            $licencable = MedicalFacility::findOrFail($request->licensable_id);
        }

        $details = '';

        if($request->licensable_id != $licence->licensable_id) {
            $details .= "تم تحديث المرخص من " . $licence->licensable->name . ' الى ' . $licencable->name;
        }


        if($request->issued_date != $licence->issued_date) {
            $details .= "تم تحديث تاريخ الاصدار من " . $licence->issued_date . ' الى ' . $licencable->issued_date;
        }


        if($request->expiry_date != $licence->expiry_date) {
            $details .= "تم تحديث تاريخ الانتهاء من " . $licence->expiry_date . ' الى ' . $licencable->expiry_date;
        }



        $licence->update($validatedData);

        LicenceLog::create([
            "user_id" => auth()->id(),
            "details" => $details,
            "licence_id" => $licence->id,
        ]);


        Log::create([
            'user_id' => Auth::id(),
            'branch_id' => Auth::user()->branch_id,
            'details' => "تم تعديل الاذن مزاولة: نوع الاذن مزاولة {$request->licensable_type}، معرف الاذن مزاولة {$request->licensable_id}",
        ]);

        return redirect()->route(get_area_name().'.licences.index', ['type' => $licence->type, 'status' => $licence->status])
            ->with('success', 'تم تعديل الاذن مزاولة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Licence $licence)
    {
        if($licence->status != "under_approve_branch") {
            return redirect()->back()->withErrors(['لا يمكنك حذف هذا الاذن ليس في حاله صحيحه']);
        }

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $type = 'doctors';
        } else {
            $type = 'facilities';
        }


        $licence->logs()->delete();
        $licence->delete();

        Log::create([
            'user_id' => Auth::id(),
            'branch_id' => Auth::user()->branch_id,
            'details' => "تم حذف الاذن مزاولة: معرف الاذن مزاولة {$licence->id}",
        ]);

        return redirect()->route(get_area_name().'.licences.index', ['type' => $type, 'status' => $licence->status])
            ->with('success', 'تم حذف الاذن مزاولة بنجاح.');
    }

    public function approve(Request $request, Licence $licence) {
        $request->validate([
            "notes" => "nullable",
        ]);

        try {
            DB::beginTransaction();
            $notLibyan = '';
            if(get_area_name() == "user" && $licence->status != "under_approve_branch") {
                return redirect()->back()->withErrors(['لا يمكنك الموافقة على اذن مزاولة في هذه الحاله التي بها']);
            }

            if(get_area_name() == "admin" && $licence->status != "under_approve_admin") {
                return redirect()->back()->withErrors(['لا يمكنك الموافقة على اذن مزاولة في هذه الحاله التي بها']);
            }



            $isUnderPayment = '';
            if(get_area_name() == "user") {
                $licence->status = "under_approve_admin";
                if($licence->licensable_type == "App\Models\Doctor") {
                    $doctor = Doctor::findOrFail($licence->licensable_id);
                    if($doctor->country_id == 1) {
                        $licence->status = 'under_payment';
                        $notLibyan = "وفي انتظار موافقة الاداره على هذا الاذن";
                    } 
                }
                
            } else {
                $licence->status = "under_payment";
                $isUnderPayment = " وهو بحالة قيد عملية الدفع ";
            }

            $licence->save();
            

            Log::create([
                "user_id" => auth()->id(),
                "details" => "تمت الموافقة  على اذن المزاولة رقم " . $licence->id . " | " . $notLibyan . ' | ' . $isUnderPayment,
            ]);


            LicenceLog::create([
                "user_id" => auth()->id(),
                "details" => "تمت الموافقة على اذن المزاوله بنجاح | ملاحظات : " . $request->notes . ' | ' . $isUnderPayment,
                "licence_id" => $licence->id,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'تمت الموافقه بنجاح');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function print(Licence $licence) {
        Log::create([
            "user_id" => auth()->id(),
            "details" => "تمت طباعة اذن المزاولة"
        ]);


        LicenceLog::create([
            "user_id" => auth()->id(),
            "details" => "تمت طباعة اذن المزاولة",
            "licence_id" => $licence->id,
        ]);

        return view('general.licences.print', compact('licence'));
    }

    public function payment(Request $request, Licence $licence) {
        $request->validate([
            "vault_id" => "required",
            "amount" => "required",
            "notes" => "nullable",
        ]);


        if(auth()->user()->branch_id != $licence->branch_id) {
            abort(404);
        }

        try {
            DB::beginTransaction();
            $vault = Vault::findOrFail($request->vault_id);
            $licence->update(['amount' => $request->amount, 'status' => "active"]);

            Log::create([
                "user_id" => auth()->id(),
                "details" => "تم دفع رسوم اذن المزاولة " . $licence->id . 'وذلك بقيمة ' . $licence->amount,
            ]);



            LicenceLog::create([
                "user_id" => auth()->id(),
                "licence_id" => $licence->id,
                "details" => "تم دفع  الاذن  " . ' وذلك بقيمة  ' . $licence->amount . 'دينار ليبي ',
            ]);



            $vault->increment('balance', $request->amount);
            $transaction = new Transaction();
            $transaction->user_id = auth()->id();
            $transaction->desc = "دفع رسوم تجديد العقد  " . $licence->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->transaction_type_id = 1;
            $transaction->type = "deposit";
            $transaction->balance = $vault->balance;
            $transaction->vault_id = $vault->id;
            $transaction->save();

            DB::commit();
            return redirect()->back()->with('success', 'تم دفع قيمة اذن المزاولة بنجاح');
            // TODO : send an email for the doctor
            // TODO : ADD THE PROPER TRANSACTION TYPE ID 
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}
