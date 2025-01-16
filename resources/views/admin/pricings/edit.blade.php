<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pricings = Pricing::all();
        return view('pricings.index', compact('pricings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pricings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:membership,license,service',
            'entity_type' => 'required|string|in:doctor,medical_facility',
            'doctor_type' => 'nullable|string|in:libyan,foreign,visitor,palestinian',
        ]);

        // If entity_type is not 'doctor', ensure doctor_type is null
        if ($validated['entity_type'] !== 'doctor') {
            $validated['doctor_type'] = null;
        }

        Pricing::create($validated);

        return redirect()->route(get_area_name().'.pricings.index')
            ->with('success', 'تمت إضافة التسعيرة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pricing $pricing)
    {
        return view('pricings.show', compact('pricing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pricing $pricing)
    {
        return view('pricings.edit', compact('pricing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pricing $pricing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:membership,license,service',
            'entity_type' => 'required|string|in:doctor,medical_facility',
            'doctor_type' => 'nullable|string|in:libyan,foreign,visitor,palestinian',
        ]);

        // If entity_type is not 'doctor', ensure doctor_type is null
        if ($validated['entity_type'] !== 'doctor') {
            $validated['doctor_type'] = null;
        }

        $pricing->update($validated);

        return redirect()->route(get_area_name().'.pricings.index')
            ->with('success', 'تم تحديث التسعيرة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pricing $pricing)
    {
        $pricing->delete();

        return redirect()->route(get_area_name().'.pricings.index')
            ->with('success', 'تم حذف التسعيرة بنجاح');
    }
}
