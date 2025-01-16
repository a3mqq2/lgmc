<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Log;
use App\Models\DoctorRank;
use Illuminate\Http\Request;


class DoctorRankController extends Controller
{
    /**
     * Display a listing of the doctor_ranks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctor_ranks = DoctorRank::paginate(10);
        return view('admin.doctor_ranks.index', compact('doctor_ranks'));
    }

    /**
     * Show the form for creating a new capacity.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.doctor_ranks.create');
    }

    /**
     * Store a newly created capacity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DoctorRank::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء صفه طبيب جديدة"]);

        return redirect()->route(get_area_name().'.doctor_ranks.index')
            ->with('success', 'تم إنشاء صفه طبيب جديدة بنجاح.');
    }

    /**
     * Display the specified capacity.
     *
     * @param  \App\Models\DoctorRank  $doctor_rank
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorRank $doctor_rank)
    {
        return view('admin.doctor_ranks.show', compact('capacity'));
    }

    /**
     * Show the form for editing the specified capacity.
     *
     * @param  \App\Models\DoctorRank  $doctor_rank
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorRank $doctor_rank)
    {
        return view('admin.doctor_ranks.edit', compact('capacity'));
    }

    /**
     * Update the specified capacity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorRank  $doctor_rank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DoctorRank $doctor_rank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $doctor_rank->update($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات صفه طبيب"]);

        return redirect()->route(get_area_name().'.doctor_ranks.index')
            ->with('success', 'تم تعديل بيانات صفه طبيب بنجاح.');
    }

    /**
     * Remove the specified capacity from storage.
     *
     * @param  \App\Models\DoctorRank  $doctor_rank
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoctorRank $doctor_rank)
    {
        $doctor_rank->delete();

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف صفه طبيب"]);

        return redirect()->route(get_area_name().'.doctor_ranks.index')
            ->with('success', 'تم حذف صفه طبيب بنجاح.');
    }
}
