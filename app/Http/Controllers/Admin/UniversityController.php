<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of the universities.
     */
    public function index()
    {
        $universities = University::all();
        return view('admin.universities.index', compact('universities'));
    }

    /**
     * Show the form for creating a new university.
     */
    public function create()
    {
        return view('admin.universities.create');
    }

    /**
     * Store a newly created university in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => "required",
        ]);

        $university = University::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء جامعة جديدة: {$university->name}",
            'loggable_id' => $university->id,
            'loggable_type' => University::class,
            'action' => 'create_university',
        ]);

        return redirect()->route(get_area_name() . '.universities.index')
            ->with('success', 'تم إنشاء جامعة جديدة بنجاح.');
    }

    /**
     * Display the specified university.
     */
    public function show(University $university)
    {
        return view('admin.universities.show', compact('university'));
    }

    /**
     * Show the form for editing the specified university.
     */
    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    /**
     * Update the specified university in storage.
     */
    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => "required",
        ]);

        $university->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات الجامعة: {$university->name}",
            'loggable_id' => $university->id,
            'loggable_type' => University::class,
            'action' => 'update_university',
        ]);

        return redirect()->route(get_area_name() . '.universities.index')
            ->with('success', 'تم تعديل بيانات الجامعة بنجاح.');
    }

    /**
     * Remove the specified university from storage.
     */
    public function destroy(University $university)
    {
        $universityName = $university->name;
        $university->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف الجامعة: {$universityName}",
            'loggable_id' => $university->id,
            'loggable_type' => University::class,
            'action' => 'delete_university',
        ]);

        return redirect()->route(get_area_name() . '.universities.index')
            ->with('success', 'تم حذف الجامعة بنجاح.');
    }
}