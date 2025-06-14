<?php

namespace App\Http\Controllers;

use App\Models\MedicalFacility;
use App\Models\Branch;
use Illuminate\Http\Request;

class MedicalFacilitiesDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalFacility::with(['manager', 'branch'])
                               ->where('membership_status', 'active'); // فقط المنشآت النشطة

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة حسب الفرع
        if ($request->filled('branch')) {
            $query->where('branch_id', $request->branch);
        }

        // فلترة حسب المدينة/المنطقة
        if ($request->filled('city')) {
            $query->where('address', 'LIKE', "%{$request->city}%");
        }

        // البحث بالاسم أو رقم الترخيص
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('commercial_number', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $facilities = $query->orderBy('name')->paginate(12);

        // بيانات للفلاتر
        $branches = Branch::orderBy('name')->get();
        $types = [
            'private_clinic' => 'عيادة خاصة',
            'medical_services' => 'خدمات طبية',
            'hospital' => 'مستشفى',
            'medical_center' => 'مركز طبي',
            'laboratory' => 'مختبر',
            'pharmacy' => 'صيدلية',
            'radiology_center' => 'مركز أشعة'
        ];

        // إحصائيات
        $stats = [
            'total' => MedicalFacility::where('membership_status', 'active')->count(),
            'private_clinic' => MedicalFacility::where('type', 'private_clinic')->where('membership_status', 'active')->count(),
            'medical_services' => MedicalFacility::where('type', 'medical_services')->where('membership_status', 'active')->count(),
            'hospital' => MedicalFacility::where('type', 'hospital')->where('membership_status', 'active')->count(),
            'others' => MedicalFacility::whereNotIn('type', ['private_clinic', 'medical_services', 'hospital'])->where('membership_status', 'active')->count(),
        ];

        return view('website.medical-facilities-directory.index', compact(
            'facilities', 'branches', 'types', 'stats'
        ));
    }

    public function show($id)
    {
        $facility = MedicalFacility::with([
            'manager', 'branch'
        ])->findOrFail($id);


        return view('website.medical-facilities-directory.show', compact('facility'));
    }
}