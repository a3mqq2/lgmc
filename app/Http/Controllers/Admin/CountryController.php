<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the countries.
     */
    public function index(Request $request)
    {
        $countries = Country::all();
        
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new country.
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created country in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        $country = Country::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء دولة جديدة: {$country->nationality_name_ar}",
            'loggable_id' => $country->id,
            'loggable_type' => Country::class,
            'action' => 'create_country',
        ]);

        return redirect()->route(get_area_name() . '.countries.index')
            ->with('success', 'تم إنشاء الدولة بنجاح.');
    }

    /**
     * Show the form for editing the specified country.
     */
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * Update the specified country in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        $country = Country::findOrFail($id);
        $country->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات الدولة: {$country->nationality_name_ar}",
            'loggable_id' => $country->id,
            'loggable_type' => Country::class,
            'action' => 'update_country',
        ]);

        return redirect()->route(get_area_name() . '.countries.index')
            ->with('success', 'تم تحديث الدولة بنجاح.');
    }

    /**
     * Remove the specified country from storage.
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $countryName = $country->nationality_name_ar;
        $countryId = $country->id;
        $country->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف الدولة: {$countryName}",
            'loggable_id' => $countryId,
            'loggable_type' => Country::class,
            'action' => 'delete_country',
        ]);

        return redirect()->route(get_area_name() . '.countries.index')
            ->with('success', 'تم حذف الدولة بنجاح.');
    }
}