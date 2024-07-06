<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\AcademicDegree;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcademicDegreeController extends Controller
{
    /**
     * Display a listing of the academic degrees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academicDegrees = AcademicDegree::paginate(10);
        return view('admin.academic_degrees.index', compact('academicDegrees'));
    }

    /**
     * Show the form for creating a new academic degree.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.academic_degrees.create');
    }

    /**
     * Store a newly created academic degree in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        AcademicDegree::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء درجة علمية جديدة"]);

        return redirect()->route(get_area_name().'.academic-degrees.index')
            ->with('success', 'تم إنشاء درجة علمية جديدة بنجاح.');
    }

    /**
     * Display the specified academic degree.
     *
     * @param  \App\Models\AcademicDegree  $academicDegree
     * @return \Illuminate\Http\Response
     */
    public function show(AcademicDegree $academicDegree)
    {
        return view('admin.academic_degrees.show', compact('academicDegree'));
    }

    /**
     * Show the form for editing the specified academic degree.
     *
     * @param  \App\Models\AcademicDegree  $academicDegree
     * @return \Illuminate\Http\Response
     */
    public function edit(AcademicDegree $academicDegree)
    {
        return view('admin.academic_degrees.edit', compact('academicDegree'));
    }

    /**
     * Update the specified academic degree in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcademicDegree  $academicDegree
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcademicDegree $academicDegree)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $academicDegree->update($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات درجة علمية"]);

        return redirect()->route(get_area_name().'.academic-degrees.index')
            ->with('success', 'تم تعديل بيانات درجة علمية بنجاح.');
    }

    /**
     * Remove the specified academic degree from storage.
     *
     * @param  \App\Models\AcademicDegree  $academicDegree
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcademicDegree $academicDegree)
    {
        $academicDegree->delete();

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف درجة علمية"]);

        return redirect()->route(get_area_name().'.academic-degrees.index')
            ->with('success', 'تم حذف درجة علمية بنجاح.');
    }
}
