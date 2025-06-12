<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Country;
use App\Models\Licence;
use App\Enums\ReplyType;
use App\Models\FileType;
use App\Models\DoctorMail;
use App\Models\DoctorRank;
use App\Models\University;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use App\Models\AcademicDegree;
use App\Models\DoctorMailItem;
use App\Models\MedicalFacility;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class DoctorHomeController extends Controller
{
    public function dashboard()
    {
        $countries = Country::all();
        $universities = University::all();
        $doctor_ranks = DoctorRank::where('doctor_type', auth('doctor')->user()->type)->get();
        $academicDegrees = AcademicDegree::all();
        return view('doctor.dashboard', compact('countries', 'universities', 'doctor_ranks','academicDegrees'));
    }



    public function licence_print(Licence $licence)
    {
        return view('general.licences.print', compact('licence'));
    }

    public function create_ticket()
    {
        return view('doctor.tickets.create');
    }


    public function store_ticket(Request $request)
    {

        $request->validate([
            "title" => "required|max:255",
            "department" => "required",
            "body" => "required",
            "category" => "required",
            "priority" => "required",
            "attachment" => "nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048",
        ]);

        try {

            $attachment_name = null;
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $attachment_name = time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->storeAs('public/attachments', $attachment_name);
            }


            DB::beginTransaction();
            $ticket = new Ticket();
            $ticket->slug = auth()->user()->branch->code . '-TICKET' . '-' . Ticket::count() + 1;
            $ticket->title = $request->title;
            $ticket->department = $request->department;
            $ticket->body = $request->body;
            $ticket->category = $request->category;
            $ticket->priority = $request->priority;
            $ticket->init_doctor_id = auth('doctor')->id();
            $ticket->branch_id = auth('doctor')->user()->branch_id;
            if($attachment_name) {
                $ticket->attachment = $attachment_name;
            }
            $ticket->save();
            DB::commit();
            return redirect()->route('doctor.dashboard')->with('success', 'تم إنشاء التذكرة بنجاح');
        } catch(\Exception $e) 
        {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'حدث خطأ ما، يرجى المحاولة مرة أخرى'
            ]);
        }
    }


    public function show_ticket(Ticket $ticket)
    {
        $replies = $ticket->replies;
        return view('doctor.tickets.show', compact('ticket', 'replies'));
    }


    public function reply_ticket(Ticket $ticket, Request $request)
    {
        $request->validate([
            "replyBody" => "required",
        ]);

        $ticketReply = new TicketReply();
        $ticketReply->ticket_id = $ticket->id;
        $ticketReply->reply_type = ReplyType::External;
        $ticketReply->body = $request->replyBody;

        if($request->closeTicket)
        {
            $ticket->status = 'complete';
            $ticket->closed_at = now();
            $ticket->save();
        }

        if($ticket->status->value == 'complete')
        {
            $ticket->status = 'customer_reply';
            $ticket->save();
        }
        $ticketReply->save();


        return redirect()->back()->with('success', 'تم إرسال الرد بنجاح');

    }


    public function create_doctor_request()
    {
        return view('doctor.doctor-requests.create');
    }

    public function store_doctor_request(Request $request)
    {
        $request->validate([
            "pricing_id" => "required|exists:pricings,id",
            "notes" => "nullable",
        ]);

        try {
            DB::beginTransaction();
            $doctorRequest = new DoctorRequest();
            $doctorRequest->pricing_id = $request->pricing_id;
            $doctorRequest->notes = $request->notes;
            $doctorRequest->doctor_id = auth('doctor')->id();
            $doctorRequest->date  = now();
            $doctorRequest->status = "pending";
            $doctorRequest->branch_id = auth('doctor')->user()->branch_id;
            $doctorRequest->doctor_type = auth('doctor')->user()->type;
            $doctorRequest->save();
            DB::commit();
            return redirect()->route('doctor.dashboard', ['requests' => 1])->with('success', 'تم إرسال الطلب بنجاح');
        } catch(\Exception $e) 
        {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'حدث خطأ ما، يرجى المحاولة مرة أخرى'
            ]);
        }
    }


    public function logout()
    {
        auth('doctor')->logout();
        return redirect('/');
    }


    public function show_mail(DoctorMail $doctorMail)
    {
        return view('doctor.doctor-mails.show', compact('doctorMail'));
    }



    public function update_mail(Request $request, DoctorMail $doctorMail)
    {
        $request->validate([
            'files.*' => 'nullable|file|max:10240',
        ]);
    
        if ($ids = array_keys($request->input('items', []))) {
            DoctorMailItem::whereIn('id', $ids)->delete();
        }
    
        foreach ($request->file('files', []) as $id => $file) {
            $path = $file->store('doctor_mail_items', 'public');
            DoctorMailItem::where('id', $id)->update(['file' => $path, 'rejected_reason' => null, 'status' => 'pending']);
        }
    
        $doctorMail->status = 'under_approve';
        $doctorMail->save();
    
        return redirect()
            ->route('doctor.doctor-mails.show', $doctorMail->id)
            ->with('success', 'تم تحديث الطلب وتحويله إلى قيد الموافقة');
    }


    public function licences()
    {
        $licences = auth('doctor')->user()->licences;
        return view('doctor.licences.index', compact('licences'));
    }


    public function create_license()
    {
        $doctor = auth('doctor')->user();
        $medical_facilities = MedicalFacility::where('branch_id', auth('doctor')->user()->branch_id)->get();
        return view('doctor.licences.create', compact('medical_facilities'));
    }


    public function facilities()
    {
        $medical_facilities = MedicalFacility::where('manager_id', auth('doctor')->user()->id)->get();
        return view('doctor.medical_facilities.index', compact('medical_facilities'));
    }


    public function doctor_mails()
    {
        $doctorMails = DoctorMail::where('doctor_id', auth('doctor')->id())->orderBy('id','desc')->get();
        return view('doctor.doctor-mails.index', compact('doctorMails'));
    }


    public function doctor_mails_create()
    {
        $doctorMails = DoctorMail::where('doctor_id', auth('doctor')->id())->get();
        return view('doctor.doctor-mails.create', compact('doctorMails'));
    }

    public function my_facility()
    {

        if(auth('doctor')->user()->type->value != "libyan")
        {
            return redirect()->route('doctor.dashboard')->withErrors(['هذه الصفحة متاحة فقط للأطباء الليبيين']);
        }

        return view('doctor.medical-facility.index');
    }


    public function my_facility_create()
    {
        if(auth('doctor')->user()->type->value != "libyan")
        {
            return redirect()->route('doctor.dashboard')->with('error', 'هذه الصفحة متاحة فقط للأطباء الليبيين');
        }

   
        return view('doctor.medical-facility.create');
    }


    public function my_facility_store(Request $request)
    {


        if(auth('doctor')->user()->type->value != "libyan")
        {
            return redirect()->route('doctor.dashboard')->with('error', 'هذه الصفحة متاحة فقط للأطباء الليبيين');
        }


        $request->validate([
            'type' => "required|in:private_clinic,medical_services",
            'name' => "required|max:255",
            "phone" => "required",
            "city" => "required",
            "address" => "required|max:255",
        ]);

        $medical_facility = new MedicalFacility();
        $medical_facility->type = $request->type;
        $medical_facility->name = $request->name;
        $medical_facility->phone_number = $request->phone;
        $medical_facility->city = $request->city;
        $medical_facility->address = $request->address;
        $medical_facility->manager_id = auth('doctor')->id();
        $medical_facility->branch_id = auth('doctor')->user()->branch_id;
        $medical_facility->membership_status = "under_complete";
        $medical_facility->save();

        return redirect()->route('doctor.my-facility')->with('success', 'تم إنشاء المنشأة الطبية بنجاح');
    }

    public function my_facility_upload_documents()
    {

        if(auth('doctor')->user()->type->value != "libyan")
        {
            return redirect()->route('doctor.dashboard')->withErrors(['هذه الصفحة متاحة فقط للأطباء الليبيين']);
        }



        $medical_facility = MedicalFacility::where('manager_id', auth('doctor')->id())
            ->where('branch_id', auth('doctor')->user()->branch_id)
            ->first();
            
            
        if (!$medical_facility) {
            return redirect()->route('doctor.dashboard')->withErrors(['لا يوجد لديك منشأة طبية مسجلة']);
        }


        if($medical_facility->membership_status->value != "under_complete")
        {
            return redirect()->route('doctor.dashboard')->withErrors(['لا يمكنك رفع المستندات في هذه المرحلة']);
        }

        $medical_facility_type = $medical_facility->type == "private_clinic" ? "single" : "services";
        $fileTypes = FileType::where('type', 'medical_facility')->where('facility_type', $medical_facility_type)->where('for_registration', 1)->get();
        $uploadedFiles = $medical_facility->files()->pluck('file_type_id')->toArray();
        $requiredFileTypes = $fileTypes->whereNotIn('id', $uploadedFiles);
        if ($requiredFileTypes->isEmpty()) {
            return redirect()->route('doctor.dashboard')->with('success', 'جميع المستندات المطلوبة تم رفعها بالفعل');
        }


        return view('doctor.medical-facility.upload-documents', compact('medical_facility', 'requiredFileTypes'));
    }


    public function my_facility_update(Request $request)
    {

      
        $facility = MedicalFacility::where('manager_id', auth('doctor')->id())
            ->where('branch_id', auth('doctor')->user()->branch_id)
            ->first();
        $isPending = in_array($facility->membership_status->value, ['under_approve', 'under_edit']);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
        ]);

        if ($isPending) {
            $facility->update($validated);
            $facility->membership_status =  $facility->renew_number ? 'under_renew'  :  "under_approve";
            $facility->save();

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $docId => $file) {
                    if (!$file) continue;

                    $doc = $facility->files()->where('id', $docId)->first();
                    if ($doc) {
                        Storage::delete($doc->file_path);
                        $path = $file->store("facilities/{$facility->id}", 'public');
                        $doc->update(['file_path' => $path]);
                    }
                }
            }
        }

        return back()->with('success', 'تم تحديث البيانات بنجاح');
    }


    public function renew()
    {
        $medical_facility = auth('doctor')->user()->medicalFacility;
        
        if($medical_facility->renew_number == null)
        {
            $medical_facility->renew_number = 1;
        } else {
            $files_for_renew_number = FileType::where('type', 'medical_facility')
            ->where('facility_type', $medical_facility->type == "private_clinic" ? "single" : "services")
            ->where('for_registration', 0)
            ->count();

            $uploadedFiles = $medical_facility->files()->where('renew_number', $medical_facility->renew_number )->pluck('file_type_id')->toArray();

            if($files_for_renew_number == count($uploadedFiles))
            {
                $medical_facility->renew_number += 1;
            }

        }
        
        $medical_facility->save();
        $medical_facility_type = $medical_facility->type == "private_clinic" ? "single" : "services";
        $fileTypes = FileType::where('type', 'medical_facility')->where('facility_type', $medical_facility_type)->where('for_registration', 0)->get();
        $uploadedFiles = $medical_facility->files()->where('renew_number', $medical_facility->renew_number )->pluck('file_type_id')->toArray();

        $requiredFileTypes = $fileTypes->whereNotIn('id', $uploadedFiles);
        if ($requiredFileTypes->isEmpty()) {
            return redirect()->route('doctor.dashboard')->with('success', 'جميع المستندات المطلوبة تم رفعها بالفعل');
        }
        return view('doctor.medical-facility.upload-documents', compact('medical_facility', 'requiredFileTypes'));
    }

    public function invoices()
    {
        return view('doctor.invoices.index');
    }

    public function invoice_show(Invoice $invoice)
    {
        return view('doctor.invoices.show', compact('invoice'));
    }
}
