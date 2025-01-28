<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Enums\DoctorType;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the blacklisted individuals.
     */

        public function index(Request $request)
        {
            $query = Blacklist::query();
    
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
    
            if ($request->filled('number_phone')) {
                $query->where('number_phone', 'like', '%' . $request->number_phone . '%');
            }
    
            if ($request->filled('doctor_type')) {
                $query->where('doctor_type', $request->doctor_type);
            }
    
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('number_phone', 'like', "%{$search}%");
                });
            }
    
            // If you have additional filters, handle them here
            if ($request->filled('reason')) {
                $query->where('reason', $request->reason);
            }
    
            $blacklists = $query->orderBy('id', 'desc')->paginate(10);
    
            return view('admin.blacklists.index', compact('blacklists'));
        }
    
    

    /**
     * Show the form for creating a new blacklisted individual.
     */
    public function create()
    {
        return view('admin.blacklists.create');
    }

    /**
     * Store a newly created blacklisted individual in storage.
     */

     public function store(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'number_phone' => [
                 'required',
                 'string',
                 'max:20',
                 function ($attribute, $value, $fail) use ($request) {
                     if ($request->input('doctor_type') === 'libyan') {
                         if (!preg_match('/^(218\d{8}|0\d{9})$/', $value)) {
                             $fail('رقم الهاتف غير صالح. يجب أن يبدأ بـ 218 ويتبعه 8 أرقام أو بـ 0 ويتبعه 9 أرقام.');
                         }
                     }
                 },
             ],
             'passport_number' => [
                 'required_unless:doctor_type,libyan',
                 'nullable',
                 'string',
                 'max:50',
             ],
             'id_number' => [
                 'required_if:doctor_type,libyan',
                 'nullable',
                 'string',
                 'max:50',
                 'regex:/^\d{11}$/',
             ],
             'reason' => 'nullable|string|max:255',
             'doctor_type' => 'nullable|in:libyan,foreign,palestinian,visitor',
         ], [
             'name.required' => 'حقل الاسم مطلوب.',
             'name.string' => 'حقل الاسم يجب أن يكون نصاً.',
             'name.max' => 'حقل الاسم لا يجب أن يتجاوز 255 حرفاً.',
             'number_phone.required' => 'حقل رقم الهاتف مطلوب.',
             'number_phone.string' => 'حقل رقم الهاتف يجب أن يكون نصاً.',
             'number_phone.max' => 'حقل رقم الهاتف لا يجب أن يتجاوز 20 حرفاً.',
             'passport_number.required_unless' => 'حقل رقم الجواز مطلوب في حال كان نوع الطبيب غير ليبي.',
             'passport_number.string' => 'حقل رقم الجواز يجب أن يكون نصاً.',
             'passport_number.max' => 'حقل رقم الجواز لا يجب أن يتجاوز 50 حرفاً.',
             'id_number.required_if' => 'حقل الرقم الوطني مطلوب في حال كان نوع الطبيب ليبي.',
             'id_number.string' => 'حقل الرقم الوطني يجب أن يكون نصاً.',
             'id_number.max' => 'حقل الرقم الوطني لا يجب أن يتجاوز 50 حرفاً.',
             'id_number.regex' => 'الرقم الوطني غير صالح. يجب أن يكون 11 رقمًا.',
             'reason.string' => 'حقل السبب يجب أن يكون نصاً.',
             'reason.max' => 'حقل السبب لا يجب أن يتجاوز 255 حرفاً.',
             'doctor_type.in' => 'نوع الطبيب غير صالح.',
         ]);
 
         Blacklist::create($validated);
         Log::create(['user_id' => auth()->user()->id, 'details' => "تم إضافة شخص إلى البلاك ليست: " . $request->name]);         
         return redirect()->route(get_area_name() . '.blacklists.index')
                          ->with('success', 'تمت إضافة الشخص إلى البلاك ليست بنجاح.');
     }

    /**
     * Display the specified blacklisted individual.
     */
    public function show(Blacklist $blacklist)
    {
        return view('admin.blacklists.show', compact('blacklist'));
    }

    /**
     * Show the form for editing the specified blacklisted individual.
     */
    public function edit(Blacklist $blacklist)
    {
        return view('admin.blacklists.edit', compact('blacklist'));
    }

    /**
     * Update the specified blacklisted individual in storage.
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number_phone' => 'required|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'reason' => 'nullable|string|max:255',
            'doctor_type' => 'nullable|in:' . implode(',', Blacklist::getDoctorTypes()),
        ]);

        $blacklist->update($validated);

        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات شخص في البلاك ليست: " . $blacklist->name]);
        return redirect()->route('admin.blacklists.index')->with('success', ['تم تحديث بيانات الشخص في البلاك ليست بنجاح.']);
    }

    /**
     * Remove the specified blacklisted individual from storage.
     */
    public function destroy(Blacklist $blacklist)
    {
        $blacklist->delete();
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إزالة شخص من البلاك ليست: " . $blacklist->name]);
        return redirect()->route('admin.blacklists.index')->with('success', ['تمت إزالة الشخص من البلاك ليست بنجاح.']);
    }
}
