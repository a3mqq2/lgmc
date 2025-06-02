<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Log;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the institutions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = Institution::orderBy('id','asc')->paginate(30);

        // Log the access to the institutions list


        return view('user.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new institution.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Log access to create institution form
  

        return view('user.institutions.create');
    }

    /**
     * Store a newly created institution in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $institution = Institution::create([
            'name' => $request->name,
            'branch_id' => auth()->user()->branch_id ?? 1, // Automatically assign branch_id
        ]);

        // Log creation with details
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء جهة عمل جديدة: {$institution->name}",
            'loggable_id' => $institution->id,
            'loggable_type' => Institution::class,
            'action' => 'create_institution',
        ]);

        return redirect()->route(get_area_name() . '.institutions.index')
            ->with('success', 'تم إنشاء جهة عمل جديدة بنجاح.');
    }

    /**
     * Display the specified institution.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function show(Institution $institution)
    {
        $this->authorizeInstitution($institution); // Authorization check

        // Log viewing of the institution
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم عرض بيانات جهة العمل: {$institution->name}",
            'loggable_id' => $institution->id,
            'loggable_type' => Institution::class,
            'action' => 'view_institution',
        ]);

        return view('user.institutions.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified institution.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function edit(Institution $institution)
    {


        // Log access to edit form
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم الدخول إلى صفحة تعديل جهة العمل: {$institution->name}",
            'loggable_id' => $institution->id,
            'loggable_type' => Institution::class,
            'action' => 'edit_institution',
        ]);

        return view('user.institutions.edit', compact('institution'));
    }

    /**
     * Update the specified institution in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institution $institution)
    {
        $this->authorizeInstitution($institution); // Authorization check

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $oldName = $institution->name;
        $institution->update([
            'name' => $request->name,
        ]);

        // Log update with details
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل جهة العمل من: {$oldName} إلى: {$institution->name}",
            'loggable_id' => $institution->id,
            'loggable_type' => Institution::class,
            'action' => 'update_institution',
        ]);

        return redirect()->route(get_area_name() . '.institutions.index')
            ->with('success', 'تم تعديل بيانات جهة العمل بنجاح.');
    }

    /**
     * Remove the specified institution from storage.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institution $institution)
    {
        $this->authorizeInstitution($institution); // Authorization check

        if ($institution->doctors->count() > 0) {
            return redirect()->route(get_area_name() . '.institutions.index')
                ->with('error', 'لا يمكن حذف جهة العمل لوجود أطباء مرتبطين بها.');
        }

        $institutionName = $institution->name;
        $institution->delete();

        // Log deletion with details
        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف جهة العمل: {$institutionName}",
            'loggable_id' => $institution->id,
            'loggable_type' => Institution::class,
            'action' => 'delete_institution',
        ]);

        return redirect()->route(get_area_name() . '.institutions.index')
            ->with('success', 'تم حذف جهة العمل بنجاح.');
    }

    /**
     * Authorize that the institution belongs to the same branch as the user.
     *
     * @param  \App\Models\Institution  $institution
     * @return void
     */
    private function authorizeInstitution(Institution $institution)
    {
        if ($institution->branch_id !== auth()->user()->branch_id) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الجهة.');
        }
    }
}
