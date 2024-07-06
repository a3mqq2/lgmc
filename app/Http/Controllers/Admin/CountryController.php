<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    /**
     * Display a listing of the countries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries = Country::paginate(10);
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created country in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255'
        ]);

        Country::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء دولة جديدة"]);

        return redirect()->route(get_area_name().'.countries.index')->with('success', 'تم إنشاء الدولة بنجاح.');
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * Update the specified country in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255'
        ]);

        $country = Country::findOrFail($id);
        $country->name = $request->name;
        $country->en_name = $request->en_name;
        $country->save();

        // Log update with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات الدولة"]);

        return redirect()->route(get_area_name().'.countries.index')->with('success', 'تم تحديث الدولة بنجاح.');
    }

    /**
     * Remove the specified country from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        // Log deletion with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف الدولة"]);

        return redirect()->route(get_area_name().'.countries.index')->with('success', 'تم حذف الدولة بنجاح.');
    }
}
