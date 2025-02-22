<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\DoctorRank;
use Illuminate\Http\Request;

class DoctorRankController extends Controller
{
    /**
     * Display a listing of the doctor ranks.
     */
    public function index()
    {
        $doctor_ranks = DoctorRank::paginate(10);
        return view('admin.doctor_ranks.index', compact('doctor_ranks'));
    }

    /**
     * Show the form for creating a new doctor rank.
     */
    public function create()
    {
        return view('admin.doctor_ranks.create');
    }

    /**
     * Store a newly created doctor rank in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $doctorRank = DoctorRank::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء صفه طبيب جديدة: {$doctorRank->name}",
            'loggable_id' => $doctorRank->id,
            'loggable_type' => DoctorRank::class,
            'action' => 'create_doctor_rank',
        ]);

        return redirect()->route(get_area_name() . '.doctor_ranks.index')
            ->with('success', 'تم إنشاء صفه طبيب جديدة بنجاح.');
    }

    /**
     * Display the specified doctor rank.
     */
    public function show(DoctorRank $doctor_rank)
    {
        return view('admin.doctor_ranks.show', compact('doctor_rank'));
    }

    /**
     * Show the form for editing the specified doctor rank.
     */
    public function edit(DoctorRank $doctor_rank)
    {
        return view('admin.doctor_ranks.edit', compact('doctor_rank'));
    }

    /**
     * Update the specified doctor rank in storage.
     */
    public function update(Request $request, DoctorRank $doctor_rank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $doctor_rank->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات صفه طبيب: {$doctor_rank->name}",
            'loggable_id' => $doctor_rank->id,
            'loggable_type' => DoctorRank::class,
            'action' => 'update_doctor_rank',
        ]);

        return redirect()->route(get_area_name() . '.doctor_ranks.index')
            ->with('success', 'تم تعديل بيانات صفه طبيب بنجاح.');
    }

    /**
     * Remove the specified doctor rank from storage.
     */
    public function destroy(DoctorRank $doctor_rank)
    {
        $name = $doctor_rank->name;
        $doctor_rank->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف صفه طبيب: {$name}",
            'loggable_id' => $doctor_rank->id,
            'loggable_type' => DoctorRank::class,
            'action' => 'delete_doctor_rank',
        ]);

        return redirect()->route(get_area_name() . '.doctor_ranks.index')
            ->with('success', 'تم حذف صفه طبيب بنجاح.');
    }
}
