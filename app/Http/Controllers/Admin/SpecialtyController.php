<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the specialties.
     */
    public function index(Request $request)
    {
        $specialties = Specialty::all();
        return view('admin.specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new specialty.
     */
    public function create()
    {
        return view('admin.specialties.create');
    }

    /**
     * Store a newly created specialty in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $specialty = Specialty::create($request->all());


        return redirect()->route(get_area_name() . '.specialties.index')
            ->with('success', 'تم إنشاء التخصص بنجاح.');
    }

    /**
     * Show the form for editing the specified specialty.
     */
    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);
        return view('admin.specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified specialty in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $specialty = Specialty::findOrFail($id);
        $specialty->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
        ]);

 
        return redirect()->route(get_area_name() . '.specialties.index')
            ->with('success', 'تم تحديث التخصص بنجاح.');
    }

}
