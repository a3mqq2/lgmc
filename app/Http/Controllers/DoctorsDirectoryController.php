<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\Branch;
use App\Enums\DoctorType;
use Illuminate\Http\Request;

class DoctorsDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['specialty1', 'doctorRank', 'branch', 'countryGraduation'])
                      ->where('documents_completed', true); // فقط الأطباء المكتملي البيانات

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة حسب التخصص
        if ($request->filled('specialty')) {
            $query->where('specialty_1_id', $request->specialty);
        }

        // فلترة حسب الصفة
        if ($request->filled('rank')) {
            $query->where('doctor_rank_id', $request->rank);
        }

        // فلترة حسب الفرع (للأطباء الليبيين)
        if ($request->filled('branch')) {
            $query->where('branch_id', $request->branch);
        }

        // البحث بالاسم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('name')->paginate(12);

        // بيانات للفلاتر
        $specialties = Specialty::orderBy('name')->get();
        $ranks = DoctorRank::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $types = DoctorType::cases();

        // إحصائيات
        $stats = [
            'total' => Doctor::where('documents_completed', true)->count(),
            'libyan' => Doctor::where('type', DoctorType::Libyan)->where('documents_completed', true)->count(),
            'foreign' => Doctor::where('type', DoctorType::Foreign)->where('documents_completed', true)->count(),
            'palestinian' => Doctor::where('type', DoctorType::Palestinian)->where('documents_completed', true)->count(),
            'visitor' => Doctor::where('type', DoctorType::Visitor)->where('documents_completed', true)->count(),
        ];

        return view('website.doctors-directory.index', compact(
            'doctors', 'specialties', 'ranks', 'branches', 'types', 'stats'
        ));
    }

    public function show($id)
    {
        $doctor = Doctor::with([
            'specialty1', 'specialty2', 'specialty3', 
            'doctorRank', 'branch', 'country', 
            'countryGraduation', 'academicDegree',
            'qualificationUniversity', 'academicDegreeUniversity'
        ])->findOrFail($id);

        if (!$doctor->documents_completed) {
            abort(404);
        }

        return view('website.doctors-directory.show', compact('doctor'));
    }
}