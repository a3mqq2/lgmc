<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the specialties.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialties = Specialty::get();
        return view('admin.specialties.index', compact('specialties'));
    }
    

    /**
     * Show the form for creating a new specialty.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.specialties.create', compact('specialties'));
    }

    /**
     * Store a newly created specialty in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'nullable|exists:specialties,id'
        ]);

        $specialty = Specialty::create($request->all());
        Log::create(['user_id' => auth()->id(), 'details' => "تم انشاء تخصص " . $specialty->name]);
        return redirect()->route(get_area_name().'.specialties.index')->with('success', 'تم إنشاء التخصص بنجاح.');
    }


    /**
 * Show the form for editing the specified specialty.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit($id)
{
    $specialty = Specialty::findOrFail($id);
    $specialties = Specialty::where('specialty_id', null)->get();
    return view('admin.specialties.edit', compact('specialty','specialties'));
}

        /**
         * Update the specified specialty in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'specialty_id' => 'nullable|exists:specialties,id'
            ]);

            $specialty = Specialty::findOrFail($id);
            $specialty->name = $request->name;
            $specialty->specialty_id = $request->specialty_id;
            $specialty->save();

            Log::create(['user_id' => auth()->id(), 'details' => "تم تعديل تخصص " . $specialty->name]);
            return redirect()->route(get_area_name().'.specialties.index')->with('success', 'تم تحديث التخصص بنجاح.');
        }



        public function get_subs($id) {
            $specialties = Specialty::where('specialty_id', $id)->get();
            return response()->json($specialties, 200);
        }
}
