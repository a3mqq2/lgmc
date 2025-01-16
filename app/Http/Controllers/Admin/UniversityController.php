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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $universities = University::paginate(10);
        return view('admin.universities.index', compact('universities'));
    }

    /**
     * Show the form for creating a new university.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.universities.create');
    }

    /**
     * Store a newly created university in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        University::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء جامعة جديدة"]);

        return redirect()->route(get_area_name().'.universities.index')
            ->with('success', 'تم إنشاء جامعة جديدة بنجاح.');
    }

    /**
     * Display the specified university.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function show(University $university)
    {
        return view('admin.universities.show', compact('university'));
    }

    /**
     * Show the form for editing the specified university.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    /**
     * Update the specified university in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $university->update($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات جامعة"]);

        return redirect()->route(get_area_name().'.universities.index')
            ->with('success', 'تم تعديل بيانات جامعة بنجاح.');
    }

    /**
     * Remove the specified university from storage.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy(University $university)
    {
        $university->delete();

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف جامعة"]);

        return redirect()->route(get_area_name().'.universities.index')
            ->with('success', 'تم حذف جامعة بنجاح.');
    }
}
