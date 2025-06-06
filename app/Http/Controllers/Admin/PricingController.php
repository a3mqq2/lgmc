<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $query = Pricing::query();

        if (request('description')) {
            $query->where('name', 'like', '%' . request('description') . '%');
        }

        if (request('type')) {
            $query->where('type', request('type'));
        }

        
        if (request('entity_type')) {
            $query->where('entity_type', request('entity_type'));
        }


        if (request('doctor_type')) {
            $query->where('doctor_type', request('doctor_type'));
        }


        $pricings = $query->paginate(50);
        return view('admin.pricings.index', compact('pricings'));
    }

    public function create()
    {
        return view('admin.pricings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required',
            'entity_type' => 'required',
        ]);

        Pricing::create($request->all());
        return redirect()->route(get_area_name().'.pricings.index');
    }


    public function edit(Pricing $pricing)
    {
        return view('admin.pricings.edit', compact('pricing'));
    }


    public function update(Request $request, Pricing $pricing)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $pricing->update($request->all());
        return redirect()->route(get_area_name().'.pricings.index')->with('success', 'تم تحديث السعر بنجاح');
    }

    public function destroy(Pricing $pricing)
    {
        $pricing->delete();
        return redirect()->route(get_area_name().'.pricings.index');
    }

}
