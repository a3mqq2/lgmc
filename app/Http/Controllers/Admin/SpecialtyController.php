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
        $specialties = Specialty::all();
        return view('admin.specialties.create', compact('specialties'));
    }

    /**
     * Store a newly created specialty in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'nullable|exists:specialties,id'
        ]);

        $specialty = Specialty::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء تخصص: {$specialty->name}",
            'loggable_id' => $specialty->id,
            'loggable_type' => Specialty::class,
            'action' => 'create_specialty',
        ]);

        return redirect()->route(get_area_name() . '.specialties.index')
            ->with('success', 'تم إنشاء التخصص بنجاح.');
    }

    /**
     * Show the form for editing the specified specialty.
     */
    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialties = Specialty::whereNull('specialty_id')->get();
        return view('admin.specialties.edit', compact('specialty', 'specialties'));
    }

    /**
     * Update the specified specialty in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'nullable|exists:specialties,id'
        ]);

        $specialty = Specialty::findOrFail($id);
        $specialty->update([
            'name' => $request->name,
            'specialty_id' => $request->specialty_id,
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل تخصص: {$specialty->name}",
            'loggable_id' => $specialty->id,
            'loggable_type' => Specialty::class,
            'action' => 'update_specialty',
        ]);

        return redirect()->route(get_area_name() . '.specialties.index')
            ->with('success', 'تم تحديث التخصص بنجاح.');
    }

    /**
     * Retrieve sub-specialties for a given specialty.
     */
    public function get_subs($id)
    {
        $specialties = Specialty::where('specialty_id', $id)->get();
        return response()->json($specialties, 200);
    }
}
