<?php

namespace App\Http\Controllers\Common;

use App\Enums\Department;
use App\Models\User;
use App\Enums\Status;
use App\Models\Doctor;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Ticket::query();

        if(request("category")) 
        {
            $query->where("category", request("category"));
        }

        if(request("status")) 
        {
            $query->where("status", request("status"));
        }

       if(request('init_doctor_id'))
       {
              $query->where('init_doctor_id', request('init_doctor_id'));
       }


       if(request('my'))
       {
              $query->where('assigned_user_id', auth()->id());
       }


       

       if(get_area_name() == 'admin')
       {
            $query->where('department', 'management');
       }


       if(get_area_name() == "user")
       {
            $query->where('department', 'operation');
            $query->where('branch_id', auth()->user()->branch_id);
       }

       if(get_area_name() == "finance")
       {
            $query->where('department', 'finance');
       }


        $tickets = $query->latest()->paginate(10);

        return view('general.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->get();
        $users = User::where('branch_id', auth()->user()->branch_id)->get();

        return view('general.tickets.create', compact('doctors', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form input
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'ticket_type'  => 'required|in:user,doctor',
            'department'   => 'required',
            'category'     => 'required',
            'priority'      => 'required',
            'init_user_id' => 'nullable|exists:users,id',
            'init_doctor_id' => 'nullable|exists:doctors,id',
            'attachment'   => 'nullable|file|mimes:jpeg,png,pdf,docx,txt|max:2048', 
            // Adjust max file size, mime types as needed
        ]);


        // Determine whether it's a doctor or user ticket
        $initUserId   = null;
        $initDoctorId = null;

        if ($request->ticket_type === 'user') {
            $initUserId = $request->input('init_user_id');
        } else {
            $initDoctorId = $request->input('init_doctor_id');
        }

        // Handle file upload if present
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // Store file in a disk of your choice, e.g. 'public'
            $attachmentPath = $file->store('attachments', 'public');
        }

        

        // Create the ticket
        $ticket = Ticket::create([
            'slug'           => auth()->user()->branch->code . '-TICKET' . '-' . Ticket::count() + 1,
            'title'          => $request->title,
            'body'           => $request->body,
            'init_user_id'   => $initUserId == null ? auth()->id() : $initUserId,
            'init_doctor_id' => $initDoctorId,
            'assigned_user_id' => null,  // optional: you can set it if you have logic for auto-assign
            'department'     => $request->department,
            'category'       => $request->category,
            'status'         => Status::New,  // or fetch from $request if your form includes status
            'attachment'    => $attachmentPath,  // store the path in DB
            'priority'        => $request->priority,
            'closed_at'      => null,
            'closed_by'      => null,
            'branch_id'      => auth()->user()->branch_id,
        ]);

        // Redirect or return response
        return redirect()
            ->route(get_area_name() . '.tickets.index')
            ->with('success', 'تم إنشاء التذكرة بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $users = User::where('branch_id', auth()->user()->branch_id)->get();
        $replies = $ticket->replies()->latest()->where(function($q) use ($ticket) {
            if($ticket->init_user_id) {
                if($ticket->init_user_id == auth()->id())
                {
                    $q->where('reply_type', 'external');
                }
            }
        })->get();
        return view('general.tickets.show', compact('ticket','users','replies'));
    }


    public function reply(Request $request, Ticket $ticket)
    {
        // Validate input
        $validated = $request->validate([
            'replyBody'    => 'required|string',
            'replyType'    => 'required|in:internal,external',
            'closeTicket'  => 'sometimes|boolean',
            'assignToUser' => 'nullable|exists:users,id',
        ]);
    
        // Create a new reply

        if (request('department')) {
            // Attempt to find the department enum case from the request
            $deptEnum = Department::find(request('department'));
            
            // Only append the text if a valid department case is found
            if ($deptEnum !== null) {
                $validated['replyBody'] .=  "<p class='text-muted'>"  . " تمت الإحالة لقسم : " . $deptEnum->label() . '</p>';
            }
        }




      
    
        // If user wants to reassign
        if ($validated['assignToUser']) {
            $ticket->assigned_user_id = $validated['assignToUser'];
            $validated['replyBody'] .=  "<p class='text-muted'>"  . " تمت الإحالة للموظف : " . $ticket->assignedUser->name . '</p>';

        }


        $reply = $ticket->replies()->create([
            'user_id'    => auth()->id(),
            'body'       => $validated['replyBody'],
            'reply_type' => $validated['replyType'],
        ]);


        if($request->department)
        {
            $ticket->department = $request->department;

        }
    
        // CASE 1: Ticket was COMPLETE, now an admin is replying => set 'admin_reply'
        if ($ticket->status->value === 'complete') {
            // We'll assume the user replying is an admin, or you can do a role check here
            $ticket->status = \App\Enums\Status::UserReply; // or 'admin_reply' if storing as string
            $ticket->closed_at = null;  // Optionally un-set closed date/time
            $ticket->closed_by = null;  // Optionally un-set closed_by
        }
        // CASE 2: Ticket was NOT complete, if reply is external => user_reply
        elseif ($validated['replyType'] === 'external') {
            $ticket->status = \App\Enums\Status::UserReply; // or 'user_reply'
        }
    
        // If user also checked "Close ticket" and reply is external, set status to complete
        if ($request->has('closeTicket') && $validated['replyType'] === 'external') {
            $ticket->status    = \App\Enums\Status::Complete; // or 'complete'
            $ticket->closed_at = now();
            $ticket->closed_by = auth()->id();
        }
    
        $ticket->save();
    
        return redirect()
            ->route(get_area_name() . '.tickets.show', $ticket->id)
            ->with('success', 'تم إضافة الرد بنجاح!');
    }
    

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()
            ->route(get_area_name() . '.tickets.index')
            ->with('success', 'تم حذف التذكرة بنجاح!');
    }
}
