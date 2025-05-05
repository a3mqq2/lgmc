<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Log;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the emails.
     */
    public function index()
    {
        $emails = Email::all();
        return view('admin.emails.index', compact('emails'));
    }

    /**
     * Show the form for creating a new email.
     */
    public function create()
    {
        return view('admin.emails.create');
    }

    /**
     * Store a newly created email in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:emails,email',
        ]);

        $email = Email::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إضافة بريد إلكتروني جديد: {$email->email}",
            'loggable_id' => $email->id,
            'loggable_type' => Email::class,
            'action' => 'create_email',
        ]);

        return redirect()->route(get_area_name() . '.emails.index')
            ->with('success', 'تم إضافة البريد الإلكتروني بنجاح.');
    }

    /**
     * Show the form for editing the specified email.
     */
    public function edit(Email $email)
    {
        return view('admin.emails.edit', compact('email'));
    }

    /**
     * Update the specified email in storage.
     */
    public function update(Request $request, Email $email)
    {
        $request->validate([
            'email' => 'required|email|unique:emails,email,' . $email->id,
        ]);

        $email->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل البريد الإلكتروني: {$email->email}",
            'loggable_id' => $email->id,
            'loggable_type' => Email::class,
            'action' => 'update_email',
        ]);

        return redirect()->route(get_area_name() . '.emails.index')
            ->with('success', 'تم تعديل البريد الإلكتروني بنجاح.');
    }

    /**
     * Remove the specified email from storage.
     */
    public function destroy(Email $email)
    {
        $emailValue = $email->email;
        $email->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف البريد الإلكتروني: {$emailValue}",
            'loggable_id' => $email->id,
            'loggable_type' => Email::class,
            'action' => 'delete_email',
        ]);

        return redirect()->route(get_area_name() . '.emails.index')
            ->with('success', 'تم حذف البريد الإلكتروني بنجاح.');
    }
}
