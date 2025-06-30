<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Licence;
use App\Models\Doctor;
use App\Models\MedicalFacility;
use App\Models\DoctorRank;
use App\Models\Specialty;
use App\Models\MedicalFacilityType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $vaults = Vault::when(get_area_name() == "user", function ($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->get();

        $branches = Branch::all();
        return view('general.reports.index', compact('vaults', 'branches'));
    }

    /**
     * تقرير تسجيل الأطباء
     */
    public function doctorsRegistration(Request $request)
    {
        $query = Doctor::with(['doctorRank', 'specialty1', 'medicalFacilityWork', 'branch']);

        // تطبيق الفلاتر
        $this->applyDoctorFilters($query, $request);

        $doctors = $query->orderBy('registered_at', 'desc')->get();
        
        $data = [
            'doctors' => $doctors,
            'filters' => $request->all(),
            'title' => 'تقرير تسجيل الأطباء',
            'generated_at' => now(),
            'total_count' => $doctors->count(),
            'filter_summary' => $this->getDoctorFilterSummary($request)
        ];

        return view('general.reports.reports', $data);
    }

    /**
     * تقرير أذونات المزاولة للأطباء
     */
    public function doctorsLicenses(Request $request)
    {
        $query = Licence::with(['doctor.doctorRank', 'doctor.specialty1', 'doctor.branch'])
                        ->whereNotNull('doctor_id');

        // تطبيق فلاتر التواريخ
        if ($request->from_date) {
            $query->whereDate('issued_date', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('issued_date', '<=', $request->to_date);
        }

        // فلتر الصفة
        if ($request->doctor_rank_id) {
            $query->where('doctor_rank_id', $request->doctor_rank_id);
        }

        // فلتر التخصص
        if ($request->specialty_id) {
            $query->whereHas('doctor', function($q) use ($request) {
                $q->where('specialty_1_id', $request->specialty_id);
            });
        }

        // فلتر نوع الطبيب
        if ($request->doctor_type) {
            $query->where('doctor_type', $request->doctor_type);
        }

        // فلتر الفرع للمستخدمين العاديين
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $licenses = $query->orderBy('issued_date', 'desc')->get();
        
        $data = [
            'licenses' => $licenses,
            'filters' => $request->all(),
            'title' => 'تقرير أذونات المزاولة للأطباء',
            'generated_at' => now(),
            'total_count' => $licenses->count(),
            'filter_summary' => $this->getLicenseFilterSummary($request)
        ];

        return view('general.reports.reports', $data);
    }

    /**
     * تقرير تسجيل المنشآت الطبية
     */
    public function medicalFacilitiesRegistration(Request $request)
    {
        $query = MedicalFacility::with(['type', 'branch', 'manager']);

        // تطبيق فلاتر التواريخ
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // فلتر حالة المنشأة
        if ($request->facility_status) {
            $query->where('membership_status', $request->facility_status);
        }

        // فلتر نوع المنشأة
        if ($request->facility_type) {
            $query->where('medical_facility_type_id', $request->facility_type);
        }

        // فلتر الفرع للمستخدمين العاديين
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $facilities = $query->orderBy('created_at', 'desc')->get();
        
        $data = [
            'facilities' => $facilities,
            'filters' => $request->all(),
            'title' => 'تقرير تسجيل المنشآت الطبية',
            'generated_at' => now(),
            'total_count' => $facilities->count(),
            'filter_summary' => $this->getFacilityFilterSummary($request)
        ];

        return view('general.reports.reports', $data);
    }

    /**
     * تقرير أذونات المنشآت الطبية
     */
    public function medicalFacilitiesLicenses(Request $request)
    {
        $query = Licence::with(['medicalFacility.type', 'medicalFacility.branch'])
                        ->whereNotNull('medical_facility_id');

        // تطبيق فلاتر التواريخ
        if ($request->from_date) {
            $query->whereDate('issued_date', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('issued_date', '<=', $request->to_date);
        }

        // فلتر حالة الإذن
        if ($request->license_status) {
            $query->where('status', $request->license_status);
        }

        // فلتر نوع المنشأة
        if ($request->facility_type) {
            $query->whereHas('medicalFacility', function($q) use ($request) {
                $q->where('medical_facility_type_id', $request->facility_type);
            });
        }

        // فلتر الفرع للمستخدمين العاديين
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $licenses = $query->orderBy('issued_date', 'desc')->get();
        
        $data = [
            'licenses' => $licenses,
            'filters' => $request->all(),
            'title' => 'تقرير أذونات المنشآت الطبية',
            'generated_at' => now(),
            'total_count' => $licenses->count(),
            'filter_summary' => $this->getFacilityLicenseFilterSummary($request)
        ];

        return view('general.reports.reports', $data);
    }

    /**
     * تطبيق فلاتر الأطباء المشتركة
     */
    /**
     * تطبيق فلاتر الأطباء المشتركة مع فلترة متقدمة للتراخيص
     */
    private function applyDoctorFilters($query, Request $request)
    {
        // فلتر التواريخ
        if ($request->from_date) {
            $query->whereDate('registered_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('registered_at', '<=', $request->to_date);
        }

        // فلتر أماكن العمل من خلال التراخيص النشطة
        if ($request->work_places && !empty(array_filter($request->work_places))) {
            $query->whereHas('licenses', function($licenseQuery) use ($request) {
                // فقط التراخيص النشطة أو غير المنتهية الصلاحية
                $licenseQuery->where(function($statusQuery) {
                    $statusQuery->where('status', 'active')
                            ->orWhere('expiry_date', '>', now())
                            ->orWhereNull('expiry_date');
                });
                
                $licenseQuery->where(function($workPlaceQuery) use ($request) {
                    foreach (array_filter($request->work_places) as $place) {
                        $workPlaceQuery->orWhere(function($q) use ($place) {
                            // البحث في المنشآت الطبية
                            $q->whereHas('medicalFacility', function($facilityQuery) use ($place) {
                                $facilityQuery->where('name', 'LIKE', '%' . $place . '%');
                            })
                            // البحث في جهات العمل (المؤسسات)
                            ->orWhereHas('institution', function($institutionQuery) use ($place) {
                                $institutionQuery->where('name', 'LIKE', '%' . $place . '%');
                            });
                        });
                    }
                });
            });
        }

        // فلتر المنشآت الطبية من خلال التراخيص النشطة
        if ($request->medical_facilities && !empty(array_filter($request->medical_facilities))) {
            $query->whereHas('licenses', function($licenseQuery) use ($request) {
                $licenseQuery->whereIn('workin_medical_facility_id', array_filter($request->medical_facilities))
                            ->where(function($statusQuery) {
                                $statusQuery->where('status', 'active')
                                        ->orWhere('expiry_date', '>', now())
                                        ->orWhereNull('expiry_date');
                            });
            });

        }

        // فلتر جهات العمل (المؤسسات) من خلال التراخيص
        if ($request->institutions && !empty(array_filter($request->institutions))) {
            $query->whereHas('licenses', function($licenseQuery) use ($request) {
                $licenseQuery->whereIn('institution_id', array_filter($request->institutions))
                            ->where(function($statusQuery) {
                                $statusQuery->where('status', 'active')
                                        ->orWhere('expiry_date', '>', now())
                                        ->orWhereNull('expiry_date');
                            });
            });
        }

        // فلتر الصفة
        if ($request->doctor_rank_id) {
            $query->where('doctor_rank_id', $request->doctor_rank_id);
        }

        // فلتر التخصص
        if ($request->specialty_id) {
            $query->where('specialty_1_id', $request->specialty_id);
        }

        // فلتر حالة العضوية
        if ($request->membership_status) {
            $query->where('membership_status', $request->membership_status);
        }

        // فلتر نوع الطبيب
        if ($request->doctor_type) {
            $query->where('type', $request->doctor_type);
        }

        // فلتر التراخيص النشطة فقط (اختياري)
        if ($request->active_licenses_only) {
            $query->whereHas('licenses', function($licenseQuery) {
                $licenseQuery->where('status', 'active')
                            ->where('expiry_date', '>', now());
            });
        }

        // فلتر الفرع للمستخدمين العاديين
        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    /**
     * إحصائيات سريعة للتقارير
     */
    public function getQuickStats()
    {
        $branchCondition = get_area_name() == "user" 
            ? ['branch_id' => auth()->user()->branch_id] 
            : [];

        $stats = [
            'total_doctors' => Doctor::where($branchCondition)->count(),
            'active_doctors' => Doctor::where($branchCondition)
                                     ->where('membership_status', 'active')->count(),
            'expired_doctors' => Doctor::where($branchCondition)
                                      ->where('membership_status', 'expired')->count(),
            'total_facilities' => MedicalFacility::where($branchCondition)->count(),
            'active_facilities' => MedicalFacility::where($branchCondition)
                                                 ->where('membership_status', 'active')->count(),
            'total_licenses_this_month' => Licence::where($branchCondition)
                                                 ->whereMonth('issued_date', now()->month)
                                                 ->whereYear('issued_date', now()->year)
                                                 ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * ملخص فلاتر الأطباء
     */
    private function getDoctorFilterSummary(Request $request)
    {
        $summary = [];
        
        if ($request->from_date || $request->to_date) {
            $dateRange = '';
            if ($request->from_date) $dateRange .= 'من: ' . $request->from_date;
            if ($request->to_date) $dateRange .= ($dateRange ? ' ' : '') . 'إلى: ' . $request->to_date;
            $summary['تاريخ التسجيل'] = $dateRange;
        }
        
        if ($request->doctor_rank_id) {
            $rank = DoctorRank::find($request->doctor_rank_id);
            $summary['الصفة'] = $rank ? $rank->name : 'غير محدد';
        }
        
        if ($request->specialty_id) {
            $specialty = Specialty::find($request->specialty_id);
            $summary['التخصص'] = $specialty ? $specialty->name : 'غير محدد';
        }
        
        if ($request->membership_status) {
            $statuses = [
                'active' => 'نشط',
                'expired' => 'منتهي الصلاحية',
                'suspended' => 'معلق',
                'banned' => 'محظور'
            ];
            $summary['حالة العضوية'] = $statuses[$request->membership_status] ?? $request->membership_status;
        }
        
        if ($request->doctor_type) {
            $types = [
                'libyan' => 'ليبي',
                'foreign' => 'أجنبي',
                'palestinian' => 'فلسطيني',
                'visitor' => 'زائر'
            ];
            $summary['نوع الطبيب'] = $types[$request->doctor_type] ?? $request->doctor_type;
        }
        
        return $summary;
    }

    /**
     * ملخص فلاتر تراخيص الأطباء
     */
    private function getLicenseFilterSummary(Request $request)
    {
        $summary = [];
        
        if ($request->from_date || $request->to_date) {
            $dateRange = '';
            if ($request->from_date) $dateRange .= 'من: ' . $request->from_date;
            if ($request->to_date) $dateRange .= ($dateRange ? ' ' : '') . 'إلى: ' . $request->to_date;
            $summary['تاريخ الإصدار'] = $dateRange;
        }
        
        if ($request->doctor_rank_id) {
            $rank = DoctorRank::find($request->doctor_rank_id);
            $summary['الصفة'] = $rank ? $rank->name : 'غير محدد';
        }
        
        if ($request->specialty_id) {
            $specialty = Specialty::find($request->specialty_id);
            $summary['التخصص'] = $specialty ? $specialty->name : 'غير محدد';
        }
        
        if ($request->doctor_type) {
            $types = [
                'libyan' => 'ليبي',
                'foreign' => 'أجنبي',
                'palestinian' => 'فلسطيني',
                'visitor' => 'زائر'
            ];
            $summary['نوع الطبيب'] = $types[$request->doctor_type] ?? $request->doctor_type;
        }
        
        return $summary;
    }

    /**
     * ملخص فلاتر المنشآت
     */
    private function getFacilityFilterSummary(Request $request)
    {
        $summary = [];
        
        if ($request->from_date || $request->to_date) {
            $dateRange = '';
            if ($request->from_date) $dateRange .= 'من: ' . $request->from_date;
            if ($request->to_date) $dateRange .= ($dateRange ? ' ' : '') . 'إلى: ' . $request->to_date;
            $summary['تاريخ التسجيل'] = $dateRange;
        }
        
        if ($request->facility_status) {
            $statuses = [
                'active' => 'نشطة',
                'expired' => 'منتهية الصلاحية',
                'suspended' => 'معلقة',
                'under_approve' => 'قيد الموافقة'
            ];
            $summary['حالة المنشأة'] = $statuses[$request->facility_status] ?? $request->facility_status;
        }
        
        if ($request->facility_type) {
            $type = MedicalFacilityType::find($request->facility_type);
            $summary['نوع المنشأة'] = $type ? $type->name : 'غير محدد';
        }
        
        return $summary;
    }

    /**
     * ملخص فلاتر تراخيص المنشآت
     */
    private function getFacilityLicenseFilterSummary(Request $request)
    {
        $summary = [];
        
        if ($request->from_date || $request->to_date) {
            $dateRange = '';
            if ($request->from_date) $dateRange .= 'من: ' . $request->from_date;
            if ($request->to_date) $dateRange .= ($dateRange ? ' ' : '') . 'إلى: ' . $request->to_date;
            $summary['تاريخ الإصدار'] = $dateRange;
        }
        
        if ($request->license_status) {
            $statuses = [
                'active' => 'نشط',
                'expired' => 'منتهي الصلاحية',
                'suspended' => 'معلق'
            ];
            $summary['حالة الإذن'] = $statuses[$request->license_status] ?? $request->license_status;
        }
        
        if ($request->facility_type) {
            $type = MedicalFacilityType::find($request->facility_type);
            $summary['نوع المنشأة'] = $type ? $type->name : 'غير محدد';
        }
        
        return $summary;
    }

    /**
     * تصدير البيانات إلى Excel
     */
    public function exportToExcel(Request $request)
    {
        $type = $request->get('type');
        
        switch ($type) {
            case 'doctors':
                return $this->exportDoctorsToExcel($request);
            case 'facilities':
                return $this->exportFacilitiesToExcel($request);
            case 'licenses':
                return $this->exportLicensesToExcel($request);
            default:
                return redirect()->back()->with('error', 'نوع التصدير غير محدد');
        }
    }

    private function exportDoctorsToExcel(Request $request)
    {
        // يمكنك استخدام Laravel Excel هنا
        // أو تنفيذ منطق تصدير مخصص
        return response()->json(['message' => 'سيتم تنفيذ تصدير الأطباء قريباً']);
    }

    private function exportFacilitiesToExcel(Request $request)
    {
        // يمكنك استخدام Laravel Excel هنا
        return response()->json(['message' => 'سيتم تنفيذ تصدير المنشآت قريباً']);
    }

    private function exportLicensesToExcel(Request $request)
    {
        // يمكنك استخدام Laravel Excel هنا
        return response()->json(['message' => 'سيتم تنفيذ تصدير التراخيص قريباً']);
    }
}