<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\Capacity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CapacityController extends Controller
{
    /**
     * Display a listing of the capacities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $capacities = Capacity::paginate(10);
        return view('admin.capacities.index', compact('capacities'));
    }

    /**
     * Show the form for creating a new capacity.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.capacities.create');
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

        Capacity::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء صفه طبيب جديدة"]);

        return redirect()->route(get_area_name().'.capacities.index')
            ->with('success', 'تم إنشاء صفه طبيب جديدة بنجاح.');
    }

    /**
     * Display the specified capacity.
     *
     * @param  \App\Models\Capacity  $capacity
     * @return \Illuminate\Http\Response
     */
    public function show(Capacity $capacity)
    {
        return view('admin.capacities.show', compact('capacity'));
    }

    /**
     * Show the form for editing the specified capacity.
     *
     * @param  \App\Models\Capacity  $capacity
     * @return \Illuminate\Http\Response
     */
    public function edit(Capacity $capacity)
    {
        return view('admin.capacities.edit', compact('capacity'));
    }

    /**
     * Update the specified capacity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Capacity  $capacity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Capacity $capacity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $capacity->update($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات صفه طبيب"]);

        return redirect()->route(get_area_name().'.capacities.index')
            ->with('success', 'تم تعديل بيانات صفه طبيب بنجاح.');
    }

    /**
     * Remove the specified capacity from storage.
     *
     * @param  \App\Models\Capacity  $capacity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Capacity $capacity)
    {
        $capacity->delete();

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف صفه طبيب"]);

        return redirect()->route(get_area_name().'.capacities.index')
            ->with('success', 'تم حذف صفه طبيب بنجاح.');
    }
}
