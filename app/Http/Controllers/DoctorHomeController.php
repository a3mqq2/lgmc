<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Licence;
use App\Enums\ReplyType;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DoctorHomeController extends Controller
{
    public function dashboard()
    {
        return view('doctor.dashboard');
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
}
