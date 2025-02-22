<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\AcademicDegree;
use Illuminate\Http\Request;

class AcademicDegreeController extends Controller
{
    /**
     * Display a listing of the academic degrees.
     */
    public function index()
    {
        $academicDegrees = AcademicDegree::paginate(10);
        return view('admin.academic_degrees.index', compact('academicDegrees'));
    }

    /**
     * Show the form for creating a new academic degree.
     */
    public function create()
    {
        return view('admin.academic_degrees.create');
    }

    /**
     * Store a newly created academic degree in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $academicDegree = AcademicDegree::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء درجة علمية جديدة: {$academicDegree->name}",
            'loggable_id' => $academicDegree->id,
            'loggable_type' => AcademicDegree::class,
            'action' => 'create_academic_degree',
        ]);

        return redirect()->route(get_area_name() . '.academic-degrees.index')
            ->with('success', 'تم إنشاء درجة علمية جديدة بنجاح.');
    }

    /**
     * Display the specified academic degree.
     */
    public function show(AcademicDegree $academicDegree)
    {
        return view('admin.academic_degrees.show', compact('academicDegree'));
    }

    /**
     * Show the form for editing the specified academic degree.
     */
    public function edit(AcademicDegree $academicDegree)
    {
        return view('admin.academic_degrees.edit', compact('academicDegree'));
    }

    /**
     * Update the specified academic degree in storage.
     */
    public function update(Request $request, AcademicDegree $academicDegree)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $academicDegree->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات درجة علمية: {$academicDegree->name}",
            'loggable_id' => $academicDegree->id,
            'loggable_type' => AcademicDegree::class,
            'action' => 'update_academic_degree',
        ]);

        return redirect()->route(get_area_name() . '.academic-degrees.index')
            ->with('success', 'تم تعديل بيانات درجة علمية بنجاح.');
    }

    /**
     * Remove the specified academic degree from storage.
     */
    public function destroy(AcademicDegree $academicDegree)
    {
        $academicDegreeName = $academicDegree->name;
        $academicDegreeId = $academicDegree->id;
        $academicDegree->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف درجة علمية: {$academicDegreeName}",
            'loggable_id' => $academicDegreeId,
            'loggable_type' => AcademicDegree::class,
            'action' => 'delete_academic_degree',
        ]);

        return redirect()->route(get_area_name() . '.academic-degrees.index')
            ->with('success', 'تم حذف درجة علمية بنجاح.');
    }
}
